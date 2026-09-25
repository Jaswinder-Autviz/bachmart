@extends('layouts.app')

@section('title', 'BachatMart - Local Surplus Stock & Clearance Deals')
@section('meta_description', 'Discover verified clearance deals and surplus stock discounts from local shops near you. Contact shopkeepers directly and purchase in-store.')

@section('content')

{{-- ── 1. HERO SECTION ── --}}
<section class="py-5" style="background: radial-gradient(120% 100% at 50% 0%, #FFF5F0 0%, #FFFFFF 100%); border-bottom: 1px solid var(--bm-border-light);">
    <div class="container py-2 py-lg-4">
        <div class="row align-items-center g-4 g-lg-5">
            <div class="col-lg-7">
                {{-- Clean Badge Tag --}}
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill"
                     style="background: #FFF0EB; border: 1px solid #FFD3C4; font-size: 0.78rem; font-weight: 700; color: var(--bm-primary); letter-spacing: 0.04em;">
                    <i class="bi bi-patch-check-fill text-primary-bm"></i> LOCAL CLEARANCE & SURPLUS STOCK MARKETPLACE
                </div>

                {{-- Hero Title --}}
                <h1 class="fw-900 text-dark mb-3" style="font-size: clamp(2.2rem, 4vw, 3.4rem); line-height: 1.18; letter-spacing: -0.035em;">
                    Turn Surplus Stock Into <span class="text-primary-bm">Great Deals</span>
                </h1>

                <p class="text-muted mb-4 fs-6" style="max-width: 560px; line-height: 1.7; font-weight: 400;">
                    Discover heavily discounted products directly from verified retail shops in your neighborhood. Chat with store owners on WhatsApp, visit the shop, and purchase in person.
                </p>

                {{-- Action Buttons --}}
                <div class="d-flex flex-wrap gap-3 mb-4 pb-2">
                    <a href="{{ route('deals') }}" class="btn btn-primary-bm px-4 py-3 fs-6">
                        <i class="bi bi-lightning-charge-fill me-1"></i>Explore Live Deals
                    </a>
                    <a href="{{ route('register.seller') }}" class="btn btn-outline-bm px-4 py-3 fs-6">
                        <i class="bi bi-shop-window me-1"></i>Sell Your Surplus Stock
                    </a>
                </div>

                {{-- Clean Stats Metric Pods --}}
                <div class="row g-3 pt-2">
                    <div class="col-4">
                        <div class="p-3 bg-white border rounded-4 text-center shadow-sm" style="border-color: var(--bm-border) !important;">
                            <div class="fw-900 fs-4 text-dark mb-0">
                                {{ number_format(\App\Models\Product::approved()->count()) }}+
                            </div>
                            <div class="text-muted small fw-600" style="font-size: 0.78rem;">Live Deals</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-white border rounded-4 text-center shadow-sm" style="border-color: var(--bm-border) !important;">
                            <div class="fw-900 fs-4 text-dark mb-0">
                                {{ \App\Models\Shop::active()->count() }}+
                            </div>
                            <div class="text-muted small fw-600" style="font-size: 0.78rem;">Verified Stores</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-white border rounded-4 text-center shadow-sm" style="border-color: var(--bm-border) !important;">
                            <div class="fw-900 fs-4 text-primary-bm mb-0">
                                Up to 70%
                            </div>
                            <div class="text-muted small fw-600" style="font-size: 0.78rem;">Direct Savings</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Hero Deal Showcase Card --}}
            <div class="col-lg-5">
                <div class="card border-0 p-3 bg-white rounded-4 shadow-lg" style="border: 1px solid var(--bm-border) !important;">
                    <div class="position-relative overflow-hidden mb-3 rounded-4" style="height: 240px; background: #F8FAFC;">
                        <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&auto=format&fit=crop&q=80"
                             alt="Clearance Deal Preview" style="width:100%; height:100%; object-fit:cover;">
                        <span class="product-card-badge product-card-badge--discount">
                            🔥 50% OFF
                        </span>
                        <span class="product-card-badge product-card-badge--featured">
                            ⭐ Top Pick
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small fw-700 text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">SPORTING GOODS</span>
                        <span class="text-success small fw-700"><i class="bi bi-geo-alt-fill me-1"></i>In-Store Purchase</span>
                    </div>

                    <h5 class="fw-800 text-dark fs-6 mb-2">Original Athletic Shoes (Unsold Stock Lot)</h5>

                    <div class="d-flex align-items-baseline gap-2 mb-3">
                        <span class="fs-4 fw-900 text-dark">₹1,499</span>
                        <span class="text-muted text-decoration-line-through small">₹2,999</span>
                        <span class="badge rounded-pill bg-success-subtle text-success fw-700 ms-auto px-2 py-1" style="font-size: 0.75rem;">Save ₹1,500</span>
                    </div>

                    <a href="{{ route('deals') }}" class="btn btn-primary-bm w-100 py-2 fw-700">
                        Explore Live Clearance Deals <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── 2. BROWSE CATEGORIES ── --}}
<section class="py-5" style="background: #FFFFFF; border-bottom: 1px solid var(--bm-border-light);">
    <div class="container">
        <div class="section-header">
            <div>
                <span class="section-tag"><i class="bi bi-grid-fill"></i> Departments</span>
                <h2 class="section-title">Shop by <span>Category</span></h2>
                <p class="section-subtitle">Find clearance discounts across popular retail categories</p>
            </div>
            <a href="{{ route('deals') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-600">
                All Categories &rarr;
            </a>
        </div>

        <div class="row g-3 g-md-4">
            @foreach($categories as $cat)
            <div class="col-6 col-md-3 col-lg-2">
                <a href="{{ route('category.show', $cat->slug) }}" class="category-card">
                    <div class="cat-icon">
                        {{ $cat->icon ?? '🏷️' }}
                    </div>
                    <div class="cat-name">{{ $cat->name }}</div>
                    <div class="cat-count">{{ $cat->approved_products_count }} deals</div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── 3. FEATURED CLEARANCE DEALS ── --}}
@if($featuredProducts->count())
<section class="py-5" style="background: #F8FAFC; border-bottom: 1px solid var(--bm-border-light);">
    <div class="container">
        <div class="section-header">
            <div>
                <span class="section-tag"><i class="bi bi-stars"></i> Curated Picks</span>
                <h2 class="section-title">Featured <span>Clearance Deals</span></h2>
                <p class="section-subtitle">Hand-picked surplus stock from top local retailers</p>
            </div>
            <a href="{{ route('deals') }}?featured=1" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-600">
                View All Featured &rarr;
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
<section class="py-5" style="background: #FFFFFF; border-bottom: 1px solid var(--bm-border-light);">
    <div class="container">
        <div class="section-header">
            <div>
                <span class="section-tag"><i class="bi bi-fire"></i> Mega Markdowns</span>
                <h2 class="section-title">Top <span>Discounts</span></h2>
                <p class="section-subtitle">Highest price reductions available right now</p>
            </div>
            <a href="{{ route('deals') }}?sort=discount" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-600">
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
<section class="py-5" style="background: #F8FAFC; border-bottom: 1px solid var(--bm-border-light);">
    <div class="container">
        <div class="section-header">
            <div>
                <span class="section-tag"><i class="bi bi-geo-alt-fill"></i> Hyper-Local</span>
                <h2 class="section-title">Deals in <span>{{ $selectedCity ?? 'Your Area' }}</span></h2>
                <p class="section-subtitle">Local stores in {{ $selectedCity ?? 'your city' }} with active clearance stock</p>
            </div>

            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle btn-sm rounded-pill px-3 fw-600" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-geo-alt me-1 text-primary-bm"></i>{{ $selectedCity ?? 'Select City' }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-2">
                    @foreach($availableCities as $city)
                    <li>
                        <a class="dropdown-item py-2 px-3 rounded-3 {{ $selectedCity === $city ? 'active fw-bold' : '' }}" href="{{ route('set-city', ['city' => $city]) }}">
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
<section class="py-5" style="background: #FFFFFF; border-bottom: 1px solid var(--bm-border-light);">
    <div class="container">
        <div class="section-header">
            <div>
                <span class="section-tag"><i class="bi bi-clock-history"></i> Fresh Inventory</span>
                <h2 class="section-title">Recently <span>Added Deals</span></h2>
                <p class="section-subtitle">Newly listed surplus items from verified local sellers</p>
            </div>
            <a href="{{ route('deals') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-600">
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
            <a href="{{ route('deals') }}" class="btn btn-primary-bm px-5 py-3 fs-6 fw-800">
                <i class="bi bi-grid-3x3-gap-fill me-2"></i>Explore All Clearance Deals
            </a>
        </div>
    </div>
</section>

{{-- ── 7. HOW IT WORKS ── --}}
<section class="py-5" style="background: #F8FAFC; border-bottom: 1px solid var(--bm-border-light);" id="how-it-works">
    <div class="container py-3">
        <div class="text-center mb-5" style="max-width: 650px; margin: 0 auto;">
            <span class="section-tag"><i class="bi bi-lightning-charge-fill"></i> Simple 3-Step Process</span>
            <h2 class="section-title mb-2">How <span>BachatMart</span> Works</h2>
            <p class="text-muted fs-6">
                A transparent, direct connection between local storekeepers clearing excess stock and bargain hunters nearby.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 p-4 bg-white rounded-4 h-100 shadow-sm" style="border: 1px solid var(--bm-border) !important;">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center fw-900 text-white rounded-3"
                             style="width: 42px; height: 42px; background: var(--bm-primary-gradient); font-size: 1rem;">
                            01
                        </div>
                        <i class="bi bi-shop-window text-primary-bm fs-3"></i>
                    </div>
                    <h5 class="fw-800 text-dark fs-6 mb-2">Shopkeeper Lists Stock</h5>
                    <p class="text-muted small mb-0" style="line-height: 1.6;">Local retailers list surplus or excess inventory at steep clearance prices for just ₹12 per product.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 p-4 bg-white rounded-4 h-100 shadow-sm" style="border: 1px solid var(--bm-border) !important;">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center fw-900 text-white rounded-3"
                             style="width: 42px; height: 42px; background: var(--bm-primary-gradient); font-size: 1rem;">
                            02
                        </div>
                        <i class="bi bi-chat-left-dots-fill text-primary-bm fs-3"></i>
                    </div>
                    <h5 class="fw-800 text-dark fs-6 mb-2">Shopper Discovers Deal</h5>
                    <p class="text-muted small mb-0" style="line-height: 1.6;">Local buyers browse verified deals nearby, compare prices, and connect directly with the shop via WhatsApp or Call.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 p-4 bg-white rounded-4 h-100 shadow-sm" style="border: 1px solid var(--bm-border) !important;">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center fw-900 text-white rounded-3"
                             style="width: 42px; height: 42px; background: var(--bm-primary-gradient); font-size: 1rem;">
                            03
                        </div>
                        <i class="bi bi-bag-check-fill text-primary-bm fs-3"></i>
                    </div>
                    <h5 class="fw-800 text-dark fs-6 mb-2">Inspect & Buy In-Store</h5>
                    <p class="text-muted small mb-0" style="line-height: 1.6;">The buyer visits the neighborhood shop, verifies the product in person, and pays directly at the store counter.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── 8. SELLER CALL-TO-ACTION BANNER ── --}}
<section class="py-5" style="background: #FFFFFF;">
    <div class="container">
        <div class="p-4 p-md-5 text-white rounded-4 shadow-lg position-relative overflow-hidden"
             style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); border: 1px solid #334155;">
            <div class="row align-items-center justify-content-between g-4 position-relative" style="z-index: 2;">
                <div class="col-lg-7">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill fw-700"
                         style="background: rgba(255, 87, 34, 0.2); color: #FF8A65; font-size: 0.82rem; border: 1px solid rgba(255, 87, 34, 0.35);">
                        <i class="bi bi-tag-fill"></i> Just ₹12 Per Product Listing &bull; 0% Commission
                    </div>
                    <h2 class="fw-900 text-white mb-2" style="font-size: clamp(1.8rem, 2.8vw, 2.5rem); letter-spacing: -0.03em;">
                        Have Unsold Surplus Stock in Your Shop?
                    </h2>
                    <p class="text-light text-opacity-75 mb-0 fs-6" style="max-width: 540px; line-height: 1.7;">
                        Join hundreds of local retailers liquidating excess stock into cash. List any surplus item for just ₹12. Get direct customer WhatsApp chats and in-store footfall with zero middleman fees.
                    </p>
                </div>
                <div class="col-lg-5 text-lg-end">
                    <a href="{{ route('register.seller') }}" class="btn btn-primary-bm px-4 py-3 fw-800 fs-6">
                        <i class="bi bi-shop-window me-2"></i>Register Shop & Start Listing &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
