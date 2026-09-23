@extends('layouts.seller')
@section('title', 'List Surplus Stock')
@section('page-title', 'List Surplus Stock')

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        {{-- Top Navigation & Header Intro --}}
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
                <i class="bi bi-arrow-left me-1"></i> Back to Surplus Stock
            </a>
            <div class="flex-grow-1">
                <h4 class="fw-800 text-dark mb-0">List New Surplus Stock</h4>
                <p class="text-muted small mb-0">Sell your unsold inventory faster by offering an attractive local clearance deal.</p>
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

        <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data" id="surplusStockForm">
            @csrf

            {{-- 1. Product Identification --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h6 class="fw-800 text-dark mb-4 pb-2 border-bottom d-flex align-items-center gap-2">
                    <i class="bi bi-box-seam text-primary-bm"></i> Item Information
                </h6>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-700 small">Product / Item Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               placeholder="e.g. Men's Slim Fit Cotton Shirts (Lot of 3) or Sony Wireless Headphones"
                               value="{{ old('name') }}" required>
                        <div class="form-text">Give a descriptive name to attract local shoppers.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-700 small">Category <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->icon }} {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-700 small">Brand</label>
                        <input type="text" name="brand" class="form-control" placeholder="e.g. Nike, Samsung, Zara, etc."
                               value="{{ old('brand') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-700 small">Item Condition <span class="text-danger">*</span></label>
                        <select name="condition" class="form-select" required>
                            <option value="new" {{ old('condition','new') === 'new' ? 'selected' : '' }}>Brand New / Box Packed</option>
                            <option value="like_new" {{ old('condition') === 'like_new' ? 'selected' : '' }}>Like New / Open Box / Display</option>
                            <option value="good" {{ old('condition') === 'good' ? 'selected' : '' }}>Good (Unsold Surplus)</option>
                            <option value="fair" {{ old('condition') === 'fair' ? 'selected' : '' }}>Fair (Minor packaging wear)</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-700 small">SKU / Inventory Code (Optional)</label>
                        <input type="text" name="sku" class="form-control" placeholder="e.g. LOT-2024-01" value="{{ old('sku') }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-700 small">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" required
                                  placeholder="Describe the item condition, size, color, specifications, and why you are clearing this inventory at a discount...">{{ old('description') }}</textarea>
                        <div class="form-text">Clear details build trust with shoppers visiting your shop. Minimum 20 characters.</div>
                    </div>
                </div>
            </div>

            {{-- 2. Pricing & Clearance Calculations --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h6 class="fw-800 text-dark mb-4 pb-2 border-bottom d-flex align-items-center gap-2">
                    <i class="bi bi-tag-fill text-primary-bm"></i> Clearance Pricing & Discount
                </h6>

                <div class="alert alert-warning border-0 rounded-3 small mb-4 d-flex align-items-center gap-2">
                    <i class="bi bi-info-circle-fill text-warning fs-5"></i>
                    <div>
                        <strong>Surplus Stock Tip:</strong> Set a genuine clearance price (Clearance Price) significantly lower than the Original Price to sell off your inventory fast!
                    </div>
                </div>

                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-700 small">Original Price (₹) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light fw-600">₹</span>
                            <input type="number" name="original_price" id="originalPrice" class="form-control"
                                   placeholder="1000" min="1" step="0.01" value="{{ old('original_price') }}"
                                   oninput="calculateDiscount()" required>
                        </div>
                        <div class="form-text">Regular retail price / MRP</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-700 small text-primary-bm">Clearance Price (₹) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-primary-bm fw-700">₹</span>
                            <input type="number" name="offer_price" id="offerPrice" class="form-control fw-700 text-primary-bm"
                                   placeholder="599" min="1" step="0.01" value="{{ old('offer_price') }}"
                                   oninput="calculateDiscount()" required>
                        </div>
                        <div class="form-text">Your discounted Surplus Stock price</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-700 small">Calculated Discount</label>
                        <div id="discountBadge" class="p-2 rounded-3 text-center fw-800"
                             style="background:#ECFDF5;color:#065F46;min-height:42px;display:flex;align-items:center;justify-content:center;font-size:1rem;border:1.5px solid #A7F3D0">
                            —
                        </div>
                        <div class="form-text text-center">Computed automatically</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-700 small">Available Quantity to Clear <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" class="form-control" placeholder="e.g. 5" min="1"
                               value="{{ old('quantity', 1) }}" required>
                    </div>

                    <div class="col-md-6">
                        <div class="form-check mt-md-4 pt-md-2">
                            <input class="form-check-input" type="checkbox" name="is_negotiable" value="1"
                                   id="negotiable" {{ old('is_negotiable') ? 'checked' : '' }}>
                            <label class="form-check-label fw-600 small" for="negotiable">
                                Clearance price is open to negotiation in-store
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Product Images --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h6 class="fw-800 text-dark mb-3 pb-2 border-bottom d-flex align-items-center gap-2">
                    <i class="bi bi-camera-fill text-primary-bm"></i> Product Photos <span class="badge bg-light text-muted border ms-2" style="font-size:0.75rem;font-weight:500">Optional</span>
                </h6>
                <div class="mb-3">
                    <label class="form-label fw-700 small">Upload Real Photos of Your Stock (Optional, 1–5 photos)</label>
                    <input type="file" name="images[]" class="form-control @error('images') is-invalid @enderror"
                           multiple accept="image/jpeg,image/png,image/webp" id="imageInput" onchange="previewStockImages(this)">
                    @error('images')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Accepted: JPEG, PNG, WebP. If you don't upload photos now, curated category imagery is automatically assigned!</div>
                </div>
                <div id="imagePreview" class="d-flex gap-2 flex-wrap mt-3"></div>
            </div>

            {{-- 4. Expiry / Optional Settings --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h6 class="fw-800 text-dark mb-3 pb-2 border-bottom d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history text-primary-bm"></i> Deal Duration
                </h6>
                <div class="col-md-6">
                    <label class="form-label fw-700 small">Clearance Deal Expiry Date (Optional)</label>
                    <input type="date" name="expires_at" class="form-control" min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           value="{{ old('expires_at') }}">
                    <div class="form-text">Leave blank if the deal remains active until stock is sold out.</div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="d-flex gap-3 justify-content-end align-items-center mb-5">
                <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary px-4 rounded-pill">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary-bm px-5 py-2 fs-6 rounded-pill">
                    <i class="bi bi-send-fill me-2"></i>Submit For Admin Approval
                </button>
            </div>
        </form>
    </div>

    {{-- Guidance Sidebar --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white sticky-top" style="top:95px">
            <h6 class="fw-800 mb-3 text-dark">
                💡 Surplus Stock Best Practices
            </h6>
            <ul class="list-unstyled small text-muted d-flex flex-column gap-3 mb-0">
                <li class="d-flex gap-2">
                    <i class="bi bi-check-circle-fill text-success mt-1 flex-shrink-0"></i>
                    <span><strong>Realistic Clearance Pricing:</strong> A minimum of 30% to 50% off retail price brings quick foot-traffic to your store.</span>
                </li>
                <li class="d-flex gap-2">
                    <i class="bi bi-check-circle-fill text-success mt-1 flex-shrink-0"></i>
                    <span><strong>Actual Photos:</strong> Take photos in good lighting so customers know exactly what to expect in-store.</span>
                </li>
                <li class="d-flex gap-2">
                    <i class="bi bi-check-circle-fill text-success mt-1 flex-shrink-0"></i>
                    <span><strong>Stock Count:</strong> Keep your available quantity updated so customers don't visit for out-of-stock items.</span>
                </li>
                <li class="d-flex gap-2">
                    <i class="bi bi-check-circle-fill text-success mt-1 flex-shrink-0"></i>
                    <span><strong>Admin Review:</strong> After submitting, our team reviews and approves your deal quickly before it goes live.</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function calculateDiscount() {
    const orig = parseFloat(document.getElementById('originalPrice').value);
    const clear = parseFloat(document.getElementById('offerPrice').value);
    const badge = document.getElementById('discountBadge');

    if (!isNaN(orig) && !isNaN(clear) && orig > 0 && clear >= 0) {
        if (clear >= orig) {
            badge.style.background = '#FEE2E2';
            badge.style.color = '#B91C1C';
            badge.style.borderColor = '#FCA5A5';
            badge.innerText = 'Clearance price must be lower than original price';
        } else {
            const pct = Math.round(((orig - clear) / orig) * 100);
            badge.style.background = '#ECFDF5';
            badge.style.color = '#065F46';
            badge.style.borderColor = '#A7F3D0';
            badge.innerHTML = `<i class="bi bi-fire me-1"></i> ${pct}% OFF CLEARANCE`;
        }
    } else {
        badge.style.background = '#F8FAFC';
        badge.style.color = '#64748B';
        badge.style.borderColor = '#E2E8F0';
        badge.innerText = '—';
    }
}

function previewStockImages(input) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    if (input.files) {
        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'position-relative border rounded-3 overflow-hidden';
                div.style.width = '80px';
                div.style.height = '80px';
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-100 h-100" style="object-fit:cover">
                    ${index === 0 ? '<span class="position-absolute bottom-0 start-0 w-100 text-center bg-dark text-white" style="font-size:9px">MAIN</span>' : ''}
                `;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }
}

// Initial calculation if old values exist
document.addEventListener('DOMContentLoaded', calculateDiscount);
</script>
@endpush
