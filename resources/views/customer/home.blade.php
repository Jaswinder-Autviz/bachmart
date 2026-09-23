@extends('layouts.app')

@section('title', 'BachatMart - Turn Surplus Stock Into Great Deals')
@section('meta_description', 'Discover heavily discounted products and Surplus Stock clearance deals from local shops near you.')

@section('content')

{{-- ── 1. HERO SECTION (Clean, Simple & Sober) ── --}}
<section class="py-5" style="background:#FFFFFF; border-bottom: 1px solid #E2E8F0;">
    <div class="container">
        <div class="row align-items-center g-4 g-lg-5">
            <div class="col-lg-7">
                {{-- Simple Tag Badge --}}
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 text-primary-bm border"
                     style="background:#FFF9F6; border-color:#FFD3C4 !important; border-radius:4px; font-size:0.75rem; font-weight:700; letter-spacing:0.5px;">
                    <i class="bi bi-patch-check-fill"></i> LOCAL CLEARANCE DEALS MARKETPLACE
                </div>

                {{-- Hero Title --}}
                <h1 class="fw-800 text-dark mb-3" style="font-size: clamp(2rem, 3.5vw, 2.8rem); line-height: 1.2; letter-spacing: -0.03em;">
                    Turn Surplus Stock Into <span class="text-primary-bm">Great Deals</span>
                </h1>

                <p class="text-muted mb-4 fs-6" style="max-width: 540px; line-height: 1.6;">
                    Discover heavily discounted products from local shops near you. Browse verified clearance items, contact shopkeepers directly, and buy in-store.
                </p>

                {{-- Action Buttons --}}
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('deals') }}" class="btn btn-primary-bm px-4 py-2 fs-6" style="border-radius:4px;">
                        <i class="bi bi-lightning-charge-fill me-1"></i>Explore Deals
                    </a>
                    <a href="{{ route('register.seller') }}" class="btn btn-outline-secondary px-4 py-2 fs-6" style="border-radius:4px;">
                        <i class="bi bi-tag-fill me-1 text-primary-bm"></i>Sell Your Surplus Stock
                    </a>
                </div>

                {{-- Clean Stats Pods --}}
                <div class="row g-3 mt-3 pt-2">
                    <div class="col-4">
                        <div class="p-3 bg-white border text-center" style="border-color:#E2E8F0 !important; border-radius:4px;">
                            <div class="fw-800 fs-4 text-dark mb-0">
                                {{ number_format(\App\Models\Product::approved()->count()) }}+
                            </div>
                            <div class="text-muted small fw-600">Live Deals</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-white border text-center" style="border-color:#E2E8F0 !important; border-radius:4px;">
                            <div class="fw-800 fs-4 text-dark mb-0">
                                {{ \App\Models\Shop::active()->count() }}+
                            </div>
                            <div class="text-muted small fw-600">Local Shops</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-white border text-center" style="border-color:#E2E8F0 !important; border-radius:4px;">
                            <div class="fw-800 fs-4 text-primary-bm mb-0">
                                Up to 70%
                            </div>
                            <div class="text-muted small fw-600">Direct Savings</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Hero Deal Showcase Card (Clean, Sharp, No Tilt/Shadows) --}}
            <div class="col-lg-5">
                <div class="card border p-3 bg-white" style="border-color:#E2E8F0 !important; border-radius:6px; box-shadow:none;">
                    <div class="position-relative overflow-hidden mb-3" style="height:230px; background:#F8FAFC; border-radius:4px;">
                        <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&auto=format&fit=crop&q=80"
                             alt="Clearance Deal Preview" style="width:100%; height:100%; object-fit:cover; transform:none !important; transition:none !important;">
                        <span class="badge bg-danger position-absolute top-0 start-0 m-2 px-2 py-1 fw-700" style="border-radius:4px;">
                            🔥 50% OFF
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small fw-700 text-uppercase" style="letter-spacing:0.5px">SPORTING GOODS</span>
                        <span class="text-success small fw-700"><i class="bi bi-geo-alt-fill me-1"></i>Direct In-Store</span>
                    </div>

                    <h5 class="fw-700 text-dark fs-6 mb-2">Original Athletic Shoes (Unsold Lot)</h5>

                    <div class="d-flex align-items-baseline gap-2 mb-3">
                        <span class="fs-5 fw-800 text-dark">₹1,499</span>
                        <span class="text-muted text-decoration-line-through small">₹2,999</span>
                        <span class="badge bg-success-subtle text-success fw-700 ms-auto" style="border-radius:4px;">Save ₹1,500</span>
                    </div>

                    <a href="{{ route('deals') }}" class="btn btn-primary-bm w-100 py-2 fw-600" style="font-size:0.92rem; border-radius:4px;">
                        Explore Live Deals <i class="bi bi-arrow-right-short ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── 2. BROWSE CATEGORIES (Clean & Flat) ── --}}
<section class="py-5" style="background:#FAFAFB; border-bottom:1px solid #E2E8F0;">
    <div class="container">
        <div class="section-header mb-4">
            <div>
                <span class="badge bg-white text-secondary border px-2 py-1 mb-1" style="border-radius:4px;"><i class="bi bi-grid-fill me-1"></i> Categories</span>
                <h2 class="section-title mb-0">Shop by <span class="text-primary-bm">Category</span></h2>
                <p class="section-subtitle">Find clearance discounts across popular retail categories</p>
            </div>
            <a href="{{ route('deals') }}" class="btn btn-outline-secondary btn-sm px-3" style="border-radius:4px;">
                All Categories &rarr;
            </a>
        </div>

        <div class="row g-3 g-md-3">
            @foreach($categories as $cat)
            <div class="col-4 col-md-3 col-lg-2">
                <a href="{{ route('category.show', $cat->slug) }}" class="card border p-3 text-center text-decoration-none h-100 bg-white" style="border-color:#E2E8F0 !important; border-radius:6px; box-shadow:none;">
                    <div class="d-inline-flex align-items-center justify-content-center mx-auto mb-2"
                         style="width:48px; height:48px; background:#FFF3E0; color:#FF5722; font-size:1.5rem; border-radius:4px;">
                        {{ $cat->icon ?? '•' }}
                    </div>
                    <div class="fw-700 text-dark text-truncate" style="font-size:0.88rem;">{{ $cat->name }}</div>
                    <div class="text-muted small" style="font-size:0.75rem;">{{ $cat->approved_products_count }} deals</div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── 3. FEATURED CLEARANCE DEALS ── --}}
@if($featuredProducts->count())
<section class="py-5" style="background:#FFFFFF; border-bottom:1px solid #E2E8F0;">
    <div class="container">
        <div class="section-header mb-4">
            <div>
                <span class="badge bg-light text-warning-emphasis border px-2 py-1 mb-1" style="border-radius:4px;"><i class="bi bi-stars me-1 text-warning"></i> Curated</span>
                <h2 class="section-title mb-0">Featured <span class="text-primary-bm">Clearance Deals</span></h2>
                <p class="section-subtitle">Hand-picked surplus stock from top local retailers</p>
            </div>
            <a href="{{ route('deals') }}?featured=1" class="btn btn-outline-secondary btn-sm px-3" style="border-radius:4px;">
                View All &rarr;
            </a>
        </div>

        <div class="row g-3 g-md-4 mobile-2-col">
            @foreach($featuredProducts as $product)
            <div class="col-6 col-md-4 col-lg-3">
                @include('components.product-card', ['product' => $product])
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── 4. BIGGEST DISCOUNTS ── --}}
@if($topDeals->count())
<section class="py-5" style="background:#FAFAFB; border-bottom:1px solid #E2E8F0;">
    <div class="container">
        <div class="section-header mb-4">
            <div>
                <span class="badge bg-white text-danger border px-2 py-1 mb-1" style="border-radius:4px;"><i class="bi bi-fire me-1"></i> Mega Discounts</span>
                <h2 class="section-title mb-0">Top <span class="text-primary-bm">Discounts</span></h2>
                <p class="section-subtitle">Highest price reductions available right now</p>
            </div>
            <a href="{{ route('deals') }}?sort=discount" class="btn btn-outline-secondary btn-sm px-3" style="border-radius:4px;">
                View All &rarr;
            </a>
        </div>

        <div class="row g-3 g-md-4 mobile-2-col">
            @foreach($topDeals as $product)
            <div class="col-6 col-md-4 col-lg-3">
                @include('components.product-card', ['product' => $product])
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── 5. DEALS NEAR YOU ── --}}
@if($dealsNearYou->count())
<section class="py-5" style="background:#FFFFFF; border-bottom:1px solid #E2E8F0;">
    <div class="container">
        <div class="section-header mb-4">
            <div>
                <span class="badge bg-light text-primary-bm border px-2 py-1 mb-1" style="border-radius:4px;"><i class="bi bi-geo-alt-fill me-1"></i> Location</span>
                <h2 class="section-title mb-0">Deals in <span class="text-primary-bm">{{ $selectedCity ?? 'Your Area' }}</span></h2>
                <p class="section-subtitle">Local stores in {{ $selectedCity ?? 'your city' }} with active clearance stock</p>
            </div>

            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle btn-sm px-3" type="button" data-bs-toggle="dropdown" style="border-radius:4px;">
                    <i class="bi bi-geo-alt me-1 text-muted"></i>{{ $selectedCity ?? 'Select City' }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end border shadow-none" style="border-color:#E2E8F0 !important; border-radius:4px;">
                    @foreach($availableCities as $city)
                    <li>
                        <a class="dropdown-item py-2 {{ $selectedCity === $city ? 'active' : '' }}" href="{{ route('set-city', ['city' => $city]) }}">
                            {{ $city }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="row g-3 g-md-4 mobile-2-col">
            @foreach($dealsNearYou as $product)
            <div class="col-6 col-md-4 col-lg-3">
                @include('components.product-card', ['product' => $product])
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── 6. LATEST ARRIVALS ── --}}
<section class="py-5" style="background:#FAFAFB; border-bottom:1px solid #E2E8F0;">
    <div class="container">
        <div class="section-header mb-4">
            <div>
                <span class="badge bg-white text-secondary border px-2 py-1 mb-1" style="border-radius:4px;"><i class="bi bi-clock-history me-1"></i> Fresh Surplus</span>
                <h2 class="section-title mb-0">Recently <span class="text-primary-bm">Added Deals</span></h2>
                <p class="section-subtitle">Newly listed surplus inventory from local sellers</p>
            </div>
            <a href="{{ route('deals') }}" class="btn btn-outline-secondary btn-sm px-3" style="border-radius:4px;">
                Browse All &rarr;
            </a>
        </div>

        <div class="row g-3 g-md-4 mobile-2-col">
            @foreach($latestProducts as $product)
            <div class="col-6 col-md-4 col-lg-3">
                @include('components.product-card', ['product' => $product])
            </div>
            @endforeach
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('deals') }}" class="btn btn-primary-bm px-4 py-2 fs-6 fw-700" style="border-radius:4px;">
                <i class="bi bi-grid-3x3-gap-fill me-2"></i>Explore All Clearance Deals
            </a>
        </div>
    </div>
</section>

{{-- ── 7. HOW IT WORKS (Simple & Clean) ── --}}
<section class="py-5" style="background:#FFFFFF; border-bottom:1px solid #E2E8F0;" id="how-it-works">
    <div class="container">
        <div class="text-center mb-5" style="max-width: 620px; margin: 0 auto;">
            <span class="badge bg-light text-secondary border px-2 py-1 mb-2" style="border-radius:4px;"><i class="bi bi-lightbulb-fill me-1 text-warning"></i> Simple Workflow</span>
            <h2 class="section-title mb-2">How <span class="text-primary-bm">BachatMart</span> Works</h2>
            <p class="text-muted small mb-0">
                A seamless 3-step bridge between local shopkeepers clearing unsold inventory and local bargain shoppers.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border p-4 bg-white h-100" style="border-color:#E2E8F0 !important; border-radius:6px; box-shadow:none;">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center fw-800 text-white"
                             style="width:36px; height:36px; background:#FF5722; font-size:0.95rem; border-radius:4px;">
                            01
                        </div>
                        <i class="bi bi-shop text-muted fs-3"></i>
                    </div>
                    <h5 class="fw-700 text-dark fs-6 mb-2">Shopkeeper Lists Stock</h5>
                    <p class="text-muted small mb-0" style="line-height:1.5;">Local retailers list surplus or excess inventory at steep clearance prices in under 2 minutes.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border p-4 bg-white h-100" style="border-color:#E2E8F0 !important; border-radius:6px; box-shadow:none;">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center fw-800 text-white"
                             style="width:36px; height:36px; background:#FF5722; font-size:0.95rem; border-radius:4px;">
                            02
                        </div>
                        <i class="bi bi-phone-vibrate text-muted fs-3"></i>
                    </div>
                    <h5 class="fw-700 text-dark fs-6 mb-2">Shopper Discovers Deal</h5>
                    <p class="text-muted small mb-0" style="line-height:1.5;">Local buyers browse verified deals nearby, check pricing, and message the store directly on WhatsApp.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border p-4 bg-white h-100" style="border-color:#E2E8F0 !important; border-radius:6px; box-shadow:none;">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center fw-800 text-white"
                             style="width:36px; height:36px; background:#FF5722; font-size:0.95rem; border-radius:4px;">
                            03
                        </div>
                        <i class="bi bi-bag-check-fill text-muted fs-3"></i>
                    </div>
                    <h5 class="fw-700 text-dark fs-6 mb-2">Inspect & Buy In-Store</h5>
                    <p class="text-muted small mb-0" style="line-height:1.5;">The customer visits the neighborhood shop, checks the product quality in person, and pays directly.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── 10. SELLER CALL-TO-ACTION BANNER ── --}}
<section class="py-5" style="background: #FAF8F5;">
    <div class="container">
        <div class="p-4 p-md-5 text-white" style="background: linear-gradient(135deg, #12291E 0%, #163628 100%); border: 1px solid #1E3D2C; border-radius: 20px; box-shadow: 0 14px 32px rgba(18,41,30,0.18);">
            <div class="row align-items-center justify-content-between g-4">
                <div class="col-lg-7">
                    <div class="d-inline-flex align-items-center gap-1.5 px-3 py-1 mb-3 text-warning small fw-700" style="background: rgba(255, 100, 51, 0.2); border-radius: 30px; color: #FF8A65 !important;">
                        <i class="bi bi-tag-fill"></i> Just ₹12 Per Product Listing &bull; 0% Commission
                    </div>
                    <h2 class="fw-900 text-white mb-2" style="font-size: clamp(1.6rem, 2.5vw, 2.3rem); letter-spacing: -0.02em;">
                        Have Unsold Surplus Stock in Your Shop?
                    </h2>
                    <p class="text-light text-opacity-75 mb-0" style="max-width: 520px; font-size: 0.95rem; line-height: 1.6;">
                        Join hundreds of local retailers clearing excess inventory. List any surplus item for just ₹12. Direct WhatsApp leads, zero sales commission, and instant in-store walk-ins.
                    </p>
                </div>
                <div class="col-lg-5 text-lg-end">
                    <a href="{{ route('register.seller') }}" class="se-btn-orange px-4 py-3 fw-800 fs-6">
                        <i class="bi bi-shop-window me-1"></i> Register Shop & Start Listing &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

