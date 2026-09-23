@extends('layouts.admin')
@section('title', 'System Settings')
@section('page-title', 'Platform & System Settings')

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">
        {{-- General Settings --}}
        <div class="col-lg-6">
            <div class="card-admin p-4 h-100">
                <h6 class="fw-700 text-dark mb-3">
                    <i class="bi bi-gear-fill me-2 text-primary"></i>General Information
                </h6>

                <div class="mb-3">
                    <label class="form-label fw-600 small">Site Name</label>
                    <input type="text" name="site_name" class="form-control form-control-sm"
                           value="{{ $settings['site_name']->value ?? 'BachatMart' }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-600 small">Tagline</label>
                    <input type="text" name="site_tagline" class="form-control form-control-sm"
                           value="{{ $settings['site_tagline']->value ?? 'Turn Surplus Stock Into Great Deals' }}">
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label fw-600 small">Support Phone</label>
                        <input type="text" name="site_phone" class="form-control form-control-sm"
                               value="{{ $settings['site_phone']->value ?? '+91 9876543210' }}">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label fw-600 small">Support Email</label>
                        <input type="email" name="site_email" class="form-control form-control-sm"
                               value="{{ $settings['site_email']->value ?? 'support@bachatmart.com' }}">
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label fw-600 small">Currency Code</label>
                        <input type="text" name="currency" class="form-control form-control-sm"
                               value="{{ $settings['currency']->value ?? 'INR' }}">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label fw-600 small">Currency Symbol</label>
                        <input type="text" name="currency_symbol" class="form-control form-control-sm"
                               value="{{ $settings['currency_symbol']->value ?? '₹' }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-600 small">Pay-Per-Product Listing Fee (₹)</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">₹</span>
                        <input type="number" name="product_listing_price" class="form-control form-control-sm"
                               value="{{ $settings['product_listing_price']->value ?? '12' }}" min="0" step="1">
                    </div>
                    <div class="form-text small">Amount charged to sellers for each single product listing published.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-600 small">Office Address</label>
                    <input type="text" name="contact_address" class="form-control form-control-sm"
                           value="{{ $settings['contact_address']->value ?? '' }}">
                </div>

                <div class="mb-0">
                    <label class="form-label fw-600 small">City & State</label>
                    <input type="text" name="contact_city" class="form-control form-control-sm"
                           value="{{ $settings['contact_city']->value ?? '' }}">
                </div>
            </div>
        </div>

        {{-- Featured Pricing & SEO --}}
        <div class="col-lg-6">
            {{-- Featured Promotion Pricing --}}
            <div class="card-admin p-4 mb-4">
                <h6 class="fw-700 text-dark mb-3">
                    <i class="bi bi-star-fill me-2" style="color:#FF5722"></i>Featured Promotion Rates (₹)
                </h6>

                <div class="row g-2">
                    <div class="col-sm-4">
                        <label class="form-label fw-600 small">3 Days</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">₹</span>
                            <input type="number" name="featured_price_3days" class="form-control"
                                   value="{{ $settings['featured_price_3days']->value ?? '49' }}">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label fw-600 small">7 Days</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">₹</span>
                            <input type="number" name="featured_price_7days" class="form-control"
                                   value="{{ $settings['featured_price_7days']->value ?? '99' }}">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label fw-600 small">15 Days</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">₹</span>
                            <input type="number" name="featured_price_15days" class="form-control"
                                   value="{{ $settings['featured_price_15days']->value ?? '199' }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- SEO & Social --}}
            <div class="card-admin p-4 mb-4">
                <h6 class="fw-700 text-dark mb-3">
                    <i class="bi bi-search me-2 text-info"></i>SEO & Social Links
                </h6>

                <div class="mb-3">
                    <label class="form-label fw-600 small">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control form-control-sm"
                           value="{{ $settings['meta_title']->value ?? 'BachatMart - Local Clearance Deals' }}">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-600 small">Meta Description</label>
                    <textarea name="meta_description" class="form-control form-control-sm" rows="2">{{ $settings['meta_description']->value ?? '' }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-600 small">Meta Keywords</label>
                    <input type="text" name="meta_keywords" class="form-control form-control-sm"
                           value="{{ $settings['meta_keywords']->value ?? '' }}">
                </div>

                <div class="row g-2">
                    <div class="col-sm-4">
                        <label class="form-label fw-600 small"><i class="bi bi-facebook me-1 text-primary"></i>Facebook</label>
                        <input type="url" name="facebook_url" class="form-control form-control-sm"
                               value="{{ $settings['facebook_url']->value ?? '' }}">
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label fw-600 small"><i class="bi bi-instagram me-1 text-danger"></i>Instagram</label>
                        <input type="url" name="instagram_url" class="form-control form-control-sm"
                               value="{{ $settings['instagram_url']->value ?? '' }}">
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label fw-600 small"><i class="bi bi-twitter-x me-1"></i>Twitter</label>
                        <input type="url" name="twitter_url" class="form-control form-control-sm"
                               value="{{ $settings['twitter_url']->value ?? '' }}">
                    </div>
                </div>
            </div>

            {{-- Payment Gateway Integration --}}
            <div class="card-admin p-4">
                <h6 class="fw-700 text-dark mb-3">
                    <i class="bi bi-credit-card-fill me-2 text-success"></i>Payment Gateway (Razorpay)
                </h6>

                <div class="mb-3">
                    <label class="form-label fw-600 small">Razorpay Key ID</label>
                    <input type="text" name="razorpay_key_id" class="form-control form-control-sm"
                           value="{{ $settings['razorpay_key_id']->value ?? '' }}" placeholder="rzp_test_...">
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-4">
        <button type="submit" class="btn px-5 py-2 fw-700" style="background:#5a67d8;color:#fff;border:none;border-radius:10px;">
            <i class="bi bi-check2-circle me-1"></i>Save All Settings
        </button>
    </div>
</form>
@endsection
