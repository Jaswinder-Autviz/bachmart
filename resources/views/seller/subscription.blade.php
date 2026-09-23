@extends('layouts.seller')
@section('title', 'Pay-Per-Product Listing')
@section('page-title', 'Product Listing & Credits')

@section('content')

{{-- 1. Main Highlights Banner --}}
<div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background: linear-gradient(135deg, #12291E 0%, #193D2C 100%); color:#FFFFFF;">
    <div class="row align-items-center g-3">
        <div class="col-lg-8">
            <div class="d-inline-flex align-items-center gap-1.5 px-3 py-1 rounded-pill mb-2 fw-700 small" style="background:rgba(255,100,51,0.25); color:#FF8A65;">
                <i class="bi bi-tag-fill"></i> PAY-AS-YOU-GO LISTING &bull; ZERO PACKAGES
            </div>
            <h3 class="fw-900 text-white mb-2">Flat <span style="color:#FF6433;">₹12 Per Product</span> Listing</h3>
            <p class="text-light text-opacity-75 small mb-0" style="max-width:600px; line-height:1.6;">
                No complicated monthly subscriptions or locked packs. Simply pay ₹12 whenever you want to list a surplus stock item. Connect directly with local buyers on WhatsApp and pay zero sales commission.
            </p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <div class="d-inline-block bg-white text-dark rounded-4 p-3.5 text-center shadow-sm" style="min-width:190px;">
                <div class="text-muted small fw-700 text-uppercase" style="letter-spacing:0.5px;">Flat Listing Rate</div>
                <div class="fw-900 fs-1 text-primary-bm" style="line-height:1.1; margin: 4px 0;">₹12</div>
                <div class="text-muted small fw-600">per product listed</div>
            </div>
        </div>
    </div>
</div>

{{-- 2. Current Listing Balance & Capacity Overview --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-3.5 bg-white h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary-subtle text-primary-bm d-flex align-items-center justify-content-center" style="width:48px; height:48px; font-size:1.3rem;">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
                <div>
                    <div class="text-muted small fw-700">Currently Listed</div>
                    <div class="fs-4 fw-900 text-dark">{{ $totalListedProducts }} <span class="text-muted fs-6 fw-500">items</span></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-3.5 bg-white h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width:48px; height:48px; font-size:1.3rem;">
                    <i class="bi bi-check2-circle"></i>
                </div>
                <div>
                    <div class="text-muted small fw-700">Available Credits</div>
                    <div class="fs-4 fw-900 text-success">{{ $remainingCredits }} <span class="text-muted fs-6 fw-500">items</span></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-3.5 bg-white h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width:48px; height:48px; font-size:1.3rem;">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div>
                    <div class="text-muted small fw-700">Listing Cost</div>
                    <div class="fs-4 fw-900 text-dark">₹12 <span class="text-muted fs-6 fw-500">/ item</span></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 3. Direct Pay-Per-Product Form (No Packs) --}}
<div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-4 bg-white" style="border: 1.5px solid #EAE6DF !important;">
    <div class="row g-4 align-items-center">
        <div class="col-lg-7">
            <div class="d-inline-flex align-items-center gap-1.5 px-2.5 py-1 rounded-pill mb-2 bg-light text-secondary small fw-700">
                <i class="bi bi-plus-circle text-primary-bm"></i> Add Product Listing Credits
            </div>
            <h4 class="fw-900 text-dark mb-2">How many surplus products do you want to list?</h4>
            <p class="text-muted small mb-4">
                Enter the exact quantity of products. At flat ₹12 per item, your credits will be added immediately.
            </p>

            {{-- Quick Select Chips --}}
            <div class="mb-3">
                <label class="form-label small fw-700 text-muted mb-2">Quick Select Quantity:</label>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3 py-1.5 fw-700 rounded-pill quick-qty-btn" data-qty="1">1 Item (₹12)</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3 py-1.5 fw-700 rounded-pill quick-qty-btn" data-qty="3">3 Items (₹36)</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3 py-1.5 fw-700 rounded-pill quick-qty-btn" data-qty="5">5 Items (₹60)</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3 py-1.5 fw-700 rounded-pill quick-qty-btn" data-qty="10">10 Items (₹120)</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3 py-1.5 fw-700 rounded-pill quick-qty-btn" data-qty="20">20 Items (₹240)</button>
                </div>
            </div>

            {{-- Quantity Input Form --}}
            <form action="{{ route('seller.subscription.buy-credits') }}" method="POST" id="buyCreditsForm">
                @csrf
                <div class="row g-3 align-items-center mb-4">
                    <div class="col-sm-6">
                        <label class="form-label small fw-700 text-dark mb-1">Number of Products:</label>
                        <div class="input-group">
                            <button class="btn btn-outline-secondary fw-800" type="button" id="btnMinus" style="width:44px;">−</button>
                            <input type="number" name="quantity" id="inputQuantity" value="1" min="1" max="500" class="form-control text-center fw-800 fs-5" required>
                            <button class="btn btn-outline-secondary fw-800" type="button" id="btnPlus" style="width:44px;">+</button>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-2.5 rounded-3 bg-light border text-center">
                            <div class="text-muted small fw-700">Total Payable Amount</div>
                            <div class="fs-3 fw-900 text-primary-bm" id="displayTotal">₹12</div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary-bm w-100 py-3 fw-800 fs-6 shadow-sm rounded-3">
                    <i class="bi bi-lightning-charge-fill me-1"></i> Pay <span id="btnPayAmount">₹12</span> & Add Listing Credits
                </button>
            </form>
        </div>

        {{-- Right Benefits Card --}}
        <div class="col-lg-5">
            <div class="p-4 rounded-4" style="background:#FAF8F5; border: 1px solid #ECE7DF;">
                <h6 class="fw-800 text-dark mb-3"><i class="bi bi-shield-check text-success me-1.5"></i> Seller Benefits</h6>
                <ul class="list-unstyled d-flex flex-column gap-3 mb-0 small">
                    <li class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-check-circle-fill text-success fs-6 mt-0.5"></i>
                        <span><strong>Flat ₹12 / Product:</strong> Absolutely no monthly subscription fees or compulsory bundles.</span>
                    </li>
                    <li class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-check-circle-fill text-success fs-6 mt-0.5"></i>
                        <span><strong>0% Sales Commission:</strong> Customers visit your store and pay you directly. We take zero cut.</span>
                    </li>
                    <li class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-check-circle-fill text-success fs-6 mt-0.5"></i>
                        <span><strong>Direct WhatsApp Leads:</strong> Shoppers click and chat directly with you to check item stock.</span>
                    </li>
                    <li class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-check-circle-fill text-success fs-6 mt-0.5"></i>
                        <span><strong>Credits Never Expire:</strong> Your purchased product listing credits remain safe in your wallet until you use them.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- 4. Payment & Listing History --}}
@if($history->count())
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <h6 class="fw-800 text-dark mb-3"><i class="bi bi-receipt text-primary-bm me-1.5"></i> Listing Payments History</h6>
    <div class="table-responsive">
        <table class="table table-sm align-middle mb-0" style="font-size:.875rem">
            <thead style="background:#f7f8fa">
                <tr>
                    <th class="ps-3 py-2">Details</th>
                    <th>Rate</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th class="pe-3">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($history as $sub)
                <tr>
                    <td class="ps-3 fw-700 text-dark">{{ $sub->subscriptionPlan->name }}</td>
                    <td class="text-muted">₹12 / item</td>
                    <td class="fw-800 text-primary-bm">₹{{ number_format($sub->amount_paid) }}</td>
                    <td><span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1 text-capitalize fw-700">Paid & Active</span></td>
                    <td class="pe-3 text-muted">{{ $sub->created_at->format('d M Y, h:i A') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputQty = document.getElementById('inputQuantity');
    const displayTotal = document.getElementById('displayTotal');
    const btnPayAmount = document.getElementById('btnPayAmount');
    const btnMinus = document.getElementById('btnMinus');
    const btnPlus = document.getElementById('btnPlus');
    const quickBtns = document.querySelectorAll('.quick-qty-btn');

    const RATE = 12; // Flat ₹12 per product

    function recalculate() {
        let val = parseInt(inputQty.value) || 1;
        if (val < 1) val = 1;
        if (val > 500) val = 500;
        inputQty.value = val;

        const total = val * RATE;
        displayTotal.textContent = '₹' + total;
        btnPayAmount.textContent = '₹' + total;
    }

    if (btnMinus) {
        btnMinus.addEventListener('click', function() {
            let val = parseInt(inputQty.value) || 1;
            if (val > 1) {
                inputQty.value = val - 1;
                recalculate();
            }
        });
    }

    if (btnPlus) {
        btnPlus.addEventListener('click', function() {
            let val = parseInt(inputQty.value) || 1;
            inputQty.value = val + 1;
            recalculate();
        });
    }

    if (inputQty) {
        inputQty.addEventListener('input', recalculate);
    }

    quickBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const q = parseInt(this.getAttribute('data-qty')) || 1;
            inputQty.value = q;
            recalculate();

            quickBtns.forEach(b => b.classList.remove('btn-primary-bm', 'text-white'));
            quickBtns.forEach(b => b.classList.add('btn-outline-secondary'));
            this.classList.remove('btn-outline-secondary');
            this.classList.add('btn-primary-bm', 'text-white');
        });
    });
});
</script>
@endpush

@endsection
