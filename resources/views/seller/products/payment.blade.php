@extends('layouts.seller')
@section('title', 'Pay Listing Fee')
@section('page-title', 'List Surplus Stock')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-xl-7">
        
        {{-- Breadcrumb / Back Link --}}
        <div class="mb-3">
            <a href="{{ route('seller.products.index') }}" class="text-decoration-none text-muted small fw-600">
                <i class="bi bi-arrow-left me-1"></i>Back to My Products
            </a>
        </div>

        {{-- Main Payment Card --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white" style="border: 1px solid var(--bm-border) !important;">
            <div class="p-4 p-md-5 text-center text-white position-relative"
                 style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);">
                <div class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-600 text-uppercase letter-spacing-1 mb-3" style="font-size:0.75rem">
                    Pay-Per-Product Listing
                </div>
                <h4 class="fw-700 text-white mb-2">Surplus Stock Listing Fee</h4>
                <p class="text-white-50 small mb-4 mx-auto" style="max-width:440px">
                    List any surplus stock or inventory item to reach thousands of local shoppers nearby.
                </p>
                <div class="d-inline-flex align-items-baseline gap-1 bg-white bg-opacity-10 px-4 py-2 rounded-4 border border-white border-opacity-20">
                    <span class="fs-4 fw-600 text-warning">₹</span>
                    <span class="display-6 fw-800 text-white">{{ number_format($listingPrice, 0) }}</span>
                    <span class="text-white-50 small fw-500">/ single listing</span>
                </div>
            </div>

            <div class="p-4 p-md-5">
                {{-- Value Proposition / Inclusions --}}
                <h6 class="fw-700 text-dark mb-3">What's included in this ₹{{ number_format($listingPrice, 0) }} fee:</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-2">
                            <div class="rounded-circle bg-success bg-opacity-10 text-success p-1 d-flex align-items-center justify-content-center mt-1" style="width:22px;height:22px;font-size:0.75rem">
                                <i class="bi bi-check-lg"></i>
                            </div>
                            <div>
                                <div class="fw-700 text-dark small">1 Live Product Listing</div>
                                <div class="text-muted" style="font-size:0.8rem">Published on BachatMart marketplace</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-2">
                            <div class="rounded-circle bg-success bg-opacity-10 text-success p-1 d-flex align-items-center justify-content-center mt-1" style="width:22px;height:22px;font-size:0.75rem">
                                <i class="bi bi-check-lg"></i>
                            </div>
                            <div>
                                <div class="fw-700 text-dark small">2 Free Product Edits</div>
                                <div class="text-muted" style="font-size:0.8rem">Update price, stock, or photos anytime</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-2">
                            <div class="rounded-circle bg-success bg-opacity-10 text-success p-1 d-flex align-items-center justify-content-center mt-1" style="width:22px;height:22px;font-size:0.75rem">
                                <i class="bi bi-check-lg"></i>
                            </div>
                            <div>
                                <div class="fw-700 text-dark small">Direct Buyer Inquiries</div>
                                <div class="text-muted" style="font-size:0.8rem">Phone calls, WhatsApp & Google Map visits</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-2">
                            <div class="rounded-circle bg-success bg-opacity-10 text-success p-1 d-flex align-items-center justify-content-center mt-1" style="width:22px;height:22px;font-size:0.75rem">
                                <i class="bi bi-check-lg"></i>
                            </div>
                            <div>
                                <div class="fw-700 text-dark small">0% Commission on Sales</div>
                                <div class="text-muted" style="font-size:0.8rem">Keep 100% of customer payment in your pocket</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-top pt-4">
                    {{-- Payment Submission Form --}}
                    <form action="{{ route('seller.products.payment.process') }}" method="POST" id="listingPaymentForm">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-700 text-dark small mb-2">Select Payment Method</label>
                            
                            {{-- Gateway Option: Dummy / Instant --}}
                            <div class="form-check p-3 rounded-3 border mb-2 d-flex align-items-center justify-content-between"
                                 style="background:#F8FAFC; border-color:#E2E8F0 !important; cursor:pointer;"
                                 onclick="document.getElementById('gatewayDummy').checked = true;">
                                <div class="d-flex align-items-center gap-3">
                                    <input class="form-check-input ms-0 mt-0" type="radio" name="gateway" id="gatewayDummy" value="dummy" checked>
                                    <label class="form-check-label fw-600 text-dark mb-0 ms-2" for="gatewayDummy" style="cursor:pointer">
                                        <span>Instant Checkout (Direct Activation / Dummy Gateway)</span>
                                        <div class="text-muted fw-normal" style="font-size:0.78rem">Instant verification for immediate product creation</div>
                                    </label>
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success fw-700 px-2 py-1" style="font-size:0.75rem">INSTANT</span>
                            </div>

                            {{-- Gateway Option: Razorpay (Simulated/Ready) --}}
                            <div class="form-check p-3 rounded-3 border d-flex align-items-center justify-content-between opacity-75"
                                 style="background:#FAFAFA; border-color:#E2E8F0 !important; cursor:pointer;"
                                 onclick="document.getElementById('gatewayRazorpay').checked = true;">
                                <div class="d-flex align-items-center gap-3">
                                    <input class="form-check-input ms-0 mt-0" type="radio" name="gateway" id="gatewayRazorpay" value="razorpay">
                                    <label class="form-check-label fw-600 text-dark mb-0 ms-2" for="gatewayRazorpay" style="cursor:pointer">
                                        <span>UPI / Cards / Netbanking (Razorpay Gateway)</span>
                                        <div class="text-muted fw-normal" style="font-size:0.78rem">Pay securely via Google Pay, PhonePe, Paytm, Debit/Credit Card</div>
                                    </label>
                                </div>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary fw-700 px-2 py-1" style="font-size:0.75rem">GATEWAY</span>
                            </div>
                        </div>

                        {{-- Summary & CTA Button --}}
                        <div class="d-flex align-items-center justify-content-between py-3 border-top mb-4">
                            <span class="text-muted fw-600">Total Payable:</span>
                            <span class="fs-4 fw-900 text-primary-bm">₹{{ number_format($listingPrice, 2) }}</span>
                        </div>

                        <button type="submit" class="btn btn-primary-bm w-100 py-3 rounded-3 fs-6 fw-700 shadow-sm" id="payBtn">
                            <i class="bi bi-shield-lock-fill me-2"></i>Pay ₹{{ number_format($listingPrice, 0) }} & Create Product Listing
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Notice / Policy --}}
        <div class="text-center text-muted small px-3">
            <i class="bi bi-shield-check text-success me-1"></i>
            No recurring subscription charges. You are only charged ₹{{ number_format($listingPrice, 0) }} per product listed.
            Each listing includes 2 free updates.
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('listingPaymentForm').addEventListener('submit', function(e) {
    var btn = document.getElementById('payBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing Payment...';
});
</script>
@endpush
