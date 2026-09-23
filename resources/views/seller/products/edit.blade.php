@extends('layouts.seller')
@section('title', 'Edit Product Listing')
@section('page-title', 'Edit Surplus Stock Listing')

@section('content')
{{-- Top Navigation Bar --}}
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Back to Inventory
        </a>
        <div>
            <h4 class="fw-800 text-dark mb-0">Edit Surplus Stock Listing</h4>
            <p class="text-muted small mb-0">Product: <strong>{{ $product->name }}</strong></p>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('seller.products.show', $product) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
            <i class="bi bi-eye me-1"></i> View Stats
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">

        {{-- Edit Counter Banner --}}
        <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 {{ $product->remaining_edits === 1 ? 'bg-warning bg-opacity-10 border border-warning' : 'bg-info bg-opacity-10 border border-info' }}">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi {{ $product->remaining_edits === 1 ? 'bi-exclamation-triangle-fill text-warning' : 'bi-info-circle-fill text-info' }} fs-4"></i>
                    <div>
                        <div class="fw-700 text-dark">
                            Edit Allowance: <span class="text-primary-bm">{{ $product->edit_count }} / 2 edits used</span>
                            ({{ $product->remaining_edits }} edit{{ $product->remaining_edits === 1 ? '' : 's' }} remaining)
                        </div>
                        <div class="text-muted small">
                            Saving changes will consume 1 edit. Re-submitting an approved listing temporarily places it under Admin review.
                        </div>
                    </div>
                </div>
                <span class="badge {{ $product->remaining_edits === 1 ? 'bg-warning text-dark' : 'bg-primary text-white' }} px-3 py-2 rounded-pill fw-700">
                    {{ $product->remaining_edits }} Edit Left
                </span>
            </div>
        </div>

        @if($errors->any())
        <div class="alert alert-danger mb-4 rounded-3 shadow-sm">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if($product->status === 'rejected')
        <div class="alert alert-danger mb-4 rounded-3 shadow-sm">
            <i class="bi bi-x-circle-fill me-2"></i>
            <strong>Rejection reason:</strong> {{ $product->rejection_reason }}
            <div class="small mt-1">Fix the issues and save changes to resubmit for review.</div>
        </div>
        @endif

        <form action="{{ route('seller.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="editProductForm">
            @csrf
            @method('PUT')

            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h6 class="fw-800 text-dark mb-4 pb-2 border-bottom d-flex align-items-center gap-2">
                    <i class="bi bi-box-seam text-primary-bm"></i> Item Information
                </h6>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-700 small">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $product->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-700 small">Category <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->icon }} {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-700 small">Brand</label>
                        <input type="text" name="brand" class="form-control" value="{{ old('brand', $product->brand) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-700 small">Item Condition <span class="text-danger">*</span></label>
                        <select name="condition" class="form-select" required>
                            @foreach(['new'=>'Brand New / Box Packed','like_new'=>'Like New / Open Box / Display','good'=>'Good (Unsold Surplus)','fair'=>'Fair (Minor packaging wear)'] as $val => $label)
                            <option value="{{ $val }}" {{ old('condition', $product->condition) === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-700 small">SKU / Code</label>
                        <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-700 small">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" required>{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h6 class="fw-800 text-dark mb-4 pb-2 border-bottom d-flex align-items-center gap-2">
                    <i class="bi bi-tag-fill text-primary-bm"></i> Clearance Pricing
                </h6>
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-700 small">Original Price (₹) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light fw-600">₹</span>
                            <input type="number" name="original_price" id="originalPrice" class="form-control"
                                   value="{{ old('original_price', $product->original_price) }}" oninput="calcDiscount()" min="1" step="0.01" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-700 small text-primary-bm">Clearance Price (₹) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-primary-bm fw-700">₹</span>
                            <input type="number" name="offer_price" id="offerPrice" class="form-control fw-700 text-primary-bm"
                                   value="{{ old('offer_price', $product->offer_price) }}" oninput="calcDiscount()" min="1" step="0.01" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-700 small">Discount</label>
                        <div id="discountBadge" class="p-2 rounded-3 text-center fw-800" style="background:#ECFDF5;color:#065F46;min-height:42px;display:flex;align-items:center;justify-content:center;border:1.5px solid #A7F3D0">
                            {{ round($product->discount_percent) }}% OFF
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-700 small">Available Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $product->quantity) }}" min="0" required>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check mt-md-4 pt-md-2">
                            <input class="form-check-input" type="checkbox" name="is_negotiable" value="1" id="negotiable"
                                   {{ old('is_negotiable', $product->is_negotiable) ? 'checked' : '' }}>
                            <label class="form-check-label fw-600 small" for="negotiable">Clearance price is open to in-store negotiation</label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Existing Images --}}
            @if($product->images->count())
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h6 class="fw-800 text-dark mb-3 pb-2 border-bottom d-flex align-items-center gap-2">
                    <i class="bi bi-images text-primary-bm"></i> Current Photos
                </h6>
                <div class="d-flex gap-3 flex-wrap">
                    @foreach($product->images as $img)
                    <div class="position-relative border rounded-3 overflow-hidden shadow-sm" style="width:90px;height:90px">
                        <img src="{{ $img->url }}" class="w-100 h-100" style="object-fit:cover">
                        @if($img->is_primary)
                            <span class="position-absolute bottom-0 start-0 w-100 text-center bg-dark text-white fw-700"
                                  style="font-size:9px;padding:1px 0">PRIMARY</span>
                        @endif
                        <div class="position-absolute top-0 end-0 d-flex flex-column gap-1 p-1">
                            @if(!$img->is_primary)
                            <form action="{{ route('seller.products.images.primary', [$product, $img]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-xs p-0 shadow-sm" style="background:#fff;border:1px solid #ccc;border-radius:4px;width:20px;height:20px;font-size:.65rem" title="Set as primary photo">★</button>
                            </form>
                            @endif
                            <form action="{{ route('seller.products.images.destroy', [$product, $img]) }}" method="POST"
                                  onsubmit="return confirm('Remove this photo?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs p-0 shadow-sm" style="background:#e53e3e;color:#fff;border:none;border-radius:4px;width:20px;height:20px;font-size:.65rem" title="Remove photo">✕</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Add more images --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h6 class="fw-800 text-dark mb-3 pb-2 border-bottom d-flex align-items-center gap-2">
                    <i class="bi bi-camera-fill text-primary-bm"></i> Add Additional Photos
                </h6>
                <input type="file" name="images[]" class="form-control" multiple accept="image/jpeg,image/png,image/webp">
                <div class="form-text">Max 5 photos total. New uploads will be added to your current photos.</div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h6 class="fw-800 text-dark mb-3 pb-2 border-bottom d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history text-primary-bm"></i> Clearance Duration
                </h6>
                <div class="col-md-6">
                    <label class="form-label fw-700 small">Clearance Deal Expiry Date</label>
                    <input type="date" name="expires_at" class="form-control"
                           value="{{ old('expires_at', $product->expires_at?->format('Y-m-d')) }}">
                </div>
            </div>

            <div class="d-flex gap-3 justify-content-end align-items-center mb-5">
                <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary px-4 rounded-pill">Cancel</a>
                <button type="submit" class="btn btn-primary-bm px-5 py-2 fs-6 rounded-pill" onclick="return confirm('Saving will consume 1 of your 2 available edits for this product. Do you want to proceed?')">
                    <i class="bi bi-check2-circle me-2"></i>Save Changes (Consume 1 Edit)
                </button>
            </div>
        </form>
    </div>

    {{-- Policy Sidebar --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white sticky-top" style="top:95px">
            <h6 class="fw-800 mb-3 text-dark">
                🛡️ 2-Edit Policy Rules
            </h6>
            <ul class="list-unstyled small text-muted d-flex flex-column gap-3 mb-0">
                <li class="d-flex gap-2">
                    <i class="bi bi-check2-circle text-primary-bm mt-1 flex-shrink-0"></i>
                    <span><strong>Free Edits Included:</strong> Each ₹12 listing includes up to 2 free updates to keep details accurate.</span>
                </li>
                <li class="d-flex gap-2">
                    <i class="bi bi-shield-check text-success mt-1 flex-shrink-0"></i>
                    <span><strong>Locking:</strong> After 2 updates, the listing becomes locked to ensure catalog integrity.</span>
                </li>
                <li class="d-flex gap-2">
                    <i class="bi bi-arrow-repeat text-warning mt-1 flex-shrink-0"></i>
                    <span><strong>Admin Verification:</strong> Edited active listings are reviewed swiftly by our moderation team.</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function calcDiscount() {
    const orig = parseFloat(document.getElementById('originalPrice').value)||0;
    const offer = parseFloat(document.getElementById('offerPrice').value)||0;
    const badge = document.getElementById('discountBadge');
    if(orig>0 && offer>0 && offer<orig){
        const pct = Math.round(((orig-offer)/orig)*100);
        badge.innerHTML = `<i class="bi bi-fire me-1"></i> ${pct}% OFF`;
        badge.style.background='#ECFDF5'; badge.style.color='#065F46'; badge.style.borderColor='#A7F3D0';
    } else {
        badge.innerHTML='—'; badge.style.background='#F8FAFC'; badge.style.color='#64748B'; badge.style.borderColor='#E2E8F0';
    }
}
</script>
@endpush
