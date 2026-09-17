@extends('layouts.admin')
@section('title', 'Edit Deal: ' . $product->name)
@section('page-title', 'Edit Clearance Deal')

@section('content')
<div class="d-flex gap-2 mb-4">
    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to Deal Detail
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="card-admin p-4">
            <form action="{{ route('admin.products.update', $product) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label small fw-700">Product / Item Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-700">Category</label>
                        <select name="category_id" class="form-select" required>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-700">Brand</label>
                        <input type="text" name="brand" class="form-control" value="{{ old('brand', $product->brand) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-700">Original Price (₹)</label>
                        <input type="number" step="0.01" name="original_price" id="editOrig" class="form-control"
                               value="{{ old('original_price', $product->original_price) }}" oninput="calcAdminDiscount()" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-700">Clearance Price (₹)</label>
                        <input type="number" step="0.01" name="offer_price" id="editOffer" class="form-control text-primary-bm fw-700"
                               value="{{ old('offer_price', $product->offer_price) }}" oninput="calcAdminDiscount()" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-700">Discount Preview</label>
                        <div id="adminDiscountBadge" class="p-2 rounded-2 text-center fw-800 bg-light border">
                            {{ round($product->discount_percent) }}% OFF
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-700">Available Quantity</label>
                        <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $product->quantity) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-700">Condition</label>
                        <select name="condition" class="form-select" required>
                            <option value="new" {{ old('condition', $product->condition) === 'new' ? 'selected' : '' }}>New</option>
                            <option value="like_new" {{ old('condition', $product->condition) === 'like_new' ? 'selected' : '' }}>Like New</option>
                            <option value="good" {{ old('condition', $product->condition) === 'good' ? 'selected' : '' }}>Good</option>
                            <option value="fair" {{ old('condition', $product->condition) === 'fair' ? 'selected' : '' }}>Fair</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-700">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="approved" {{ old('status', $product->status) === 'approved' ? 'selected' : '' }}>Approved (Live)</option>
                            <option value="pending" {{ old('status', $product->status) === 'pending' ? 'selected' : '' }}>Pending Approval</option>
                            <option value="rejected" {{ old('status', $product->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="sold_out" {{ old('status', $product->status) === 'sold_out' ? 'selected' : '' }}>Sold Out</option>
                            <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Inactive / Deactivated</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-700">Description</label>
                        <textarea name="description" class="form-control" rows="5" required>{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_negotiable" value="1" id="adminNego"
                                   {{ old('is_negotiable', $product->is_negotiable) ? 'checked' : '' }}>
                            <label class="form-check-label small fw-600" for="adminNego">Price is Negotiable</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary px-5 fw-600">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Shop context sidebar --}}
    <div class="col-lg-4">
        <div class="card-admin p-4">
            <h6 class="fw-700 mb-3">Shop Details</h6>
            <div class="d-flex align-items-center gap-3 mb-3">
                <img src="{{ $product->shop->logo_url }}" width="48" height="48" class="rounded-2" style="object-fit:cover">
                <div>
                    <div class="fw-700">{{ $product->shop->name }}</div>
                    <div class="text-muted small">{{ $product->shop->city }}</div>
                </div>
            </div>
            <div class="small text-muted mb-2">Seller: <strong>{{ $product->user->name }}</strong> ({{ $product->user->email }})</div>
            <div class="small text-muted">Total Leads Tracked: <strong>{{ $product->leads->count() }}</strong></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function calcAdminDiscount() {
    const orig = parseFloat(document.getElementById('editOrig').value);
    const offer = parseFloat(document.getElementById('editOffer').value);
    const badge = document.getElementById('adminDiscountBadge');

    if (!isNaN(orig) && !isNaN(offer) && orig > 0 && offer >= 0) {
        const pct = Math.round(((orig - offer) / orig) * 100);
        badge.innerText = `${pct}% OFF`;
    }
}
</script>
@endpush
