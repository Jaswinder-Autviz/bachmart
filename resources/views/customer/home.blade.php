@extends('layouts.app')

@section('title', 'BachatMart - Turn Surplus Stock Into Great Deals')
@section('meta_description', 'Discover heavily discounted products and Surplus Stock clearance deals from local shops near you.')

@push('styles')
<link href="{{ asset('css/home-3d.css') }}?v={{ file_exists(public_path('css/home-3d.css')) ? filemtime(public_path('css/home-3d.css')) : '1.0' }}" rel="stylesheet">
@endpush

@section('content')

{{-- ── 1. 3D HERO SECTION ── --}}
<section class="hero-marketplace hero-marketplace-3d spatial-scene-wrapper">
    {{-- Floating 3D Ambient Light Orbs --}}
    <div class="orb-3d orb-3d-1"></div>
    <div class="orb-3d orb-3d-2"></div>

    <div class="container position-relative" style="z-index: 2;">
        <div class="row align-items-center g-4 g-lg-5">
            <div class="col-lg-7">
                {{-- 3D Pill Badge --}}
                <div class="hero-badge-pill-3d">
                    <span class="hero-badge-pulse-dot"></span>
                    <span>LOCAL CLEARANCE DEALS MARKETPLACE</span>
                </div>

                {{-- 3D Hero Title --}}
                <h1 class="hero-title-3d">
                    Turn Surplus Stock Into <span class="text-gradient-3d">Great Deals</span>
                </h1>

                <p class="hero-subtitle-3d">
                    Discover heavily discounted products from local shops near you. Browse verified clearance items, contact shopkeepers directly, and buy in-store.
                </p>

                {{-- 3D Pushable Action Buttons --}}
                <div class="d-flex flex-wrap gap-3 mt-4 pt-2">
                    <a href="{{ route('deals') }}" class="btn-3d-primary">
                        <i class="bi bi-lightning-charge-fill me-1"></i>Explore Deals
                    </a>
                    <a href="{{ route('register.seller') }}" class="btn-3d-secondary">
                        <i class="bi bi-tag-fill me-1 text-primary-bm"></i>Sell Your Surplus Stock
                    </a>
                </div>

                {{-- Marketplace 3D Extruded Stats Pods --}}
                <div class="row g-3 mt-4 pt-3">
                    <div class="col-4">
                        <div class="stats-pod-3d">
                            <div class="stats-pod-number">
                                {{ number_format(\App\Models\Product::approved()->count()) }}+
                            </div>
                            <div class="stats-pod-label">Live Deals</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stats-pod-3d">
                            <div class="stats-pod-number">
                                {{ \App\Models\Shop::active()->count() }}+
                            </div>
                            <div class="stats-pod-label">Local Shops</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stats-pod-3d">
                            <div class="stats-pod-number text-primary-bm">
                                Up to 70%
                            </div>
                            <div class="stats-pod-label">Direct Savings</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3D Hero Stage (Interactive WebGL Canvas & Tilted Deal Showcase) --}}
            <div class="col-lg-5">
                <div class="hero-stage-3d-container">
                    {{-- Three.js Interactive 3D WebGL Canvas --}}
                    <div id="heroThreeCanvas"></div>

                    {{-- Floating 3D Holographic Badges --}}
                    <div class="floating-3d-tag floating-3d-tag--deal">
                        <i class="bi bi-fire text-primary-bm fs-5"></i>
                        <span>Surplus Lots Clearance</span>
                    </div>

                    <div class="floating-3d-tag floating-3d-tag--live">
                        <i class="bi bi-patch-check-fill text-success fs-5"></i>
                        <span>100% In-Store Verified</span>
                    </div>

                    {{-- 3D Interactive Tilt Showcase Card --}}
                    <div class="showcase-3d-card" data-3d-tilt data-tilt-max="11">
                        <div class="card-3d-glare"></div>

                        <div class="showcase-3d-media">
                            <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&auto=format&fit=crop&q=80"
                                 alt="Clearance Deal Preview">
                            <span class="showcase-badge-discount">
                                🔥 50% OFF
                            </span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3 mb-1">
                            <span class="text-muted fw-700 small" style="letter-spacing:0.5px">SPORTING GOODS</span>
                            <span class="text-success small fw-700"><i class="bi bi-geo-alt-fill me-1"></i>Direct In-Store</span>
                        </div>

                        <h5 class="fw-800 fs-5 mb-2 text-dark">Original Athletic Shoes (Unsold Lot)</h5>

                        <div class="d-flex align-items-baseline gap-2 mb-3">
                            <span class="fs-4 fw-800 text-dark">₹1,499</span>
                            <span class="fs-6 text-muted text-decoration-line-through">₹2,999</span>
                            <span class="badge bg-success-subtle text-success fw-700 ms-auto px-2 py-1 rounded-pill">Save ₹1,500</span>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('deals') }}" class="btn-3d-primary w-100 py-2" style="border-radius:12px; font-size:0.95rem;">
                                Explore Live Deals <i class="bi bi-arrow-right-short ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── 2. BROWSE CATEGORIES (3D TACTILE PODS) ── --}}
<section class="py-5" style="background:#FFFFFF;">
    <div class="container">
        <div class="section-header">
            <div>
                <span class="section-badge-3d"><i class="bi bi-grid-fill"></i> Categories</span>
                <h2 class="section-title">Shop by <span>Category</span></h2>
                <p class="section-subtitle">Find clearance discounts across popular retail categories</p>
            </div>
            <a href="{{ route('deals') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                All Categories &rarr;
            </a>
        </div>

        <div class="row g-3 g-md-4">
            @foreach($categories as $cat)
            <div class="col-4 col-md-3 col-lg-2">
                <a href="{{ route('category.show', $cat->slug) }}" class="category-card-3d">
                    <div class="cat-icon-3d">{{ $cat->icon ?? '•' }}</div>
                    <div class="cat-name-3d">{{ $cat->name }}</div>
                    <div class="cat-count-3d">{{ $cat->approved_products_count }} deals</div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── 3. FEATURED CLEARANCE DEALS ── --}}
@if($featuredProducts->count())
<section class="py-5" style="background:#F8FAFC">
    <div class="container">
        <div class="section-header">
            <div>
                <span class="section-badge-3d"><i class="bi bi-stars"></i> Curated</span>
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
<section class="py-5" style="background:#FFFFFF">
    <div class="container">
        <div class="section-header">
            <div>
                <span class="section-badge-3d"><i class="bi bi-fire"></i> Mega Discounts</span>
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
                <span class="section-badge-3d"><i class="bi bi-geo-alt-fill"></i> Location</span>
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
<section class="py-5" style="background:#FFFFFF">
    <div class="container">
        <div class="section-header">
            <div>
                <span class="section-badge-3d"><i class="bi bi-clock-history"></i> Fresh Surplus</span>
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
            <a href="{{ route('deals') }}" class="btn-3d-primary px-5 py-3">
                <i class="bi bi-grid-3x3-gap-fill me-2"></i>Explore All Clearance Deals
            </a>
        </div>
    </div>
</section>

{{-- ── 7. 3D ISOMETRIC "HOW IT WORKS" ── --}}
<section class="py-5" style="background:#F8FAFC" id="how-it-works">
    <div class="container">
        <div class="text-center mb-5" style="max-width: 620px; margin: 0 auto;">
            <span class="section-badge-3d"><i class="bi bi-lightbulb-fill"></i> Simple Workflow</span>
            <h2 class="section-title">How <span>BachatMart</span> Works</h2>
            <p class="text-muted small mt-2">
                A seamless 3-step bridge between local shopkeepers clearing unsold inventory and local bargain shoppers.
            </p>
        </div>

        <div class="row g-4 pedestal-stage-row">
            <div class="col-md-4">
                <div class="stage-pedestal-3d">
                    <div class="pedestal-number-3d">01</div>
                    <div class="pedestal-icon-wrapper">
                        <i class="bi bi-shop"></i>
                    </div>
                    <h5 class="fw-800 text-dark mb-2">Shopkeeper Lists Stock</h5>
                    <p class="text-muted small mb-0">Local retailers list dead or excess inventory at steep clearance prices in under 2 minutes.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stage-pedestal-3d">
                    <div class="pedestal-number-3d">02</div>
                    <div class="pedestal-icon-wrapper">
                        <i class="bi bi-phone-vibrate"></i>
                    </div>
                    <h5 class="fw-800 text-dark mb-2">Shopper Discovers Deal</h5>
                    <p class="text-muted small mb-0">Local buyers browse verified deals nearby, check pricing, and message the store directly on WhatsApp.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stage-pedestal-3d">
                    <div class="pedestal-number-3d">03</div>
                    <div class="pedestal-icon-wrapper">
                        <i class="bi bi-bag-check-fill"></i>
                    </div>
                    <h5 class="fw-800 text-dark mb-2">Inspect & Buy In-Store</h5>
                    <p class="text-muted small mb-0">The customer visits the neighborhood shop, checks the product quality in person, and pays directly.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── 8. 3D SELLER CALL-TO-ACTION BANNER ── --}}
<section class="py-5" style="background:#FFFFFF">
    <div class="container">
        <div class="seller-banner-3d">
            <div class="row align-items-center justify-content-between g-4 position-relative" style="z-index: 2;">
                <div class="col-lg-7 text-white">
                    <div class="seller-badge-3d">
                        <i class="bi bi-graph-up-arrow"></i> Retailers & Wholesalers
                    </div>
                    <h2 class="fw-800 text-white mb-2" style="font-size: clamp(1.8rem, 2.6vw, 2.5rem);">
                        Have Unsold Stock in Your Shop?
                    </h2>
                    <p class="text-secondary mb-0" style="max-width: 520px; font-size:1.05rem;">
                        Join hundreds of local retailers clearing excess inventory. Zero commission, no shipping delays, direct store footfall.
                    </p>
                </div>
                <div class="col-lg-5 text-lg-end">
                    <a href="{{ route('register.seller') }}" class="btn-3d-primary px-4 py-3" style="background: linear-gradient(180deg, #FFFFFF 0%, #F1F5F9 100%); color:#0F172A !important; box-shadow: 0 6px 0 #94A3B8, 0 16px 28px rgba(0,0,0,0.3);">
                        <i class="bi bi-shop-window me-1 text-primary-bm"></i> Register Shop for Free &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
{{-- Three.js WebGL Library for 3D Hero Scene --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
{{-- BachatMart 3D Engine & Physics --}}
<script src="{{ asset('js/home-3d.js') }}?v={{ file_exists(public_path('js/home-3d.js')) ? filemtime(public_path('js/home-3d.js')) : '1.0' }}"></script>
@endpush
