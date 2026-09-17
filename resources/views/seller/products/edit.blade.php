@extends('layouts.seller')
@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')
<div class="row">
<div class="col-lg-8">

@if($errors->any())
<div class="alert alert-danger mb-4"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

@if($product->status === 'rejected')
<div class="alert alert-danger mb-4">
    <i class="bi bi-x-circle-fill me-2"></i>
    <strong>Rejection reason:</strong> {{ $product->rejection_reason }}
    <div class="small mt-1">Fix the issues and resubmit. The product will go back for review.</div>
</div>
@endif

<form action="{{ route('seller.products.update', $product) }}" method="POST" enctype="multipart/form-data">
@csrf @method('PUT')

<div class="card-bm p-4 mb-4">
    <h6 class="fw-700 mb-4 pb-2 border-bottom">Product Information</h6>
    <div class="row g-3">
        <div class="col-12">
            <label class="form-label fw-600 small">Product Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-600 small">Category <span class="text-danger">*</span></label>
            <select name="category_id" class="form-select" required>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                    {{ $cat->icon }} {{ $cat->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-600 small">Brand</label>
            <input type="text" name="brand" class="form-control" value="{{ old('brand', $product->brand) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-600 small">Condition</label>
            <select name="condition" class="form-select">
                @foreach(['new'=>'New','like_new'=>'Like New','good'=>'Good','fair'=>'Fair'] as $val => $label)
                <option value="{{ $val }}" {{ old('condition', $product->condition) === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-600 small">SKU</label>
            <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}">
        </div>
        <div class="col-12">
            <label class="form-label fw-600 small">Description <span class="text-danger">*</span></label>
            <textarea name="description" class="form-control" rows="5" required>{{ old('description', $product->description) }}</textarea>
        </div>
    </div>
</div>

<div class="card-bm p-4 mb-4">
    <h6 class="fw-700 mb-4 pb-2 border-bottom">Pricing</h6>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label fw-600 small">Original Price (₹) <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text">₹</span>
                <input type="number" name="original_price" id="originalPrice" class="form-control"
                       value="{{ old('original_price', $product->original_price) }}" oninput="calcDiscount()" min="1" step="0.01" required>
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-600 small">Offer Price (₹) <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text">₹</span>
                <input type="number" name="offer_price" id="offerPrice" class="form-control"
                       value="{{ old('offer_price', $product->offer_price) }}" oninput="calcDiscount()" min="1" step="0.01" required>
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-600 small">Current Discount</label>
            <div id="discountBadge" class="p-2 rounded-2 text-center fw-700" style="background:#f0fff4;color:#276749">
                {{ round($product->discount_percent) }}% OFF
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-600 small">Quantity</label>
            <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $product->quantity) }}" min="0" required>
        </div>
        <div class="col-md-6 d-flex align-items-end pb-1">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_negotiable" value="1" id="negotiable"
                       {{ old('is_negotiable', $product->is_negotiable) ? 'checked' : '' }}>
                <label class="form-check-label fw-600 small" for="negotiable">Price Negotiable</label>
            </div>
        </div>
    </div>
</div>

{{-- Existing Images --}}
@if($product->images->count())
<div class="card-bm p-4 mb-4">
    <h6 class="fw-700 mb-3 pb-2 border-bottom">Current Images</h6>
    <div class="d-flex gap-3 flex-wrap">
        @foreach($product->images as $img)
        <div class="position-relative" style="width:90px;height:90px">
            <img src="{{ $img->url }}" class="w-100 h-100 rounded-2" style="object-fit:cover;border:2px solid {{ $img->is_primary ? '#FF6B35' : '#e2e8f0' }}">
            @if($img->is_primary)
                <span class="position-absolute bottom-0 start-50 translate-middle-x"
                      style="font-size:.6rem;background:#FF6B35;color:#fff;padding:1px 5px;border-radius:10px;white-space:nowrap">Main</span>
            @endif
            <div class="position-absolute top-0 end-0 d-flex flex-column gap-1 p-1">
                @if(!$img->is_primary)
                <form action="{{ route('seller.products.images.primary', [$product, $img]) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-xs p-0" style="background:#fff;border:1px solid #ccc;border-radius:4px;width:20px;height:20px;font-size:.65rem" title="Set as main">★</button>
                </form>
                @endif
                <form action="{{ route('seller.products.images.destroy', [$product, $img]) }}" method="POST"
                      onsubmit="return confirm('Remove this image?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-xs p-0" style="background:#e53e3e;color:#fff;border:none;border-radius:4px;width:20px;height:20px;font-size:.65rem" title="Remove">✕</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Add more images --}}
<div class="card-bm p-4 mb-4">
    <h6 class="fw-700 mb-3 pb-2 border-bottom">Add More Images</h6>
    <input type="file" name="images[]" class="form-control" multiple accept="image/jpeg,image/png,image/webp">
    <div class="form-text">Max 5 total images. New images will be added to existing ones.</div>
</div>

<div class="card-bm p-4 mb-4">
    <div class="col-md-6">
        <label class="form-label fw-600 small">Deal Expiry Date</label>
        <input type="date" name="expires_at" class="form-control"
               value="{{ old('expires_at', $product->expires_at?->format('Y-m-d')) }}">
    </div>
</div>

<div class="d-flex gap-3 justify-content-end">
    <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
    <button type="submit" class="btn btn-primary-bm px-5">
        <i class="bi bi-check2-circle me-2"></i>Save Changes
    </button>
</div>
</form>
</div>
</div>
@endsection

@push('scripts')
<script>
function calcDiscount() {
    const orig = parseFloat(document.getElementById('originalPrice').value)||0;
    const offer = parseFloat(document.getElementById('offerPrice').value)||0;
    const badge = document.getElementById('discountBadge');
    if(orig>0&&offer>0&&offer<orig){
        const pct = Math.round(((orig-offer)/orig)*100);
        badge.innerHTML = pct+'% OFF';
        badge.style.background='#f0fff4'; badge.style.color='#276749';
    }else{
        badge.innerHTML='—'; badge.style.background='#f7f8fa'; badge.style.color='#718096';
    }
}
</script>
@endpush
