@extends('layouts.app')

@section('title', 'BachatMart - Local Surplus Stock & Clearance Deals')
@section('meta_description', 'Discover verified clearance deals and surplus stock discounts from local shops near you. Contact shopkeepers directly on WhatsApp and purchase in-store.')

@section('content')

{{-- ── 1. HERO SECTION ── --}}
<section class="py-5" style="background: radial-gradient(120% 100% at 50% 0%, #FFF5F0 0%, #FFFFFF 100%); border-bottom: 1px solid var(--bm-border-light);">
    <div class="container py-2 py-lg-4">
        <div class="row align-items-center g-4 g-lg-5">
            {{-- Left Column: Value Prop & Actions --}}
            <div class="col-lg-7">
                {{-- Clean Badge Tag --}}
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill"
                     style="background: #FFF0EB; border: 1px solid #FFD3C4; font-size: 0.76rem; font-weight: 700; color: var(--bm-primary); letter-spacing: 0.04em;">
                    <i class="bi bi-patch-check-fill text-primary-bm"></i> LOCAL SURPLUS STOCK & CLEARANCE NETWORK
                </div>

                {{-- Hero Title --}}
                <h1 class="fw-900 text-dark mb-3" style="font-size: clamp(2rem, 3.8vw, 3.2rem); line-height: 1.18; letter-spacing: -0.035em;">
                    Turn Surplus Stock Into <span class="text-primary-bm">Direct Savings</span>
                </h1>

                <p class="text-muted mb-4 fs-6" style="max-width: 580px; line-height: 1.68; font-weight: 400;">
                    Connect directly with local neighborhood storekeepers liquidating excess inventory at steep clearance markdowns. Chat on WhatsApp, inspect items in-store, and pay at the counter.
                </p>

                {{-- Hero Search Form --}}
                <form action="{{ route('deals') }}" method="GET" class="d-flex align-items-center bg-white border rounded-pill p-1 shadow-sm mb-4" style="max-width: 560px; border-color: var(--bm-border) !important;">
                    <i class="bi bi-search text-muted ms-3 me-2 fs-6"></i>
                    <input type="text" name="q" class="form-control border-0 bg-transparent shadow-none py-2 px-1"
                           placeholder="Search surplus electronics, apparel, footwear, home..."
                           style="font-size: 0.9rem;">
                    @if($selectedCity)
                        <input type="hidden" name="city" value="{{ $selectedCity }}">
                    @endif
                    <button type="submit" class="btn btn-primary-bm rounded-pill px-4 py-2 text-nowrap">
                        Find Deals
                    </button>
                </form>

                {{-- Action Buttons --}}
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="{{ route('deals') }}" class="btn btn-primary-bm px-4 py-2 fw-700">
                        <i class="bi bi-lightning-charge-fill me-1"></i>Explore Live Deals
                    </a>
                    <a href="{{ route('register.seller') }}" class="btn btn-outline-bm px-4 py-2 fw-700">
                        <i class="bi bi-shop-window me-1"></i>Sell Surplus Stock (₹12/Item)
                    </a>
                </div>

                {{-- Trust Pillars Pills --}}
                <div class="d-flex flex-wrap gap-2 mb-4 pb-2">
                    <span class="badge rounded-pill bg-white border text-dark fw-600 px-3 py-2 shadow-xs" style="border-color: var(--bm-border) !important; font-size: 0.76rem;">
                        <i class="bi bi-check-circle-fill text-success me-1"></i> 0% Middleman Fees
                    </span>
                    <span class="badge rounded-pill bg-white border text-dark fw-600 px-3 py-2 shadow-xs" style="border-color: var(--bm-border) !important; font-size: 0.76rem;">
                        <i class="bi bi-eye-fill text-primary-bm me-1"></i> Inspect In-Store
                    </span>
                    <span class="badge rounded-pill bg-white border text-dark fw-600 px-3 py-2 shadow-xs" style="border-color: var(--bm-border) !important; font-size: 0.76rem;">
                        <i class="bi bi-whatsapp text-success me-1"></i> Direct WhatsApp Inquiry
                    </span>
                    <span class="badge rounded-pill bg-white border text-dark fw-600 px-3 py-2 shadow-xs" style="border-color: var(--bm-border) !important; font-size: 0.76rem;">
                        <i class="bi bi-shield-check text-primary-bm me-1"></i> Verified Local Shops
                    </span>
                </div>

                {{-- Clean Stats Metric Pods --}}
                <div class="row g-3">
                    <div class="col-4">
                        <div class="p-3 bg-white border rounded-4 text-center shadow-sm" style="border-color: var(--bm-border) !important;">
                            <div class="fw-900 fs-4 text-dark mb-0">
                                {{ number_format(\App\Models\Product::approved()->count()) }}+
                            </div>
                            <div class="text-muted small fw-600" style="font-size: 0.76rem;">Live Deals</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-white border rounded-4 text-center shadow-sm" style="border-color: var(--bm-border) !important;">
                            <div class="fw-900 fs-4 text-dark mb-0">
                                {{ \App\Models\Shop::active()->count() }}+
                            </div>
                            <div class="text-muted small fw-600" style="font-size: 0.76rem;">Verified Stores</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-white border rounded-4 text-center shadow-sm" style="border-color: var(--bm-border) !important;">
                            <div class="fw-900 fs-4 text-primary-bm mb-0">
                                Up to 70%
                            </div>
                            <div class="text-muted small fw-600" style="font-size: 0.76rem;">Direct Savings</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Live Featured Deal Spotlight Card --}}
            <div class="col-lg-5">
                @php
                    $heroDeal = $topDeals->first() ?? $featuredProducts->first();
                @endphp

                @if($heroDeal)
                <div class="hero-deal-card">
                    <div class="hero-deal-card__img-box">
                        <a href="{{ route('product.show', $heroDeal->slug) }}" class="d-block w-100 h-100">
                            <img src="{{ $heroDeal->primary_image_url }}"
                                 alt="{{ $heroDeal->name }}"
                                 onerror="this.onerror=null; this.src='{{ asset('images/product-placeholder.svg') }}';">
                        </a>

                        {{-- Floating Badges --}}
                        <div class="position-absolute top-0 start-0 m-3 z-2">
                            @if($heroDeal->discount_percent > 0)
                                <span class="badge rounded-pill px-3 py-2 text-white fw-800 shadow-sm" style="background: var(--bm-danger); font-size: 0.78rem;">
                                    🔥 -{{ round($heroDeal->discount_percent) }}% OFF
                                </span>
                            @endif
                        </div>
                        <div class="position-absolute top-0 end-0 m-3 z-2">
                            <span class="badge rounded-pill px-3 py-2 text-white fw-800 shadow-sm" style="background: var(--bm-primary-gradient); font-size: 0.78rem;">
                                ⭐ Deal of the Day
                            </span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small fw-700 text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">
                            {{ $heroDeal->category->name ?? 'Clearance Lot' }}
                        </span>
                        @if($heroDeal->shop)
                            <span class="text-success small fw-700">
                                <i class="bi bi-geo-alt-fill me-1"></i>{{ $heroDeal->shop->city }}
                            </span>
                        @endif
                    </div>

                    <h5 class="fw-800 text-dark fs-6 mb-2 text-truncate" title="{{ $heroDeal->name }}">
                        <a href="{{ route('product.show', $heroDeal->slug) }}" class="text-decoration-none text-dark">
                            {{ $heroDeal->name }}
                        </a>
                    </h5>

                    <div class="d-flex align-items-baseline gap-2 mb-3">
                        <span class="fs-4 fw-900 text-dark">₹{{ number_format($heroDeal->offer_price) }}</span>
                        @if($heroDeal->original_price > $heroDeal->offer_price)
                            <span class="text-muted text-decoration-line-through small">₹{{ number_format($heroDeal->original_price) }}</span>
                            <span class="badge rounded-pill bg-success-subtle text-success fw-700 ms-auto px-2 py-1" style="font-size: 0.74rem;">
                                Save ₹{{ number_format($heroDeal->original_price - $heroDeal->offer_price) }}
                            </span>
                        @endif
                    </div>

                    @if($heroDeal->shop)
                        <div class="d-flex align-items-center gap-2 mb-3 p-2 rounded-3 bg-light" style="font-size: 0.8rem;">
                            <i class="bi bi-shop text-primary-bm"></i>
                            <span class="text-dark fw-600 text-truncate">{{ $heroDeal->shop->name }}</span>
                            @if($heroDeal->shop->is_verified)
                                <i class="bi bi-patch-check-fill text-success" title="Verified Merchant"></i>
                            @endif
                        </div>
                    @endif

                    <div class="d-flex gap-2">
                        <a href="{{ route('product.show', $heroDeal->slug) }}" class="btn btn-primary-bm flex-grow-1 py-2 fw-700">
                            View Deal Details <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                        @if($heroDeal->shop && $heroDeal->shop->whatsapp)
                            <a href="{{ $heroDeal->shop->whatsapp_url }}" target="_blank"
                               onclick="trackLead('whatsapp', {{ $heroDeal->shop->id }}, {{ $heroDeal->id }})"
                               class="btn btn-whatsapp rounded-pill px-3"
                               title="Chat with Shopkeeper"
                               aria-label="WhatsApp">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                        @endif
                    </div>
                </div>
                @else
                <div class="card border-0 p-4 bg-white rounded-4 shadow-sm text-center" style="border: 1px solid var(--bm-border) !important;">
                    <i class="bi bi-tags text-primary-bm display-4 mb-3"></i>
                    <h5 class="fw-800 text-dark mb-2">Hyper-Local Clearance Marketplace</h5>
                    <p class="text-muted small mb-3">Explore live discounts from verified local retail shops or list your surplus inventory.</p>
                    <a href="{{ route('deals') }}" class="btn btn-primary-bm py-2 fw-700">Explore Clearance Deals</a>
                </div>
                @endif
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

{{-- ── 3. MEGA MARKDOWNS / BIGGEST DISCOUNTS ── --}}
@if($topDeals->count())
<section class="py-5" style="background: #F8FAFC; border-bottom: 1px solid var(--bm-border-light);">
    <div class="container">
        <div class="section-header">
            <div>
                <span class="section-tag" style="background: #FEF2F2; border-color: #FECACA; color: var(--bm-danger);">
                    <i class="bi bi-fire text-danger"></i> Mega Markdowns
                </span>
                <h2 class="section-title">Top <span>Discounts</span></h2>
                <p class="section-subtitle">Highest price reductions and clearance markdowns available right now</p>
            </div>
            <a href="{{ route('deals') }}?sort=discount" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-600">
                View All Discounts &rarr;
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

{{-- ── 4. FEATURED CLEARANCE DEALS ── --}}
@if($featuredProducts->count())
<section class="py-5" style="background: #FFFFFF; border-bottom: 1px solid var(--bm-border-light);">
    <div class="container">
        <div class="section-header">
            <div>
                <span class="section-tag"><i class="bi bi-stars text-primary-bm"></i> Curated Picks</span>
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

{{-- ── 5. FEATURED NEIGHBORHOOD MERCHANTS / SHOPS ── --}}
@if($featuredShops->count())
<section class="py-5" style="background: #F8FAFC; border-bottom: 1px solid var(--bm-border-light);">
    <div class="container">
        <div class="section-header">
            <div>
                <span class="section-tag"><i class="bi bi-shop-window text-primary-bm"></i> Neighborhood Merchants</span>
                <h2 class="section-title">Verified Local <span>Stores</span></h2>
                <p class="section-subtitle">Connect directly with trusted shops clearing inventory in your city</p>
            </div>
            <a href="{{ route('shops.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-600">
                View All Stores &rarr;
            </a>
        </div>

        <div class="row g-3 g-md-4">
            @foreach($featuredShops as $shop)
            <div class="col-12 col-md-6 col-lg-4">
                @include('components.shop-card', ['shop' => $shop])
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── 6. DEALS IN YOUR CITY ── --}}
@if($dealsNearYou->count())
<section class="py-5" style="background: #FFFFFF; border-bottom: 1px solid var(--bm-border-light);">
    <div class="container">
        <div class="section-header">
            <div>
                <span class="section-tag"><i class="bi bi-geo-alt-fill text-primary-bm"></i> Hyper-Local</span>
                <h2 class="section-title">Deals in <span>{{ $selectedCity ?? 'Your Area' }}</span></h2>
                <p class="section-subtitle">Local stores in {{ $selectedCity ?? 'your city' }} with active clearance stock</p>
            </div>

            @if($availableCities->count())
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
            @endif
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

{{-- ── 7. LATEST ARRIVALS ── --}}
@if($latestProducts->count())
<section class="py-5" style="background: #F8FAFC; border-bottom: 1px solid var(--bm-border-light);">
    <div class="container">
        <div class="section-header">
            <div>
                <span class="section-tag"><i class="bi bi-clock-history text-primary-bm"></i> Fresh Inventory</span>
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
@endif

{{-- ── 8. WHY BACHATMART (TRUST & VALUE PROPOSITIONS) ── --}}
<section class="py-5" style="background: #FFFFFF; border-bottom: 1px solid var(--bm-border-light);">
    <div class="container py-2">
        <div class="text-center mb-5" style="max-width: 620px; margin: 0 auto;">
            <span class="section-tag"><i class="bi bi-shield-check"></i> Buyer & Seller Protection</span>
            <h2 class="section-title mb-2">Why Shop on <span>BachatMart</span>?</h2>
            <p class="text-muted fs-6">
                A smarter, safer way to discover clearance bargains directly from neighborhood retail shops.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="bm-trust-card">
                    <div class="bm-trust-icon" style="background: #FFF5F0; color: var(--bm-primary);">
                        <i class="bi bi-eye-fill"></i>
                    </div>
                    <h5 class="fw-800 text-dark fs-6 mb-2">Inspect Before Paying</h5>
                    <p class="text-muted small mb-0" style="line-height: 1.6;">
                        Visit the local store, hold and inspect the item with your own hands. Zero guesswork, zero delivery fraud, and complete peace of mind.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="bm-trust-card">
                    <div class="bm-trust-icon" style="background: #ECFDF5; color: var(--bm-success);">
                        <i class="bi bi-percent"></i>
                    </div>
                    <h5 class="fw-800 text-dark fs-6 mb-2">0% Middleman Markups</h5>
                    <p class="text-muted small mb-0" style="line-height: 1.6;">
                        We don't charge hefty sales commissions. Storekeepers pass the entire savings directly to you at honest liquidation prices.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="bm-trust-card">
                    <div class="bm-trust-icon" style="background: #F0FDF4; color: var(--bm-whatsapp-dark);">
                        <i class="bi bi-whatsapp"></i>
                    </div>
                    <h5 class="fw-800 text-dark fs-6 mb-2">Direct WhatsApp Connect</h5>
                    <p class="text-muted small mb-0" style="line-height: 1.6;">
                        Chat directly with the shopkeeper to check real-time stock availability, reserve items, or ask questions before visiting.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="bm-trust-card">
                    <div class="bm-trust-icon" style="background: #EFF6FF; color: #3B82F6;">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                    <h5 class="fw-800 text-dark fs-6 mb-2">Dead Stock Liquidated</h5>
                    <p class="text-muted small mb-0" style="line-height: 1.6;">
                        Local retailers convert stagnant, unsold capital into fast cash for just ₹12 per product listing with instant local reach.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── 9. HOW IT WORKS ── --}}
<section class="py-5" style="background: #F8FAFC; border-bottom: 1px solid var(--bm-border-light);" id="how-it-works">
    <div class="container py-2">
        <div class="text-center mb-5" style="max-width: 620px; margin: 0 auto;">
            <span class="section-tag"><i class="bi bi-lightning-charge-fill"></i> Simple 3-Step Process</span>
            <h2 class="section-title mb-2">How <span>BachatMart</span> Works</h2>
            <p class="text-muted fs-6">
                A transparent, direct connection between local storekeepers clearing excess stock and bargain hunters nearby.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="bm-step-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bm-step-number">01</div>
                        <i class="bi bi-shop-window text-primary-bm fs-3"></i>
                    </div>
                    <h5 class="fw-800 text-dark fs-6 mb-2">Shopkeeper Lists Surplus Stock</h5>
                    <p class="text-muted small mb-0" style="line-height: 1.6;">
                        Local retailers list surplus or excess inventory at steep clearance prices for just ₹12 per product.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="bm-step-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bm-step-number">02</div>
                        <i class="bi bi-chat-left-dots-fill text-primary-bm fs-3"></i>
                    </div>
                    <h5 class="fw-800 text-dark fs-6 mb-2">Shopper Discovers Nearby Deals</h5>
                    <p class="text-muted small mb-0" style="line-height: 1.6;">
                        Local buyers browse verified deals nearby, compare prices, and connect directly with the shop via WhatsApp or Call.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="bm-step-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bm-step-number">03</div>
                        <i class="bi bi-bag-check-fill text-primary-bm fs-3"></i>
                    </div>
                    <h5 class="fw-800 text-dark fs-6 mb-2">Inspect & Purchase In-Store</h5>
                    <p class="text-muted small mb-0" style="line-height: 1.6;">
                        The buyer visits the neighborhood shop, verifies the product in person, and pays directly at the store counter.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── 10. SELLER CALL-TO-ACTION BANNER ── --}}
<section class="py-5" style="background: #FFFFFF;">
    <div class="container">
        <div class="p-4 p-md-5 text-white rounded-4 shadow-lg position-relative overflow-hidden"
             style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); border: 1px solid #334155;">
            {{-- Background decorative circle --}}
            <div class="position-absolute" style="top: -50px; right: -50px; width: 250px; height: 250px; border-radius: 50%; background: radial-gradient(circle, rgba(255,87,34,0.18) 0%, transparent 70%); pointer-events: none;"></div>

            <div class="row align-items-center justify-content-between g-4 position-relative" style="z-index: 2;">
                <div class="col-lg-7">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill fw-700"
                         style="background: rgba(255, 87, 34, 0.2); color: #FF8A65; font-size: 0.78rem; border: 1px solid rgba(255, 87, 34, 0.35);">
                        <i class="bi bi-tag-fill"></i> Just ₹12 Per Product Listing &bull; 0% Commission
                    </div>
                    <h2 class="fw-900 text-white mb-2" style="font-size: clamp(1.6rem, 2.6vw, 2.3rem); letter-spacing: -0.03em;">
                        Have Unsold Surplus Stock in Your Shop?
                    </h2>
                    <p class="text-light text-opacity-75 mb-0 fs-6" style="max-width: 540px; line-height: 1.7; font-weight: 400;">
                        Join local retailers liquidating excess stock into cash. List any surplus item for just ₹12. Get direct customer WhatsApp inquiries and in-store footfall with zero middleman commissions.
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
