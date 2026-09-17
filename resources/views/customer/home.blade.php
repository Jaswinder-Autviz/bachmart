@extends('layouts.app')

@section('title', 'BachatMart - Turn Surplus Stock Into Great Deals')
@section('meta_description', 'Discover heavily discounted products and Surplus Stock clearance deals from local shops near you.')

@section('content')

{{-- ── 1. HERO SECTION ── --}}
<section class="hero-marketplace">
    <div class="container">
        <div class="row align-items-center g-4 g-lg-5">
            <div class="col-lg-7">
                <div class="hero-badge-pill">
                    <span style="width:8px;height:8px;border-radius:50%;background:var(--bm-primary);display:inline-block"></span>
                    <span>LOCAL CLEARANCE DEALS MARKETPLACE</span>
                </div>
                <h1 class="hero-title">
                    Turn Surplus Stock Into <span class="text-primary-bm">Great Deals</span>
                </h1>
                <p class="hero-subtitle">
                    Discover heavily discounted products from local shops near you. Browse verified clearance items, contact shopkeepers directly, and buy in-store.
                </p>

                <div class="d-flex flex-wrap gap-3 mt-4 pt-1">
                    <a href="{{ route('deals') }}" class="btn btn-primary-bm px-4 py-3" style="font-size:1.05rem">
                        <i class="bi bi-lightning-charge-fill me-1"></i>Explore Deals
                    </a>
                    <a href="{{ route('register.seller') }}" class="btn btn-outline-bm px-4 py-3" style="font-size:1.05rem">
                        <i class="bi bi-tag-fill me-1"></i>Sell Your Surplus Stock
                    </a>
                </div>

                {{-- Marketplace Stats --}}
                <div class="row g-3 mt-4 pt-2">
                    <div class="col-4">
                        <div class="fw-800 fs-3 text-dark" style="line-height:1">
                            {{ number_format(\App\Models\Product::approved()->count()) }}+
                        </div>
                        <div class="small text-muted fw-600 mt-1">Live Deals</div>
                    </div>
                    <div class="col-4 border-start ps-3">
                        <div class="fw-800 fs-3 text-dark" style="line-height:1">
                            {{ \App\Models\Shop::active()->count() }}+
                        </div>
                        <div class="small text-muted fw-600 mt-1">Local Shops</div>
                    </div>
                    <div class="col-4 border-start ps-3">
                        <div class="fw-800 fs-3 text-primary-bm" style="line-height:1">
                            Up to 70%
                        </div>
                        <div class="small text-muted fw-600 mt-1">Direct Savings</div>
                    </div>
                </div>
            </div>

            {{-- Hero Visual Deal Showcase --}}
            <div class="col-lg-5">
                <div class="position-relative mx-auto" style="max-width: 420px;">
                    <div class="card border rounded-4 overflow-hidden p-3 bg-white" style="border-color:#E2E8F0 !important">
                        <div class="position-relative rounded-3 overflow-hidden mb-3" style="height:220px;background:#F8FAFC">
                            <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&auto=format&fit=crop&q=80"
                                 alt="Clearance Deal Preview" class="w-100 h-100 object-fit-cover">
                            <span class="badge-discount-tag fs-6 px-3 py-1">
                                50% OFF
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted fw-600 small">SPORTING GOODS</span>
                            <span class="text-success small fw-600"><i class="bi bi-geo-alt me-1"></i>In-Store Deal</span>
                        </div>
                        <h6 class="fw-700 fs-5 mb-2 text-dark">Original Athletic Shoes (Unsold Lot)</h6>
                        <div class="d-flex align-items-baseline gap-2 mb-3">
                            <span class="fs-4 fw-800 text-dark">₹1,499</span>
                            <span class="fs-6 text-muted text-decoration-line-through">₹2,999</span>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('deals') }}" class="btn btn-primary-bm flex-grow-1" style="border-radius:8px">
                                Explore Deals <i class="bi bi-arrow-right-short ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── 2. BROWSE CATEGORIES ── --}}
<section class="py-5" style="background:#ffffff">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">Shop by <span>Category</span></h2>
                <p class="section-subtitle">Find clearance discounts across popular retail categories</p>
            </div>
            <a href="{{ route('deals') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                All Categories &rarr;
            </a>
        </div>

        <div class="row g-3">
            @foreach($categories as $cat)
            <div class="col-4 col-md-3 col-lg-2">
                <a href="{{ route('category.show', $cat->slug) }}" class="category-card">
                    <div class="cat-icon">{{ $cat->icon ?? '•' }}</div>
                    <div class="cat-name">{{ $cat->name }}</div>
                    <div class="cat-count">{{ $cat->approved_products_count }} deals</div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── 3. FEATURED DEALS ── --}}
@if($featuredProducts->count())
<section class="py-5" style="background:#F8FAFC">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">Featured <span>Clearance Deals</span></h2>
                <p class="section-subtitle">Hand-picked surplus stock from top local retailers</p>
            </div>
            <a href="{{ route('deals') }}?featured=1" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
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
<section class="py-5" style="background:#ffffff">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">Top <span>Discounts</span></h2>
                <p class="section-subtitle">Highest price reductions available right now</p>
            </div>
            <a href="{{ route('deals') }}?sort=discount" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
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
<section class="py-5" style="background:#F8FAFC">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">Deals in <span>{{ $selectedCity ?? 'Your Area' }}</span></h2>
                <p class="section-subtitle">Local stores in {{ $selectedCity ?? 'your city' }} with active clearance stock</p>
            </div>

            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle btn-sm rounded-pill px-3" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-geo-alt me-1 text-muted"></i>{{ $selectedCity ?? 'Select City' }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border rounded-3">
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
<section class="py-5" style="background:#ffffff">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">Recently <span>Added Deals</span></h2>
                <p class="section-subtitle">Newly listed surplus inventory from local sellers</p>
            </div>
            <a href="{{ route('deals') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
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
            <a href="{{ route('deals') }}" class="btn btn-dark px-5 py-3 rounded-pill fw-600">
                Explore All Clearance Deals
            </a>
        </div>
    </div>
</section>

{{-- ── 7. HOW IT WORKS (Minimal, Clean 3-Step) ── --}}
<section class="py-5" style="background:#F8FAFC" id="how-it-works">
    <div class="container">
        <div class="text-center mb-5" style="max-width: 600px; margin: 0 auto;">
            <h2 class="section-title">How <span>BachatMart</span> Works</h2>
            <p class="text-muted small mt-2">
                A simple bridge between local shopkeepers clearing unsold inventory and local bargain shoppers.
            </p>
        </div>

        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="p-4 bg-white rounded-4 border h-100">
                    <div class="fw-800 fs-4 text-primary-bm mb-2">01</div>
                    <h6 class="fw-700 text-dark mb-2">Shopkeeper Lists Stock</h6>
                    <p class="text-muted small mb-0">Local retailers list dead or excess inventory at steep clearance prices in under 2 minutes.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 bg-white rounded-4 border h-100">
                    <div class="fw-800 fs-4 text-primary-bm mb-2">02</div>
                    <h6 class="fw-700 text-dark mb-2">Shopper Discovers Deal</h6>
                    <p class="text-muted small mb-0">Local buyers browse verified deals nearby, check pricing, and message the store directly on WhatsApp.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 bg-white rounded-4 border h-100">
                    <div class="fw-800 fs-4 text-primary-bm mb-2">03</div>
                    <h6 class="fw-700 text-dark mb-2">Inspect & Buy In-Store</h6>
                    <p class="text-muted small mb-0">The customer visits the neighborhood shop, checks the product quality in person, and pays directly.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── 8. SELLER CALL-TO-ACTION BANNER ── --}}
<section class="py-5 text-white" style="background:#0F172A">
    <div class="container">
        <div class="row align-items-center justify-content-between g-4">
            <div class="col-lg-7">
                <h3 class="fw-800 mb-2">Have Unsold Stock in Your Shop?</h3>
                <p class="text-secondary mb-0" style="max-width: 520px;">
                    Join hundreds of local retailers clearing excess inventory. Zero commission, no shipping delays, direct store footfall.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('register.seller') }}" class="btn btn-light px-4 py-3 rounded-pill fw-700 text-dark">
                    Register Shop for Free &rarr;
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
