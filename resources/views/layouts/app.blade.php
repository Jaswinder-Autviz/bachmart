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
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Custom Marketplace Design System --}}
    <link href="{{ asset('css/marketplace.css') }}?v={{ file_exists(public_path('css/marketplace.css')) ? filemtime(public_path('css/marketplace.css')) : '1.1' }}" rel="stylesheet">
    <style>
        .market-category-row {
            scrollbar-width: none !important;
            -ms-overflow-style: none !important;
        }
        .market-category-row::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }
    </style>

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
                    <span>{{ $currentCity ? Str::limit($currentCity, 18) : 'India' }}</span>
                    <i class="bi bi-chevron-down"></i>
                </button>
                <ul class="dropdown-menu shadow border-0 rounded-3 mt-2" style="min-width: 220px; max-height: 280px; overflow-y: auto;">
                    <li><h6 class="dropdown-header text-uppercase fw-700" style="font-size:.7rem;letter-spacing:1px">Choose Location</h6></li>
                    <li>
                        <a class="dropdown-item py-2 {{ !$currentCity ? 'active fw-bold' : '' }}" href="{{ route('set-city', ['city' => 'all']) }}">
                            <i class="bi bi-globe me-2 text-muted"></i>All Cities
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    @foreach($headerCities as $city)
                        <li>
                            <a class="dropdown-item py-2 {{ $currentCity === $city ? 'active fw-bold' : '' }}" href="{{ route('set-city', ['city' => $city]) }}">
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
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Search products, shops or deals" aria-label="Search products, shops or deals">
                <button type="submit" aria-label="Search">
                    <i class="bi bi-search"></i>
                </button>
            </form>

            <div class="market-header__actions d-none d-lg-flex">
                <a class="market-action" href="{{ route('home') }}" aria-label="Wishlist">
                    <i class="bi bi-heart"></i>
                    <span>Wishlist</span>
                </a>

                @auth
                    <div class="dropdown market-profile">
                        <button class="market-profile__button dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person"></i>
                            <span>{{ Str::limit(auth()->user()->name, 12) }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                            <li><a class="dropdown-item" href="{{ route('home') }}"><i class="bi bi-house me-2"></i>Home</a></li>
                            @if(auth()->user()->isSeller())
                                <li><a class="dropdown-item" href="{{ route('seller.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Seller Dashboard</a></li>
                            @elseif(auth()->user()->isAdmin())
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-shield-lock me-2"></i>Admin Panel</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a class="market-action market-action--login" href="{{ route('login') }}">
                        <i class="bi bi-person"></i>
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
                    <i class="bi bi-plus-lg"></i>
                    <span>SELL</span>
                </a>
            </div>

            <div class="market-header__mobile-tools d-lg-none ms-auto">
                @php
                    $sellTargetMobile = route('register.seller');
                    if (auth()->check() && auth()->user()->isSeller()) {
                        $sellTargetMobile = route('seller.products.create');
                    }
                @endphp
                <a class="market-sell-btn market-sell-btn--mobile" href="{{ $sellTargetMobile }}">
                    <i class="bi bi-plus-lg"></i>
                    <span>SELL</span>
                </a>
            </div>
        </div>
    </div>

    <div class="market-header__search-mobile d-lg-none">
        <div class="container">
            <form action="{{ route('search') }}" method="GET" class="market-search market-search--mobile">
                @if($currentCity)
                    <input type="hidden" name="city" value="{{ $currentCity }}">
                @endif
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Search products, shops or deals" aria-label="Search products, shops or deals">
                <button type="submit" aria-label="Search">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="market-category-strip">
        <div class="container position-relative">
            <div class="market-category-wrapper" id="marketCategoryWrapper">
                <button type="button" class="market-category-nav-btn market-category-nav-btn--prev d-none d-md-flex" id="catScrollPrev" aria-label="Previous categories">
                    <i class="bi bi-chevron-left"></i>
                </button>

                <div class="market-category-row" id="marketCategoryRow">
                    <a class="market-all-category" href="{{ route('deals') }}">
                        <i class="bi bi-list"></i>
                        <span>All Categories</span>
                    </a>

                    @php $categoryList = \App\Models\Category::active()->orderBy('sort_order')->get(); @endphp
                    @foreach($categoryList as $category)
                        <a class="market-category-pill {{ request()->routeIs('category.show') && request()->route('category') && request()->route('category')->slug === $category->slug ? 'active' : '' }}"
                           href="{{ route('category.show', $category->slug) }}">
                            {{ $category->icon ?? '•' }} {{ $category->name }}
                        </a>
                    @endforeach
                </div>

                <button type="button" class="market-category-nav-btn market-category-nav-btn--next d-none d-md-flex" id="catScrollNext" aria-label="Next categories">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</header>

{{-- Flash Messages --}}
@if(session('success') || session('error') || session('info') || session('warning'))
<div class="container mt-3">
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center alert-dismissible fade show rounded-3 shadow-sm border-0" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center alert-dismissible fade show rounded-3 shadow-sm border-0" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info d-flex align-items-center alert-dismissible fade show rounded-3 shadow-sm border-0" role="alert">
            <i class="bi bi-info-circle-fill me-2 fs-5"></i>
            <div>{{ session('info') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning d-flex align-items-center alert-dismissible fade show rounded-3 shadow-sm border-0" role="alert">
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
<footer class="footer-main" style="background:#0F172A;color:#94A3B8;padding:4rem 0 2rem;margin-top:4rem;border-top:1px solid #1E293B">
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="fs-4 fw-800 text-white">Bachat<span style="color:var(--bm-primary)">Mart</span></span>
                    <span class="badge-clearance-nav">LOCAL DEALS</span>
                </div>
                <p class="small mt-2" style="color:#94A3B8;max-width:340px;line-height:1.6">
                    The local Surplus Stock clearance platform. Helping shopkeepers turn unsold stock into quick cash while local shoppers discover massive in-store bargains.
                </p>
                <div class="d-flex gap-3 mt-3">
                    <a href="#" class="text-white fs-5"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white fs-5"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white fs-5"><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-6">
                <div class="fw-700 text-white mb-3" style="font-size:.95rem">Explore Deals</div>
                <ul class="list-unstyled small d-flex flex-column gap-2">
                    <li><a href="{{ route('home') }}" class="text-secondary text-decoration-none hover-primary">Home</a></li>
                    <li><a href="{{ route('deals') }}" class="text-secondary text-decoration-none hover-primary">All Clearance Deals</a></li>
                    <li><a href="{{ route('deals') }}?sort=discount" class="text-secondary text-decoration-none hover-primary">Biggest Discounts</a></li>
                    <li><a href="{{ route('shops.index') }}" class="text-secondary text-decoration-none hover-primary">Local Shops</a></li>
                    <li><a href="{{ route('search') }}" class="text-secondary text-decoration-none hover-primary">Search Catalog</a></li>
                </ul>
            </div>

            <div class="col-lg-2 col-6">
                <div class="fw-700 text-white mb-3" style="font-size:.95rem">For Shopkeepers</div>
                <ul class="list-unstyled small d-flex flex-column gap-2">
                    <li><a href="{{ route('register.seller') }}" class="text-secondary text-decoration-none hover-primary">Sell Your Surplus Stock</a></li>
                    <li><a href="{{ route('login') }}" class="text-secondary text-decoration-none hover-primary">Seller Login</a></li>
                    <li><a href="{{ route('register.seller') }}" class="text-secondary text-decoration-none hover-primary">Free Shop Registration</a></li>
                    <li><a href="{{ route('home') }}#how-it-works" class="text-secondary text-decoration-none hover-primary">How Listing Works</a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="fw-700 text-white mb-3" style="font-size:.95rem">How BachatMart Works</div>
                <div class="p-3 rounded-3" style="background:#1E293B;border:1px solid #334155">
                    <div class="d-flex align-items-start gap-2 mb-2">
                        <i class="bi bi-info-circle text-warning fs-5 flex-shrink-0"></i>
                        <span class="small text-light">
                            <strong>Direct In-Store Pickup:</strong> BachatMart is not an online e-commerce shop. Customers contact local shopkeepers and purchase items in-store.
                        </span>
                    </div>
                    <div class="d-flex gap-2 flex-wrap mt-2">
                        <span class="badge bg-secondary text-light">No online delivery</span>
                        <span class="badge bg-secondary text-light">Direct shop payment</span>
                        <span class="badge bg-secondary text-light">Instant in-person deals</span>
                    </div>
                </div>
            </div>
        </div>

        <hr style="border-color:#334155;margin:2rem 0">

        <div class="row align-items-center">
            <div class="col-md-6 small text-muted">
                © {{ date('Y') }} BachatMart Inc. All rights reserved.
            </div>
            <div class="col-md-6 text-md-end small text-muted">
                Local Surplus Stock Deals & In-Store Inventory Clearance
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
        // silent fail - non-blocking
    });
}

// Category Strip Horizontal Scroll & Drag Functionality
document.addEventListener('DOMContentLoaded', function() {
    const categoryRow = document.getElementById('marketCategoryRow');
    const categoryWrapper = document.getElementById('marketCategoryWrapper');
    const prevBtn = document.getElementById('catScrollPrev');
    const nextBtn = document.getElementById('catScrollNext');

    if (!categoryRow) return;

    // Update scroll buttons and edge fades
    function updateScrollIndicators() {
        const maxScroll = categoryRow.scrollWidth - categoryRow.clientWidth;
        const currentScroll = categoryRow.scrollLeft;
        const hasScrollLeft = currentScroll > 8;
        const hasScrollRight = currentScroll < (maxScroll - 8);

        if (categoryWrapper) {
            categoryWrapper.classList.toggle('has-scroll-left', hasScrollLeft);
            categoryWrapper.classList.toggle('has-scroll-right', hasScrollRight && maxScroll > 8);
        }

        if (prevBtn) {
            prevBtn.classList.toggle('is-visible', hasScrollLeft);
        }
        if (nextBtn) {
            nextBtn.classList.toggle('is-visible', hasScrollRight && maxScroll > 8);
        }
    }

    // Scroll button click listeners
    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            categoryRow.scrollBy({ left: -260, behavior: 'smooth' });
        });
    }
    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            categoryRow.scrollBy({ left: 260, behavior: 'smooth' });
        });
    }

    // Mouse wheel horizontal scroll without Shift key
    categoryRow.addEventListener('wheel', function(e) {
        if (Math.abs(e.deltaY) > Math.abs(e.deltaX) && e.deltaY !== 0) {
            e.preventDefault();
            categoryRow.scrollLeft += e.deltaY;
        }
    }, { passive: false });

    // Click & Drag to scroll for desktop mouse users
    let isDown = false;
    let startX = 0;
    let scrollStart = 0;
    let isDragging = false;

    categoryRow.addEventListener('mousedown', function(e) {
        if (e.button !== 0) return;
        isDown = true;
        isDragging = false;
        startX = e.pageX - categoryRow.offsetLeft;
        scrollStart = categoryRow.scrollLeft;
    });

    window.addEventListener('mouseup', function() {
        if (isDown) {
            isDown = false;
            categoryRow.classList.remove('is-dragging');
            setTimeout(function() { isDragging = false; }, 50);
        }
    });

    categoryRow.addEventListener('mousemove', function(e) {
        if (!isDown) return;
        const x = e.pageX - categoryRow.offsetLeft;
        const walk = (x - startX);
        if (Math.abs(walk) > 5) {
            isDragging = true;
            categoryRow.classList.add('is-dragging');
            e.preventDefault();
            categoryRow.scrollLeft = scrollStart - walk;
        }
    });

    // Prevent accidental link navigation when dragging
    categoryRow.addEventListener('click', function(e) {
        if (isDragging) {
            e.preventDefault();
            e.stopPropagation();
        }
    }, true);

    // Auto-scroll active category into view smoothly
    const activePill = categoryRow.querySelector('.market-category-pill.active');
    if (activePill) {
        setTimeout(function() {
            const offsetLeft = activePill.offsetLeft;
            const pillWidth = activePill.offsetWidth;
            const rowWidth = categoryRow.offsetWidth;
            categoryRow.scrollTo({
                left: offsetLeft - (rowWidth / 2) + (pillWidth / 2),
                behavior: 'smooth'
            });
            setTimeout(updateScrollIndicators, 300);
        }, 120);
    }

    categoryRow.addEventListener('scroll', updateScrollIndicators, { passive: true });
    window.addEventListener('resize', updateScrollIndicators);
    setTimeout(updateScrollIndicators, 150);
});
</script>

@stack('scripts')
</body>
</html>
