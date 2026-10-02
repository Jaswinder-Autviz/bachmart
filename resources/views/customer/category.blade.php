@extends('layouts.app')
@section('title', $category->name . ' Clearance Deals & Surplus Stock - BachatMart')
@section('meta_description', 'Discover ' . $products->total() . '+ live clearance deals in ' . $category->name . ' from local verified shops. Inspect in-store, chat on WhatsApp, and save up to ' . round($stats->max_discount ?? 70) . '%.')

@section('content')
<div class="category-page py-3 py-lg-4">
    <div class="container">

        {{-- ── 1. BREADCRUMBS ── --}}
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0 py-1 small">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted"><i class="bi bi-house-door-fill me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('deals') }}" class="text-decoration-none text-muted">Categories</a></li>
                <li class="breadcrumb-item active fw-700 text-dark" aria-current="page">{{ $category->name }}</li>
            </ol>
        </nav>

        {{-- ── 2. MODERN CATEGORY HERO SHOWCASE ── --}}
        <div class="category-hero position-relative rounded-4 p-4 p-lg-5 mb-4 text-white overflow-hidden shadow-sm"
             style="background: linear-gradient(135deg, #0B132B 0%, #1C2541 50%, #1F2937 100%); border: 1px solid rgba(255, 255, 255, 0.1);">
            
            {{-- Background decorative ambient lights --}}
            <div class="position-absolute" style="top: -60px; right: -40px; width: 260px; height: 260px; background: radial-gradient(circle, rgba(255, 87, 34, 0.25) 0%, transparent 70%); pointer-events: none;"></div>
            <div class="position-absolute" style="bottom: -50px; left: 10%; width: 200px; height: 200px; background: radial-gradient(circle, rgba(16, 185, 129, 0.18) 0%, transparent 70%); pointer-events: none;"></div>

            <div class="row align-items-center g-4 position-relative" style="z-index: 2;">
                <div class="col-lg-8">
                    <div class="d-flex align-items-start gap-3">
                        <div class="category-icon-box flex-shrink-0 d-flex align-items-center justify-content-center rounded-4 shadow-sm"
                             style="width: 76px; height: 76px; background: rgba(255, 87, 34, 0.16); border: 2px solid rgba(255, 87, 34, 0.35); font-size: 2.5rem;">
                            {{ $category->icon ?? '🏷️' }}
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                                <span class="badge rounded-pill px-3 py-1 fw-700" style="background: rgba(255, 87, 34, 0.2); color: #FF8A65; border: 1px solid rgba(255, 87, 34, 0.3); font-size: 0.72rem; letter-spacing: 0.05em;">
                                    <i class="bi bi-patch-check-fill me-1"></i>VERIFIED DEPARTMENT CLEARANCE
                                </span>
                                @if(request('city') || session('selected_city'))
                                    <span class="badge rounded-pill px-2.5 py-1 fw-600 bg-white bg-opacity-10 text-light border border-white border-opacity-10" style="font-size: 0.72rem;">
                                        <i class="bi bi-geo-alt-fill text-warning me-1"></i>{{ request('city', session('selected_city')) }}
                                    </span>
                                @endif
                            </div>

                            <h1 class="fw-900 mb-2 text-white" style="font-size: clamp(1.8rem, 3.2vw, 2.6rem); letter-spacing: -0.03em; line-height: 1.2;">
                                {{ $category->name }}
                            </h1>

                            <p class="text-light text-opacity-80 mb-3 small" style="max-width: 620px; line-height: 1.6;">
                                {{ $category->description ?? 'Explore verified surplus inventory, overstock clearances, and liquidation deals in ' . $category->name . ' directly from neighborhood merchants. In-store purchase with zero middleman markup.' }}
                            </p>

                            {{-- Highlight Stat Badges --}}
                            <div class="d-flex flex-wrap gap-2 pt-1">
                                <span class="badge bg-white bg-opacity-10 text-white rounded-pill px-3 py-1.5 fw-600 border border-white border-opacity-15">
                                    <i class="bi bi-tag-fill text-warning me-1"></i>{{ $products->total() }} Active Deals
                                </span>
                                @if(($stats->max_discount ?? 0) > 0)
                                <span class="badge bg-white bg-opacity-10 text-white rounded-pill px-3 py-1.5 fw-600 border border-white border-opacity-15">
                                    <i class="bi bi-lightning-charge-fill text-danger me-1"></i>Up to {{ round($stats->max_discount) }}% Off
                                </span>
                                @endif
                                <span class="badge bg-white bg-opacity-10 text-white rounded-pill px-3 py-1.5 fw-600 border border-white border-opacity-15">
                                    <i class="bi bi-shield-check text-success me-1"></i>Inspect In-Store
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick In-Category Search --}}
                <div class="col-lg-4">
                    <div class="p-3.5 p-lg-4 rounded-4" style="background: rgba(255, 255, 255, 0.08); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.12);">
                        <label class="form-label text-light fw-700 small mb-2 d-flex align-items-center justify-content-between">
                            <span><i class="bi bi-search me-1.5 text-warning"></i>Search in {{ $category->name }}</span>
                            @if(request('q'))
                                <a href="{{ route('category.show', array_merge(['category' => $category->slug], request()->except('q'))) }}" class="text-warning text-decoration-none small" style="font-size: 0.75rem;">Clear</a>
                            @endif
                        </label>
                        <form method="GET" action="{{ route('category.show', $category->slug) }}">
                            {{-- Retain other filters --}}
                            @foreach(request()->except(['q', 'page']) as $k => $v)
                                @if(is_array($v))
                                    @foreach($v as $sub)
                                        <input type="hidden" name="{{ $k }}[]" value="{{ $sub }}">
                                    @endforeach
                                @else
                                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                @endif
                            @endforeach
                            <div class="input-group">
                                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm rounded-start-pill border-0 ps-3" placeholder="e.g. brand, model, size..." style="font-size: 0.88rem;">
                                <button type="submit" class="btn btn-primary-bm btn-sm rounded-end-pill px-3 fw-700">Find</button>
                            </div>
                        </form>
                        <div class="mt-2 text-light text-opacity-60" style="font-size: 0.72rem;">
                            <i class="bi bi-info-circle me-1"></i>Search titles, specs, brands in this category
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── 3. CATEGORY SWITCHER / HORIZONTAL PILL RAIL ── --}}
        <div class="category-rail-wrapper mb-4">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-uppercase text-muted fw-800" style="font-size: 0.72rem; letter-spacing: 0.05em;">Browse Categories</span>
                <span class="text-muted small" style="font-size: 0.75rem;">Scroll & click to switch</span>
            </div>
            <div class="category-rail d-flex gap-2 pb-2 overflow-auto text-nowrap" style="scrollbar-width: thin;">
                @foreach($categories as $cat)
                    @php
                        $isActive = ($cat->slug === $category->slug);
                        $count = $cat->approved_products_count ?? $cat->approvedProducts()->count();
                    @endphp
                    <a href="{{ route('category.show', $cat->slug) }}"
                       class="category-pill-btn text-decoration-none d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill transition-all {{ $isActive ? 'active-pill' : 'inactive-pill' }}">
                        <span class="cat-pill-icon fs-6">{{ $cat->icon ?? '🏷️' }}</span>
                        <span class="cat-pill-name fw-700">{{ $cat->name }}</span>
                        @if($count > 0)
                            <span class="badge rounded-pill {{ $isActive ? 'bg-white text-dark' : 'bg-light text-muted' }}" style="font-size: 0.68rem; font-weight: 700;">
                                {{ $count }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>

        {{-- ── 4. SPOTLIGHT CLEARANCE DEAL IN THIS CATEGORY (IF AVAILABLE) ── --}}
        @if($spotlightDeal && $spotlightDeal->discount_percent >= 25 && !request()->hasAny(['q', 'price_range', 'min_price', 'max_price', 'condition', 'brand']))
        <div class="card border-0 rounded-4 shadow-sm mb-4 overflow-hidden" style="background: linear-gradient(135deg, #FFF7ED 0%, #FFFFFF 100%); border: 1.5px solid #FFEDD5 !important;">
            <div class="card-body p-3 p-lg-4">
                <div class="row align-items-center g-3 g-lg-4">
                    <div class="col-auto">
                        <span class="badge rounded-pill px-3 py-1.5 fw-800" style="background: var(--bm-primary-gradient); color: #fff; font-size: 0.74rem;">
                            <i class="bi bi-fire me-1"></i>TOP DEAL IN {{ strtoupper($category->name) }}
                        </span>
                    </div>
                    <div class="col">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <span class="fw-800 text-dark text-truncate" style="max-width: 320px;">{{ $spotlightDeal->name }}</span>
                            <span class="text-danger fw-900 fs-6">-{{ round($spotlightDeal->discount_percent) }}% OFF</span>
                            <span class="fw-900 text-dark fs-5">₹{{ number_format($spotlightDeal->offer_price) }}</span>
                            @if($spotlightDeal->original_price > $spotlightDeal->offer_price)
                                <span class="text-muted text-decoration-line-through small">₹{{ number_format($spotlightDeal->original_price) }}</span>
                            @endif
                            @if($spotlightDeal->shop)
                                <span class="text-muted small d-none d-md-inline">· Store: <strong>{{ $spotlightDeal->shop->name }}</strong> ({{ $spotlightDeal->shop->city }})</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-12 col-md-auto d-flex gap-2">
                        <a href="{{ route('product.show', $spotlightDeal->slug) }}" class="btn btn-sm btn-primary-bm px-3.5 py-1.5 fw-700">
                            Grab Deal <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                        @if($spotlightDeal->shop && $spotlightDeal->shop->whatsapp)
                            <a href="{{ $spotlightDeal->shop->whatsapp_url }}" target="_blank"
                               onclick="trackLead('whatsapp', {{ $spotlightDeal->shop->id }}, {{ $spotlightDeal->id }})"
                               class="btn btn-sm btn-whatsapp px-3 py-1.5 fw-700" title="Chat on WhatsApp">
                                <i class="bi bi-whatsapp me-1"></i>WhatsApp
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- ── 5. TOP VERIFIED STORES IN THIS CATEGORY ── --}}
        @if($categoryShops->count() > 0 && !request()->hasAny(['page', 'q']))
        <div class="category-shops-section mb-4 pb-2">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h2 class="h6 fw-900 mb-0 text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-shop-window text-primary-bm"></i>
                        <span>Verified Local Stores for {{ $category->name }}</span>
                    </h2>
                    <p class="text-muted mb-0" style="font-size: 0.78rem;">Visit, inspect products in-person, or chat directly with the storekeeper.</p>
                </div>
                <a href="{{ route('deals', ['category' => $category->id]) }}" class="small fw-700 text-primary-bm text-decoration-none d-none d-sm-inline">
                    View All <i class="bi bi-chevron-right"></i>
                </a>
            </div>

            <div class="row g-3">
                @foreach($categoryShops as $shop)
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white transition-hover position-relative" style="border: 1px solid var(--bm-border) !important;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <img src="{{ $shop->logo_url }}" alt="{{ $shop->name }}" width="44" height="44"
                                 class="rounded-3 border border-2 border-white shadow-xs flex-shrink-0"
                                 style="object-fit: cover; background: #FFF;"
                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($shop->name) }}&background=FF5722&color=fff&size=44';">
                            <div class="overflow-hidden">
                                <h6 class="fw-800 mb-0 text-truncate text-dark" style="font-size: 0.88rem;" title="{{ $shop->name }}">
                                    <a href="{{ route('shop.show', $shop->slug) }}" class="text-decoration-none text-dark">
                                        {{ $shop->name }}
                                    </a>
                                </h6>
                                <div class="text-muted text-truncate" style="font-size: 0.72rem;">
                                    <i class="bi bi-geo-alt-fill text-primary-bm"></i>{{ $shop->city }}
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between text-muted mt-auto pt-2 border-top" style="font-size: 0.72rem;">
                            <span class="badge bg-light text-dark fw-700 rounded-pill px-2 py-1">
                                {{ $shop->products_count }} {{ Str::plural('deal', $shop->products_count) }}
                            </span>
                            @if($shop->rating > 0)
                                <span class="text-warning fw-700">
                                    ★ {{ number_format($shop->rating, 1) }}
                                </span>
                            @else
                                <span class="text-success fw-700">
                                    <i class="bi bi-patch-check-fill"></i> Verified
                                </span>
                            @endif
                        </div>

                        <div class="d-flex gap-1.5 mt-2">
                            <a href="{{ route('shop.show', $shop->slug) }}" class="btn btn-outline-secondary btn-sm rounded-pill w-100 py-1 fw-700" style="font-size: 0.74rem;">
                                Store Deals
                            </a>
                            @if($shop->whatsapp)
                                <a href="{{ $shop->whatsapp_url }}" target="_blank"
                                   onclick="trackLead('whatsapp', {{ $shop->id }})"
                                   class="btn btn-whatsapp btn-sm rounded-pill px-2.5 py-1"
                                   title="Chat on WhatsApp">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── 6. MAIN CONTENT SECTION: SIDEBAR + PRODUCT FEED ── --}}
        <div class="row g-4">
            
            {{-- ── DESKTOP FILTER SIDEBAR ── --}}
            <div class="col-lg-3 d-none d-lg-block">
                <div class="card border-0 shadow-sm rounded-4 sticky-top p-4 bg-white" style="top: 90px; border: 1px solid var(--bm-border) !important; max-height: calc(100vh - 110px); overflow-y: auto;">
                    
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                        <h6 class="fw-800 mb-0 text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-funnel-fill text-primary-bm"></i> Filter Deals
                        </h6>
                        @if(request()->hasAny(['q', 'city', 'min_discount', 'price_range', 'min_price', 'max_price', 'condition', 'brand', 'featured', 'negotiable', 'verified_shop', 'sort']))
                            <a href="{{ route('category.show', $category->slug) }}" class="text-danger small fw-700 text-decoration-none">
                                Reset All
                            </a>
                        @endif
                    </div>

                    <form method="GET" action="{{ route('category.show', $category->slug) }}" id="desktopFilterForm">
                        
                        {{-- Search Filter in Category --}}
                        <div class="mb-3.5 pb-3 border-bottom">
                            <label class="form-label small fw-800 text-dark mb-1.5">Keyword Search</label>
                            <div class="input-group input-group-sm">
                                <input type="text" name="q" class="form-control rounded-3" placeholder="Brand, title, spec..." value="{{ request('q') }}">
                                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                            </div>
                        </div>

                        {{-- Sort Selector --}}
                        <div class="mb-3.5 pb-3 border-bottom">
                            <label class="form-label small fw-800 text-dark mb-1.5">Sort Deals</label>
                            <select name="sort" class="form-select form-select-sm rounded-3 fw-600" onchange="this.form.submit()">
                                <option value="" {{ !request('sort') ? 'selected' : '' }}>Featured Deals First</option>
                                <option value="discount" {{ request('sort') == 'discount' ? 'selected' : '' }}>Highest Discount %</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular (Views)</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest Listed</option>
                            </select>
                        </div>

                        {{-- Discount Percentage Filter --}}
                        <div class="mb-3.5 pb-3 border-bottom">
                            <label class="form-label small fw-800 text-dark mb-2">Discount %</label>
                            <div class="d-flex flex-column gap-1.5">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="min_discount" id="disc_any" value="" {{ !request('min_discount') ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label class="form-check-label small text-dark fw-600" for="disc_any">Any Discount</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="min_discount" id="disc_70" value="70" {{ request('min_discount') == '70' ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label class="form-check-label small text-dark fw-600 d-flex justify-content-between align-items-center" for="disc_70">
                                        <span>70% or more</span>
                                        <span class="badge rounded-pill bg-danger-subtle text-danger" style="font-size: 0.65rem;">Super Deal</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="min_discount" id="disc_50" value="50" {{ request('min_discount') == '50' ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label class="form-check-label small text-dark fw-600" for="disc_50">50% or more (Half Price)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="min_discount" id="disc_30" value="30" {{ request('min_discount') == '30' ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label class="form-check-label small text-dark fw-600" for="disc_30">30% or more</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="min_discount" id="disc_15" value="15" {{ request('min_discount') == '15' ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label class="form-check-label small text-dark fw-600" for="disc_15">15% or more</label>
                                </div>
                            </div>
                        </div>

                        {{-- Price Range Presets --}}
                        <div class="mb-3.5 pb-3 border-bottom">
                            <label class="form-label small fw-800 text-dark mb-2">Price Range</label>
                            <div class="d-flex flex-column gap-1.5 mb-2.5">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="price_range" id="pr_all" value="" {{ !request('price_range') ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label class="form-check-label small text-dark fw-600" for="pr_all">All Prices</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="price_range" id="pr_under500" value="under_500" {{ request('price_range') == 'under_500' ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label class="form-check-label small text-dark fw-600" for="pr_under500">Under ₹500</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="price_range" id="pr_500_1000" value="500_1000" {{ request('price_range') == '500_1000' ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label class="form-check-label small text-dark fw-600" for="pr_500_1000">₹500 to ₹1,000</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="price_range" id="pr_1000_2500" value="1000_2500" {{ request('price_range') == '1000_2500' ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label class="form-check-label small text-dark fw-600" for="pr_1000_2500">₹1,000 to ₹2,500</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="price_range" id="pr_above2500" value="above_2500" {{ request('price_range') == 'above_2500' ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label class="form-check-label small text-dark fw-600" for="pr_above2500">Above ₹2,500</label>
                                </div>
                            </div>

                            {{-- Custom Price Input --}}
                            <div class="row g-2 align-items-center">
                                <div class="col-5">
                                    <input type="number" name="min_price" class="form-control form-control-sm rounded-2 text-center" placeholder="Min ₹" value="{{ request('min_price') }}">
                                </div>
                                <div class="col-2 text-center text-muted small">-</div>
                                <div class="col-5">
                                    <input type="number" name="max_price" class="form-control form-control-sm rounded-2 text-center" placeholder="Max ₹" value="{{ request('max_price') }}">
                                </div>
                            </div>
                        </div>

                        {{-- City / Location Filter --}}
                        <div class="mb-3.5 pb-3 border-bottom">
                            <label class="form-label small fw-800 text-dark mb-1.5">City / Location</label>
                            @if(isset($availableCities) && $availableCities->count() > 0)
                                <select name="city" class="form-select form-select-sm rounded-3 fw-600" onchange="this.form.submit()">
                                    <option value="">All Locations</option>
                                    @foreach($availableCities as $c)
                                        <option value="{{ $c }}" {{ request('city', session('selected_city')) == $c ? 'selected' : '' }}>
                                            📍 {{ $c }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <input type="text" name="city" class="form-control form-control-sm rounded-3"
                                       placeholder="e.g. Mumbai, Delhi..." value="{{ request('city', session('selected_city')) }}">
                            @endif
                        </div>

                        {{-- Condition Filter --}}
                        <div class="mb-3.5 pb-3 border-bottom">
                            <label class="form-label small fw-800 text-dark mb-1.5">Item Condition</label>
                            <select name="condition" class="form-select form-select-sm rounded-3 fw-600" onchange="this.form.submit()">
                                <option value="">All Conditions</option>
                                <option value="new" {{ request('condition') == 'new' ? 'selected' : '' }}>Brand New Surplus</option>
                                <option value="open_box" {{ request('condition') == 'open_box' ? 'selected' : '' }}>Open Box / Display</option>
                                <option value="refurbished" {{ request('condition') == 'refurbished' ? 'selected' : '' }}>Certified Refurbished</option>
                            </select>
                        </div>

                        {{-- Popular Brands in this Category --}}
                        @if(isset($availableBrands) && $availableBrands->count() > 0)
                        <div class="mb-3.5 pb-3 border-bottom">
                            <label class="form-label small fw-800 text-dark mb-1.5">Brand</label>
                            <select name="brand" class="form-select form-select-sm rounded-3 fw-600" onchange="this.form.submit()">
                                <option value="">All Brands</option>
                                @foreach($availableBrands as $b)
                                    <option value="{{ $b }}" {{ request('brand') == $b ? 'selected' : '' }}>{{ $b }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        {{-- Checkbox Toggles --}}
                        <div class="mb-3 d-flex flex-column gap-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="featured" value="1" id="f_featured" {{ request('featured') ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="form-check-label small fw-700 text-dark" for="f_featured">⭐ Featured Deals Only</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="verified_shop" value="1" id="f_verified" {{ request('verified_shop') ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="form-check-label small fw-700 text-dark" for="f_verified">✅ Verified Stores Only</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="negotiable" value="1" id="f_negotiable" {{ request('negotiable') ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="form-check-label small fw-700 text-dark" for="f_negotiable">💬 Negotiable Price</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary-bm btn-sm w-100 rounded-pill fw-700 py-2 mb-2">
                            Apply Filters
                        </button>
                        
                        @if(request()->hasAny(['q', 'city', 'min_discount', 'price_range', 'min_price', 'max_price', 'condition', 'brand', 'featured', 'negotiable', 'verified_shop', 'sort']))
                            <a href="{{ route('category.show', $category->slug) }}" class="btn btn-outline-secondary btn-sm w-100 rounded-pill fw-600 py-1.5">
                                Clear All Filters
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            {{-- ── PRODUCT FEED COLUMN ── --}}
            <div class="col-lg-9 col-12">
                
                {{-- ── TOP CONTROLS & FILTER TOOLBAR ── --}}
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-3" style="border: 1px solid var(--bm-border) !important;">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        
                        {{-- Results summary --}}
                        <div>
                            <div class="fw-800 text-dark" style="font-size: 0.95rem;">
                                Showing <span class="text-primary-bm">{{ $products->total() }}</span> clearance {{ Str::plural('deal', $products->total()) }}
                                @if(request('city'))
                                    in <span class="text-dark">{{ request('city') }}</span>
                                @endif
                            </div>
                            <div class="text-muted small" style="font-size: 0.74rem;">
                                Real-time inventory liquidated by local retail shops
                            </div>
                        </div>

                        {{-- Mobile Filter Button & View Switcher --}}
                        <div class="d-flex align-items-center gap-2">
                            
                            {{-- Mobile Filter Drawer Trigger --}}
                            <button class="btn btn-sm btn-primary-bm rounded-pill px-3 d-lg-none fw-700" type="button" data-bs-toggle="offcanvas" data-bs-target="#categoryMobileFilter">
                                <i class="bi bi-sliders me-1"></i> Filters & Sort
                                @if(request()->hasAny(['q', 'city', 'min_discount', 'price_range', 'min_price', 'max_price', 'condition', 'brand', 'featured', 'negotiable', 'verified_shop', 'sort']))
                                    <span class="badge bg-white text-danger rounded-circle ms-1 px-1">●</span>
                                @endif
                            </button>

                            {{-- Sort Selector (Compact for desktop/tablet) --}}
                            <div class="d-none d-sm-flex align-items-center gap-1.5">
                                <label class="small text-muted fw-700 text-nowrap mb-0">Sort:</label>
                                <select class="form-select form-select-sm rounded-pill fw-600 py-1" style="font-size: 0.8rem; width: auto;"
                                        onchange="location.href = this.value;">
                                    @php
                                        $currQuery = request()->except('page');
                                    @endphp
                                    <option value="{{ route('category.show', array_merge(['category' => $category->slug], $currQuery, ['sort' => ''])) }}" {{ !request('sort') ? 'selected' : '' }}>Featured First</option>
                                    <option value="{{ route('category.show', array_merge(['category' => $category->slug], $currQuery, ['sort' => 'discount'])) }}" {{ request('sort') == 'discount' ? 'selected' : '' }}>Highest Discount</option>
                                    <option value="{{ route('category.show', array_merge(['category' => $category->slug], $currQuery, ['sort' => 'price_low'])) }}" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="{{ route('category.show', array_merge(['category' => $category->slug], $currQuery, ['sort' => 'price_high'])) }}" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                    <option value="{{ route('category.show', array_merge(['category' => $category->slug], $currQuery, ['sort' => 'popular'])) }}" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                                    <option value="{{ route('category.show', array_merge(['category' => $category->slug], $currQuery, ['sort' => 'newest'])) }}" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                                </select>
                            </div>

                            {{-- View Mode Switcher (Grid vs List) --}}
                            <div class="btn-group btn-group-sm rounded-pill p-0.5 border" role="group" aria-label="View Mode Switcher">
                                <button type="button" class="btn btn-sm rounded-pill px-2.5 py-1 view-btn active" id="btnGridView" title="Grid View">
                                    <i class="bi bi-grid-fill"></i>
                                </button>
                                <button type="button" class="btn btn-sm rounded-pill px-2.5 py-1 view-btn" id="btnListView" title="List View">
                                    <i class="bi bi-view-list"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- ── ACTIVE FILTER CHIPS ── --}}
                    @if(request()->hasAny(['q', 'city', 'min_discount', 'price_range', 'min_price', 'max_price', 'condition', 'brand', 'featured', 'negotiable', 'verified_shop']))
                    <div class="d-flex align-items-center gap-1.5 flex-wrap pt-2 mt-2 border-top">
                        <span class="text-muted fw-700" style="font-size: 0.72rem;">Active Filters:</span>

                        @if(request('q'))
                            <a href="{{ route('category.show', array_merge(['category' => $category->slug], request()->except(['q', 'page']))) }}"
                               class="filter-tag-chip">
                                Query: "{{ request('q') }}" <i class="bi bi-x ms-1"></i>
                            </a>
                        @endif

                        @if(request('min_discount'))
                            <a href="{{ route('category.show', array_merge(['category' => $category->slug], request()->except(['min_discount', 'page']))) }}"
                               class="filter-tag-chip">
                                {{ request('min_discount') }}%+ Off <i class="bi bi-x ms-1"></i>
                            </a>
                        @endif

                        @if(request('price_range'))
                            @php
                                $prLabels = [
                                    'under_500' => 'Under ₹500',
                                    '500_1000' => '₹500 - ₹1,000',
                                    '1000_2500' => '₹1,000 - ₹2,500',
                                    'above_2500' => 'Above ₹2,500'
                                ];
                            @endphp
                            <a href="{{ route('category.show', array_merge(['category' => $category->slug], request()->except(['price_range', 'page']))) }}"
                               class="filter-tag-chip">
                                {{ $prLabels[request('price_range')] ?? request('price_range') }} <i class="bi bi-x ms-1"></i>
                            </a>
                        @endif

                        @if(request('min_price') || request('max_price'))
                            <a href="{{ route('category.show', array_merge(['category' => $category->slug], request()->except(['min_price', 'max_price', 'page']))) }}"
                               class="filter-tag-chip">
                                ₹{{ request('min_price', 0) }} - ₹{{ request('max_price', 'Any') }} <i class="bi bi-x ms-1"></i>
                            </a>
                        @endif

                        @if(request('city'))
                            <a href="{{ route('category.show', array_merge(['category' => $category->slug], request()->except(['city', 'page']))) }}"
                               class="filter-tag-chip">
                                City: {{ request('city') }} <i class="bi bi-x ms-1"></i>
                            </a>
                        @endif

                        @if(request('condition'))
                            <a href="{{ route('category.show', array_merge(['category' => $category->slug], request()->except(['condition', 'page']))) }}"
                               class="filter-tag-chip">
                                Condition: {{ ucfirst(str_replace('_', ' ', request('condition'))) }} <i class="bi bi-x ms-1"></i>
                            </a>
                        @endif

                        @if(request('brand'))
                            <a href="{{ route('category.show', array_merge(['category' => $category->slug], request()->except(['brand', 'page']))) }}"
                               class="filter-tag-chip">
                                Brand: {{ request('brand') }} <i class="bi bi-x ms-1"></i>
                            </a>
                        @endif

                        @if(request('featured'))
                            <a href="{{ route('category.show', array_merge(['category' => $category->slug], request()->except(['featured', 'page']))) }}"
                               class="filter-tag-chip">
                                ⭐ Featured Only <i class="bi bi-x ms-1"></i>
                            </a>
                        @endif

                        @if(request('verified_shop'))
                            <a href="{{ route('category.show', array_merge(['category' => $category->slug], request()->except(['verified_shop', 'page']))) }}"
                               class="filter-tag-chip">
                                ✅ Verified Stores <i class="bi bi-x ms-1"></i>
                            </a>
                        @endif

                        @if(request('negotiable'))
                            <a href="{{ route('category.show', array_merge(['category' => $category->slug], request()->except(['negotiable', 'page']))) }}"
                               class="filter-tag-chip">
                                💬 Negotiable <i class="bi bi-x ms-1"></i>
                            </a>
                        @endif

                        <a href="{{ route('category.show', $category->slug) }}" class="text-danger small fw-700 text-decoration-none ms-1" style="font-size: 0.74rem;">
                            Clear All
                        </a>
                    </div>
                    @endif

                    {{-- ── QUICK FILTER SHORTCUT PILLS ── --}}
                    <div class="quick-pills-bar d-flex gap-1.5 overflow-auto pt-2 mt-1" style="scrollbar-width: none;">
                        <a href="{{ route('category.show', array_merge(['category' => $category->slug], request()->except(['min_discount', 'page']), ['min_discount' => 50])) }}"
                           class="quick-pill {{ request('min_discount') == 50 ? 'active' : '' }}">
                            🔥 50%+ Off
                        </a>
                        <a href="{{ route('category.show', array_merge(['category' => $category->slug], request()->except(['min_discount', 'page']), ['min_discount' => 70])) }}"
                           class="quick-pill {{ request('min_discount') == 70 ? 'active' : '' }}">
                            ⚡ 70%+ Clearance
                        </a>
                        <a href="{{ route('category.show', array_merge(['category' => $category->slug], request()->except(['price_range', 'page']), ['price_range' => 'under_500'])) }}"
                           class="quick-pill {{ request('price_range') == 'under_500' ? 'active' : '' }}">
                            🏷️ Under ₹500
                        </a>
                        <a href="{{ route('category.show', array_merge(['category' => $category->slug], request()->except(['price_range', 'page']), ['price_range' => '500_1000'])) }}"
                           class="quick-pill {{ request('price_range') == '500_1000' ? 'active' : '' }}">
                            🏷️ ₹500 - ₹1,000
                        </a>
                        <a href="{{ route('category.show', array_merge(['category' => $category->slug], request()->except(['verified_shop', 'page']), ['verified_shop' => request('verified_shop') ? null : 1])) }}"
                           class="quick-pill {{ request('verified_shop') ? 'active' : '' }}">
                            ✅ Verified Stores
                        </a>
                        <a href="{{ route('category.show', array_merge(['category' => $category->slug], request()->except(['featured', 'page']), ['featured' => request('featured') ? null : 1])) }}"
                           class="quick-pill {{ request('featured') ? 'active' : '' }}">
                            ⭐ Featured
                        </a>
                    </div>
                </div>

                {{-- ── PRODUCT DISPLAY (GRID & LIST VIEWS) ── --}}
                @if($products->count())
                    
                    {{-- Standard Grid Container --}}
                    <div id="productGridContainer" class="row g-2 g-md-3 g-lg-3 mobile-2-col">
                        @foreach($products as $product)
                        <div class="col-6 col-md-4 col-lg-3">
                            @include('components.product-card', ['product' => $product])
                        </div>
                        @endforeach
                    </div>

                    {{-- List View Container (Hidden by default, toggled via JS) --}}
                    <div id="productListViewContainer" class="d-none d-flex flex-column gap-3">
                        @foreach($products as $product)
                            @php
                                $imgUrl = $product->primary_image_url;
                                $isFeatured = $product->isFeaturedActive();
                                $discountPercent = $product->discount_percent > 0 
                                    ? round($product->discount_percent) 
                                    : ($product->original_price > $product->offer_price 
                                        ? round((($product->original_price - $product->offer_price) / $product->original_price) * 100) 
                                        : 0);
                            @endphp
                            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white" style="border: 1px solid var(--bm-border) !important;">
                                <div class="row g-3 align-items-center">
                                    <div class="col-auto">
                                        <div class="position-relative rounded-3 overflow-hidden" style="width: 120px; height: 120px; background: #f8fafc;">
                                            <a href="{{ route('product.show', $product->slug) }}">
                                                <img src="{{ $imgUrl }}" alt="{{ $product->name }}" class="w-100 h-100" style="object-fit: cover;"
                                                     onerror="this.onerror=null; this.src='{{ asset('images/product-placeholder.svg') }}';">
                                            </a>
                                            @if($discountPercent > 0)
                                                <span class="position-absolute top-0 start-0 m-1.5 badge rounded-pill" style="background: #EF4444; color: #fff; font-size: 0.65rem; font-weight: 800;">
                                                    -{{ $discountPercent }}%
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                            <span class="text-muted small fw-600" style="font-size: 0.75rem;">{{ $product->category->name ?? $category->name }}</span>
                                            @if($product->brand)
                                                <span class="text-muted small">· Brand: <strong>{{ $product->brand }}</strong></span>
                                            @endif
                                            @if($isFeatured)
                                                <span class="badge rounded-pill" style="background: var(--bm-primary-gradient); color: #fff; font-size: 0.68rem; font-weight: 700;">
                                                    ⭐ Featured
                                                </span>
                                            @endif
                                            @if($product->condition)
                                                <span class="badge bg-light text-dark rounded-pill fw-600 border" style="font-size: 0.68rem;">
                                                    {{ ucfirst(str_replace('_', ' ', $product->condition)) }}
                                                </span>
                                            @endif
                                        </div>
                                        <h5 class="fw-800 text-dark mb-1 fs-6">
                                            <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none text-dark">
                                                {{ $product->name }}
                                            </a>
                                        </h5>
                                        @if($product->description)
                                            <p class="text-muted small mb-2 text-truncate d-none d-md-block" style="max-width: 520px; font-size: 0.78rem;">
                                                {{ Str::limit(strip_tags($product->description), 90) }}
                                            </p>
                                        @endif
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            @if($product->shop)
                                                <span class="text-muted small">
                                                    <i class="bi bi-shop text-primary-bm me-1"></i>{{ $product->shop->name }} ({{ $product->shop->city }})
                                                </span>
                                            @endif
                                            @if($product->is_negotiable)
                                                <span class="badge bg-success-subtle text-success rounded-pill fw-700" style="font-size: 0.68rem;">
                                                    <i class="bi bi-chat-dots-fill me-1"></i>Negotiable
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-auto text-md-end border-top border-md-0 pt-2 pt-md-0">
                                        <div class="mb-2">
                                            <div class="fs-5 fw-900 text-dark">₹{{ number_format($product->offer_price) }}</div>
                                            @if($product->original_price > $product->offer_price)
                                                <div class="text-muted text-decoration-line-through small" style="font-size: 0.78rem;">
                                                    ₹{{ number_format($product->original_price) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="d-flex gap-2 justify-content-md-end">
                                            <a href="{{ route('product.show', $product->slug) }}" class="btn btn-sm btn-primary-bm px-3 py-1.5 fw-700">
                                                View Deal
                                            </a>
                                            @if($product->shop && $product->shop->whatsapp)
                                                <a href="{{ $product->shop->whatsapp_url }}" target="_blank"
                                                   onclick="trackLead('whatsapp', {{ $product->shop->id }}, {{ $product->id }})"
                                                   class="btn btn-sm btn-whatsapp px-2.5 py-1.5" title="WhatsApp Chat">
                                                    <i class="bi bi-whatsapp"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- ── PAGINATION ── --}}
                    <div class="mt-4 mt-lg-5 d-flex justify-content-center">
                        {{ $products->links() }}
                    </div>

                @else
                    {{-- ── EMPTY STATE ── --}}
                    <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white" style="border: 1px solid var(--bm-border) !important;">
                        <div class="mb-3" style="font-size: 3.5rem;">🔍</div>
                        <h4 class="fw-800 text-dark mb-2">No deals found matching your filters</h4>
                        <p class="text-muted small mb-4 mx-auto" style="max-width: 480px; line-height: 1.6;">
                            We couldn't find any active clearance items in <strong>{{ $category->name }}</strong> matching your selected criteria. Try removing some filters or exploring other categories.
                        </p>
                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                            <a href="{{ route('category.show', $category->slug) }}" class="btn btn-primary-bm rounded-pill px-4 fw-700">
                                Reset Filters
                            </a>
                            <a href="{{ route('deals') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-600">
                                Browse All Categories
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── 7. MODERN BUYER TRUST & ASSURANCE STRIP ── --}}
        <div class="mt-5 pt-3">
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center h-100" style="border: 1px solid var(--bm-border) !important;">
                        <div class="fs-2 text-primary-bm mb-1">🏬</div>
                        <h6 class="fw-800 text-dark mb-1" style="font-size: 0.9rem;">Inspect In-Person</h6>
                        <p class="text-muted small mb-0" style="font-size: 0.75rem;">Touch and inspect the clearance item at the store before making payment.</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center h-100" style="border: 1px solid var(--bm-border) !important;">
                        <div class="fs-2 text-success mb-1">💬</div>
                        <h6 class="fw-800 text-dark mb-1" style="font-size: 0.9rem;">WhatsApp Direct</h6>
                        <p class="text-muted small mb-0" style="font-size: 0.75rem;">Chat directly with the shopkeeper to verify stock or request details.</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center h-100" style="border: 1px solid var(--bm-border) !important;">
                        <div class="fs-2 text-danger mb-1">🏷️</div>
                        <h6 class="fw-800 text-dark mb-1" style="font-size: 0.9rem;">Steep Markdowns</h6>
                        <p class="text-muted small mb-0" style="font-size: 0.75rem;">Genuine 20% to 80% markdowns on excess store and warehouse surplus.</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center h-100" style="border: 1px solid var(--bm-border) !important;">
                        <div class="fs-2 text-warning mb-1">🛡️</div>
                        <h6 class="fw-800 text-dark mb-1" style="font-size: 0.9rem;">0% Middleman Fees</h6>
                        <p class="text-muted small mb-0" style="font-size: 0.75rem;">Pay directly at the counter via Cash or UPI with zero extra platform fees.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── 8. CATEGORY FAQS ACCORDION ── --}}
        <div class="mt-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 bg-white" style="border: 1px solid var(--bm-border) !important;">
                <div class="text-center mb-4">
                    <span class="badge rounded-pill bg-light text-primary-bm fw-800 px-3 py-1 mb-2 border" style="font-size: 0.72rem;">CLEARANCE SHOPPING GUIDE</span>
                    <h3 class="fw-900 text-dark mb-1">Frequently Asked Questions about {{ $category->name }} Deals</h3>
                    <p class="text-muted small mb-0">Everything you need to know about buying surplus goods on BachatMart</p>
                </div>

                <div class="accordion accordion-flush" id="categoryFaqAccordion">
                    <div class="accordion-item border-bottom py-2">
                        <h2 class="accordion-header" id="faqHeading1">
                            <button class="accordion-button collapsed fw-800 text-dark bg-transparent shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1">
                                Are the items listed under {{ $category->name }} brand new?
                            </button>
                        </h2>
                        <div id="faqCollapse1" class="accordion-collapse collapse" data-bs-parent="#categoryFaqAccordion">
                            <div class="accordion-body text-muted small pt-1" style="line-height: 1.68;">
                                Most products are brand new retail excess inventory, overstocked goods, or end-of-season warehouse liquidations from verified local store owners. Each product page explicitly specifies the item's condition (e.g., Brand New Surplus, Open Box, or Certified Refurbished) so you always know what you are buying.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-bottom py-2">
                        <h2 class="accordion-header" id="faqHeading2">
                            <button class="accordion-button collapsed fw-800 text-dark bg-transparent shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2">
                                How do I purchase or reserve an item from a local shop?
                            </button>
                        </h2>
                        <div id="faqCollapse2" class="accordion-collapse collapse" data-bs-parent="#categoryFaqAccordion">
                            <div class="accordion-body text-muted small pt-1" style="line-height: 1.68;">
                                BachatMart connects you directly with the neighborhood seller. Simply click the <strong>"WhatsApp"</strong> or <strong>"Call Store"</strong> button on any product listing. You can message the storekeeper, ask them to hold the item, visit the store at your convenience, inspect the product in-person, and pay at the counter.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-bottom py-2">
                        <h2 class="accordion-header" id="faqHeading3">
                            <button class="accordion-button collapsed fw-800 text-dark bg-transparent shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3">
                                Can I negotiate the price further with the shopkeeper?
                            </button>
                        </h2>
                        <div id="faqCollapse3" class="accordion-collapse collapse" data-bs-parent="#categoryFaqAccordion">
                            <div class="accordion-body text-muted small pt-1" style="line-height: 1.68;">
                                Items marked with the <strong>"Negotiable"</strong> badge allow for counter offers directly with the merchant. Since store owners want to clear surplus inventory quickly, many sellers are open to friendly discussions, especially if you are purchasing multiple items in-store.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item py-2">
                        <h2 class="accordion-header" id="faqHeading4">
                            <button class="accordion-button collapsed fw-800 text-dark bg-transparent shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse4">
                                Are there any extra delivery or platform charges?
                            </button>
                        </h2>
                        <div id="faqCollapse4" class="accordion-collapse collapse" data-bs-parent="#categoryFaqAccordion">
                            <div class="accordion-body text-muted small pt-1" style="line-height: 1.68;">
                                Zero! BachatMart is completely free for buyers. There are no convenience fees, middleman markups, or payment gateway surcharges. You pay the exact agreed price directly to the shopkeeper at their store.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ── 9. MOBILE OFFCANVAS FILTER DRAWER ── --}}
<div class="offcanvas offcanvas-bottom rounded-top-4" tabindex="-1" id="categoryMobileFilter" style="height: 85vh;">
    <div class="offcanvas-header border-bottom py-3">
        <h5 class="offcanvas-title fw-800 text-dark d-flex align-items-center gap-2 fs-6">
            <i class="bi bi-sliders text-primary-bm"></i> Filter & Sort {{ $category->name }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-4" style="overflow-y: auto;">
        <form method="GET" action="{{ route('category.show', $category->slug) }}">
            
            {{-- Keyword --}}
            <div class="mb-3">
                <label class="form-label small fw-800 text-dark mb-1">Search in {{ $category->name }}</label>
                <input type="text" name="q" class="form-control rounded-3" placeholder="Keyword..." value="{{ request('q') }}">
            </div>

            {{-- Sort --}}
            <div class="mb-3">
                <label class="form-label small fw-800 text-dark mb-1">Sort Deals</label>
                <select name="sort" class="form-select form-select-sm rounded-3 fw-600">
                    <option value="" {{ !request('sort') ? 'selected' : '' }}>Featured Deals First</option>
                    <option value="discount" {{ request('sort') == 'discount' ? 'selected' : '' }}>Highest Discount %</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                </select>
            </div>

            {{-- Min Discount --}}
            <div class="mb-3">
                <label class="form-label small fw-800 text-dark mb-1">Min Discount %</label>
                <select name="min_discount" class="form-select form-select-sm rounded-3 fw-600">
                    <option value="">Any Discount</option>
                    <option value="70" {{ request('min_discount') == '70' ? 'selected' : '' }}>70% or more (Super Deal)</option>
                    <option value="50" {{ request('min_discount') == '50' ? 'selected' : '' }}>50% or more (Half Price)</option>
                    <option value="30" {{ request('min_discount') == '30' ? 'selected' : '' }}>30% or more</option>
                    <option value="15" {{ request('min_discount') == '15' ? 'selected' : '' }}>15% or more</option>
                </select>
            </div>

            {{-- Price Range Presets --}}
            <div class="mb-3">
                <label class="form-label small fw-800 text-dark mb-1">Price Range</label>
                <select name="price_range" class="form-select form-select-sm rounded-3 fw-600">
                    <option value="">All Prices</option>
                    <option value="under_500" {{ request('price_range') == 'under_500' ? 'selected' : '' }}>Under ₹500</option>
                    <option value="500_1000" {{ request('price_range') == '500_1000' ? 'selected' : '' }}>₹500 to ₹1,000</option>
                    <option value="1000_2500" {{ request('price_range') == '1000_2500' ? 'selected' : '' }}>₹1,000 to ₹2,500</option>
                    <option value="above_2500" {{ request('price_range') == 'above_2500' ? 'selected' : '' }}>Above ₹2,500</option>
                </select>
            </div>

            {{-- City --}}
            <div class="mb-3">
                <label class="form-label small fw-800 text-dark mb-1">City / Location</label>
                @if(isset($availableCities) && $availableCities->count() > 0)
                    <select name="city" class="form-select form-select-sm rounded-3 fw-600">
                        <option value="">All Locations</option>
                        @foreach($availableCities as $c)
                            <option value="{{ $c }}" {{ request('city', session('selected_city')) == $c ? 'selected' : '' }}>
                                📍 {{ $c }}
                            </option>
                        @endforeach
                    </select>
                @else
                    <input type="text" name="city" class="form-control form-control-sm rounded-3" placeholder="City..." value="{{ request('city', session('selected_city')) }}">
                @endif
            </div>

            {{-- Toggles --}}
            <div class="mb-4 d-flex flex-column gap-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="featured" value="1" id="m_featured" {{ request('featured') ? 'checked' : '' }}>
                    <label class="form-check-label small fw-700 text-dark" for="m_featured">⭐ Featured Deals Only</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="verified_shop" value="1" id="m_verified" {{ request('verified_shop') ? 'checked' : '' }}>
                    <label class="form-check-label small fw-700 text-dark" for="m_verified">✅ Verified Stores Only</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="negotiable" value="1" id="m_negotiable" {{ request('negotiable') ? 'checked' : '' }}>
                    <label class="form-check-label small fw-700 text-dark" for="m_negotiable">💬 Negotiable Price</label>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary-bm flex-grow-1 rounded-pill fw-700 py-2.5">
                    Apply Filters
                </button>
                <a href="{{ route('category.show', $category->slug) }}" class="btn btn-outline-secondary rounded-pill px-4 fw-600 py-2.5">
                    Reset
                </a>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
/* Modern Category Page Styling */
.category-page {
    background-color: #F8FAFC;
    min-height: 80vh;
}

/* Category Scroller Rail */
.category-rail::-webkit-scrollbar {
    height: 4px;
}
.category-rail::-webkit-scrollbar-thumb {
    background: #CBD5E1;
    border-radius: 9999px;
}

.category-pill-btn {
    font-size: 0.82rem;
    white-space: nowrap;
    border: 1px solid var(--bm-border);
    transition: all 0.2s ease;
}
.category-pill-btn.inactive-pill {
    background: #FFFFFF;
    color: var(--bm-dark);
}
.category-pill-btn.inactive-pill:hover {
    background: #F1F5F9;
    border-color: #CBD5E1;
    color: var(--bm-primary);
    transform: translateY(-1px);
}
.category-pill-btn.active-pill {
    background: var(--bm-primary-gradient);
    color: #FFFFFF !important;
    border-color: transparent;
    box-shadow: 0 4px 12px rgba(255, 87, 34, 0.35);
}

/* Quick Filter Pills */
.quick-pills-bar::-webkit-scrollbar {
    display: none;
}
.quick-pill {
    display: inline-flex;
    align-items: center;
    padding: 0.3rem 0.75rem;
    font-size: 0.74rem;
    font-weight: 700;
    white-space: nowrap;
    border-radius: 9999px;
    background: #F1F5F9;
    color: #475569;
    text-decoration: none;
    border: 1px solid transparent;
    transition: all 0.18s ease;
}
.quick-pill:hover {
    background: #E2E8F0;
    color: var(--bm-dark);
}
.quick-pill.active {
    background: #FFF0EB;
    color: var(--bm-primary);
    border-color: #FFD3C4;
}

/* Filter Tag Chips */
.filter-tag-chip {
    display: inline-flex;
    align-items: center;
    padding: 0.2rem 0.6rem;
    font-size: 0.72rem;
    font-weight: 700;
    border-radius: 9999px;
    background: #FEE2E2;
    color: #991B1B;
    text-decoration: none;
    transition: all 0.15s ease;
}
.filter-tag-chip:hover {
    background: #FECACA;
    color: #7F1D1D;
}

/* View Switcher Button active state */
.view-btn.active {
    background-color: var(--bm-dark);
    color: #FFFFFF;
}
.view-btn:not(.active) {
    background-color: transparent;
    color: var(--bm-muted);
}
.view-btn:not(.active):hover {
    color: var(--bm-dark);
}

/* Hover transitions */
.transition-hover {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.transition-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08) !important;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // View Switcher: Grid vs List
    const btnGrid = document.getElementById('btnGridView');
    const btnList = document.getElementById('btnListView');
    const gridContainer = document.getElementById('productGridContainer');
    const listContainer = document.getElementById('productListViewContainer');

    if (btnGrid && btnList && gridContainer && listContainer) {
        // Check saved preference in localStorage
        const savedView = localStorage.getItem('bm_category_view');
        if (savedView === 'list') {
            activateListView();
        }

        btnGrid.addEventListener('click', function() {
            activateGridView();
            localStorage.setItem('bm_category_view', 'grid');
        });

        btnList.addEventListener('click', function() {
            activateListView();
            localStorage.setItem('bm_category_view', 'list');
        });

        function activateGridView() {
            btnGrid.classList.add('active');
            btnList.classList.remove('active');
            gridContainer.classList.remove('d-none');
            listContainer.classList.add('d-none');
        }

        function activateListView() {
            btnList.classList.add('active');
            btnGrid.classList.remove('active');
            listContainer.classList.remove('d-none');
            gridContainer.classList.add('d-none');
        }
    }
});
</script>
@endpush
@endsection
