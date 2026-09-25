<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'BachatMart')) - Local Surplus Stock Deals</title>
    <meta name="description" content="@yield('meta_description', 'Discover clearance deals and Surplus Stock discounts from local shops near you.')">
    <meta name="keywords" content="@yield('meta_keywords', 'Surplus Stock, clearance sale, local deals, discounts, local shops, bachat mart')">

    {{-- Open Graph --}}
    <meta property="og:title" content="@yield('title', 'BachatMart - Local Surplus Stock Clearance Deals')">
    <meta property="og:description" content="@yield('meta_description', 'Turn Surplus Stock into great local deals.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    {{-- Google Fonts - Montserrat --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,600;1,700&display=swap" rel="stylesheet">

    {{-- Custom Marketplace Design System --}}
    <link href="{{ asset('css/marketplace.css') }}?v={{ file_exists(public_path('css/marketplace.css')) ? filemtime(public_path('css/marketplace.css')) : '2.0' }}" rel="stylesheet">

    @stack('styles')
</head>
<body>

@php
    $currentCity = session('selected_city', request('city'));
    $headerCities = \App\Models\Shop::active()
        ->whereNotNull('city')
        ->where('city', '!=', '')
        ->distinct()
        ->pluck('city')
        ->sort()
        ->values();
@endphp

<header class="market-header">
    <div class="container">
        <div class="market-header__main">
            <a class="market-logo" href="{{ route('home') }}" aria-label="BachatMart home">
                <span class="market-logo__text">Bachat<span>Mart</span></span>
            </a>

            <div class="dropdown market-location d-none d-lg-block">
                <button class="market-location__button dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-geo-alt-fill"></i>
                    <span>{{ $currentCity ? Str::limit($currentCity, 18) : 'All Locations' }}</span>
                    <i class="bi bi-chevron-down ms-1 text-muted" style="font-size: 0.75rem;"></i>
                </button>
                <ul class="dropdown-menu shadow-lg border-0 rounded-4 mt-2 p-2" style="min-width: 230px; max-height: 300px; overflow-y: auto;">
                    <li><h6 class="dropdown-header text-uppercase fw-700 px-3 py-2" style="font-size: 0.7rem; letter-spacing: 0.08em; color: var(--bm-muted);">Select City</h6></li>
                    <li>
                        <a class="dropdown-item py-2 px-3 rounded-3 {{ !$currentCity ? 'active fw-bold' : '' }}" href="{{ route('set-city', ['city' => 'all']) }}">
                            <i class="bi bi-globe me-2 text-muted"></i>All Cities (India)
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    @foreach($headerCities as $city)
                        <li>
                            <a class="dropdown-item py-2 px-3 rounded-3 {{ $currentCity === $city ? 'active fw-bold' : '' }}" href="{{ route('set-city', ['city' => $city]) }}">
                                <i class="bi bi-geo-alt me-2 text-muted"></i>{{ $city }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <form class="market-search d-none d-lg-flex" action="{{ route('search') }}" method="GET">
                @if($currentCity)
                    <input type="hidden" name="city" value="{{ $currentCity }}">
                @endif
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Search products, brands, clearance lots..." aria-label="Search clearance stock">
                <button type="submit" aria-label="Search">
                    <i class="bi bi-search"></i>
                </button>
            </form>

            <div class="market-header__actions d-none d-lg-flex">
                <a class="market-action" href="{{ route('deals') }}" aria-label="All Deals">
                    <i class="bi bi-lightning-charge-fill text-primary-bm"></i>
                    <span>Deals</span>
                </a>

                <a class="market-action" href="{{ route('shops.index') }}" aria-label="Local Shops">
                    <i class="bi bi-shop"></i>
                    <span>Shops</span>
                </a>

                @auth
                    <div class="dropdown market-profile">
                        <button class="market-profile__button dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle text-primary-bm"></i>
                            <span>{{ Str::limit(auth()->user()->name, 14) }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2 p-2">
                            <li><a class="dropdown-item py-2 px-3 rounded-3" href="{{ route('home') }}"><i class="bi bi-house me-2"></i>Home</a></li>
                            @if(auth()->user()->isSeller())
                                <li><a class="dropdown-item py-2 px-3 rounded-3 fw-600 text-primary-bm" href="{{ route('seller.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Seller Dashboard</a></li>
                            @elseif(auth()->user()->isAdmin())
                                <li><a class="dropdown-item py-2 px-3 rounded-3 fw-600 text-primary-bm" href="{{ route('admin.dashboard') }}"><i class="bi bi-shield-lock me-2"></i>Admin Panel</a></li>
                            @endif
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 px-3 rounded-3 text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a class="market-action" href="{{ route('login') }}">
                        <i class="bi bi-person-circle"></i>
                        <span>Login</span>
                    </a>
                @endauth

                @php
                    $sellTarget = route('register.seller');
                    if (auth()->check() && auth()->user()->isSeller()) {
                        $sellTarget = route('seller.products.create');
                    }
                @endphp

                <a class="market-sell-btn" href="{{ $sellTarget }}">
                    <i class="bi bi-plus-circle-fill"></i>
                    <span>SELL STOCK</span>
                </a>
            </div>

            <div class="market-header__mobile-tools d-lg-none ms-auto">
                @php
                    $sellTargetMobile = route('register.seller');
                    if (auth()->check() && auth()->user()->isSeller()) {
                        $sellTargetMobile = route('seller.products.create');
                    }
                @endphp
                <a class="market-sell-btn py-2 px-3" href="{{ $sellTargetMobile }}" style="font-size: 0.82rem;">
                    <i class="bi bi-plus-lg"></i>
                    <span>SELL</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Mobile Search Bar --}}
    <div class="market-header__search-mobile d-lg-none">
        <div class="container">
            <form action="{{ route('search') }}" method="GET" class="market-search">
                @if($currentCity)
                    <input type="hidden" name="city" value="{{ $currentCity }}">
                @endif
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Search products, shops or clearance..." aria-label="Search">
                <button type="submit" aria-label="Search">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </div>

    {{-- Horizontal Category Carousel Bar --}}
    <div class="market-category-strip">
        <div class="container position-relative">
            <div class="market-category-wrapper" id="marketCategoryWrapper">
                <div class="market-category-row" id="marketCategoryRow">
                    <a class="market-all-category" href="{{ route('deals') }}">
                        <i class="bi bi-grid-fill"></i>
                        <span>All Deals</span>
                    </a>

                    @php $categoryList = \App\Models\Category::active()->orderBy('sort_order')->get(); @endphp
                    @foreach($categoryList as $category)
                        <a class="market-category-pill {{ request()->routeIs('category.show') && request()->route('category') && request()->route('category')->slug === $category->slug ? 'active' : '' }}"
                           href="{{ route('category.show', $category->slug) }}">
                            {{ $category->icon ?? '•' }} {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</header>

{{-- Flash Messages --}}
@if(session('success') || session('error') || session('info') || session('warning'))
<div class="container mt-3">
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center alert-dismissible fade show rounded-4 shadow-sm border-0" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center alert-dismissible fade show rounded-4 shadow-sm border-0" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info d-flex align-items-center alert-dismissible fade show rounded-4 shadow-sm border-0" role="alert">
            <i class="bi bi-info-circle-fill me-2 fs-5"></i>
            <div>{{ session('info') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning d-flex align-items-center alert-dismissible fade show rounded-4 shadow-sm border-0" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
            <div>{{ session('warning') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>
@endif

{{-- Main Body Content --}}
@yield('content')

{{-- ── FOOTER ── --}}
<footer class="footer-main" style="background:#0F172A;color:#94A3B8;padding:4.5rem 0 2.5rem;margin-top:5rem;border-top:1px solid #1E293B">
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="fs-4 fw-900 text-white">Bachat<span style="color:var(--bm-primary)">Mart</span></span>
                    <span class="badge rounded-pill" style="background: rgba(255,87,34,0.15); color: #FF7A45; font-size: 0.72rem; font-weight: 700; border: 1px solid rgba(255,87,34,0.3);">LOCAL CLEARANCE</span>
                </div>
                <p class="small" style="color:#94A3B8;max-width:340px;line-height:1.7">
                    The hyper-local clearance marketplace. Helping neighborhood shopkeepers liquidate surplus inventory while empowering local shoppers with massive direct savings.
                </p>
                <div class="d-flex gap-3 mt-4">
                    <a href="#" class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;color:#CBD5E1;border-color:#334155;"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;color:#CBD5E1;border-color:#334155;"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;color:#CBD5E1;border-color:#334155;"><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-6">
                <div class="fw-700 text-white mb-3" style="font-size:0.95rem">Explore Deals</div>
                <ul class="list-unstyled small d-flex flex-column gap-2">
                    <li><a href="{{ route('home') }}" class="text-secondary text-decoration-none hover-primary">Home</a></li>
                    <li><a href="{{ route('deals') }}" class="text-secondary text-decoration-none hover-primary">All Clearance Deals</a></li>
                    <li><a href="{{ route('deals') }}?sort=discount" class="text-secondary text-decoration-none hover-primary">Top Discounts</a></li>
                    <li><a href="{{ route('shops.index') }}" class="text-secondary text-decoration-none hover-primary">Browse Shops</a></li>
                    <li><a href="{{ route('search') }}" class="text-secondary text-decoration-none hover-primary">Search Catalog</a></li>
                </ul>
            </div>

            <div class="col-lg-2 col-6">
                <div class="fw-700 text-white mb-3" style="font-size:0.95rem">For Retailers</div>
                <ul class="list-unstyled small d-flex flex-column gap-2">
                    <li><a href="{{ route('register.seller') }}" class="text-secondary text-decoration-none hover-primary">Sell Surplus Stock</a></li>
                    <li><a href="{{ route('login') }}" class="text-secondary text-decoration-none hover-primary">Seller Login</a></li>
                    <li><a href="{{ route('register.seller') }}" class="text-secondary text-decoration-none hover-primary">List Products for ₹12</a></li>
                    <li><a href="{{ route('home') }}#how-it-works" class="text-secondary text-decoration-none hover-primary">How It Works</a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="fw-700 text-white mb-3" style="font-size:0.95rem">Direct In-Store Model</div>
                <div class="p-3 rounded-4" style="background:#1E293B;border:1px solid #334155">
                    <div class="d-flex align-items-start gap-2 mb-2">
                        <i class="bi bi-shield-check text-success fs-5 flex-shrink-0"></i>
                        <span class="small text-light">
                            <strong>Inspect Before You Pay:</strong> Connect directly with local store owners on WhatsApp or Call, inspect the product in person, and complete your purchase in-store.
                        </span>
                    </div>
                    <div class="d-flex gap-2 flex-wrap mt-3">
                        <span class="badge rounded-pill bg-dark text-light border border-secondary px-3 py-1">0% Commission</span>
                        <span class="badge rounded-pill bg-dark text-light border border-secondary px-3 py-1">Direct Store Payment</span>
                        <span class="badge rounded-pill bg-dark text-light border border-secondary px-3 py-1">100% Genuine</span>
                    </div>
                </div>
            </div>
        </div>

        <hr style="border-color:#334155;margin:2.5rem 0 1.5rem">

        <div class="row align-items-center">
            <div class="col-md-6 small text-muted">
                © {{ date('Y') }} BachatMart. Crafted with care for local businesses and bargain shoppers.
            </div>
            <div class="col-md-6 text-md-end small text-muted mt-2 mt-md-0">
                Local Clearance Deals & Surplus Stock Liquidation Platform
            </div>
        </div>
    </div>
</footer>

{{-- Bootstrap 5 JS Bundle --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

{{-- Lead Tracking Script --}}
<script>
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '';

function trackLead(type, shopId, productId = null) {
    if (!shopId) return;
    fetch('{{ route("lead.track") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            type: type,
            shop_id: shopId,
            product_id: productId
        })
    }).catch(err => {
        // silent fail
    });
}
</script>

@stack('scripts')
</body>
</html>
