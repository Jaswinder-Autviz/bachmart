<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Seller Dashboard') - BachatMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/marketplace.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary: #FF6B35;
            --primary-dark: #E85C26;
            --sidebar-bg: #1a202c;
            --sidebar-text: #a0aec0;
            --sidebar-active: #FF6B35;
            --sidebar-width: 260px;
        }
        body { font-family: 'Inter', sans-serif; background: #f7f8fa; }
        .seller-sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0; left: 0;
            overflow-y: auto;
            padding-bottom: 0.75rem;
            z-index: 100;
            transition: transform .3s;
        }
        .seller-sidebar .brand {
            padding: 1.5rem 1.5rem 1rem;
            font-weight: 800;
            font-size: 1.4rem;
            color: #fff;
            border-bottom: 1px solid #2d3748;
        }
        .seller-sidebar .brand span { color: var(--primary); }
        .seller-sidebar .nav-section {
            padding: 1rem 0.75rem 0.5rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #4a5568;
            font-weight: 600;
        }
        .seller-sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 0.65rem 1rem;
            border-radius: 8px;
            margin: 2px 0.5rem;
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all .2s;
        }
        .seller-sidebar .nav-link:hover { background: #2d3748; color: #fff; }
        .seller-sidebar .nav-link.active { background: var(--primary); color: #fff; }
        .seller-sidebar .nav-link i { font-size: 1.1rem; width: 20px; }
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }
        .top-bar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.8rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
        }
        .top-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1a202c; }
        .page-content { padding: 1.5rem; }
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            border: none;
        }
        .stat-card .stat-value { font-size: 1.8rem; font-weight: 800; color: #1a202c; }
        .stat-card .stat-label { font-size: 0.8rem; color: #718096; font-weight: 500; }
        .stat-card .stat-icon {
            width: 44px; height: 44px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
        }
        .btn-primary-bm {
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all .2s;
        }
        .btn-primary-bm:hover { background: var(--primary-dark); color: #fff; }
        .card-bm {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            border: none;
        }
        .badge-status-approved { background: #c6f6d5; color: #276749; }
        .badge-status-pending { background: #feebc8; color: #7b341e; }
        .badge-status-rejected { background: #fed7d7; color: #9b2c2c; }
        .badge-status-draft { background: #e2e8f0; color: #4a5568; }
        .badge-status-featured { background: #bee3f8; color: #2c5282; }
        .table-hover tbody tr:hover { background: #f7fafc; }
        @media (max-width: 768px) {
            .seller-sidebar { transform: translateX(-100%); }
            .seller-sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }

        /* ── Pagination Styling & SVG Guard ── */
        nav[role="navigation"] svg,
        .pagination svg {
            width: 1rem !important;
            height: 1rem !important;
            max-width: 1rem !important;
            max-height: 1rem !important;
            display: inline-block !important;
            vertical-align: middle !important;
        }
        .pagination {
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 4px !important;
            margin-bottom: 0 !important;
            align-items: center !important;
        }
        .pagination .page-item .page-link {
            border-radius: 8px !important;
            margin: 0 2px !important;
            color: #4A5568 !important;
            border: 1px solid #E2E8F0 !important;
            padding: 0.38rem 0.8rem !important;
            font-weight: 600 !important;
            font-size: 0.875rem !important;
            transition: all 0.2s ease !important;
        }
        .pagination .page-item.active .page-link {
            background-color: #FF5722 !important;
            border-color: #FF5722 !important;
            color: #FFFFFF !important;
            box-shadow: 0 2px 8px rgba(255, 87, 34, 0.3) !important;
        }
        .pagination .page-item .page-link:hover {
            background-color: #FFF3E0 !important;
            border-color: #FF5722 !important;
            color: #FF5722 !important;
        }
        .pagination .page-item.disabled .page-link {
            color: #A0AEC0 !important;
            background-color: #F7FAFC !important;
            border-color: #E2E8F0 !important;
        }

        /* ── Toastr Custom Modern Styling ── */
        #toast-container > div {
            opacity: 0.98 !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.18) !important;
            font-family: 'Inter', sans-serif !important;
            font-size: 0.875rem !important;
            padding: 15px 15px 15px 50px !important;
        }
        #toast-container > .toast-success { background-color: #10B981 !important; }
        #toast-container > .toast-error { background-color: #EF4444 !important; }
        #toast-container > .toast-info { background-color: #3B82F6 !important; }
        #toast-container > .toast-warning { background-color: #F59E0B !important; }
    </style>
    @stack('styles')
</head>
<body>

{{-- Sidebar --}}
<aside class="seller-sidebar" id="sellerSidebar">
    <div class="brand">Bachat<span>Mart</span></div>

    {{-- Seller info --}}
    <div class="px-3 py-3 border-bottom" style="border-color:#2d3748 !important">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ auth()->user()->avatar_url }}" class="rounded-circle" width="38" height="38" alt="Avatar">
            <div>
                <div style="color:#fff;font-size:.85rem;font-weight:600">{{ Str::limit(auth()->user()->name, 20) }}</div>
                <div style="color:#718096;font-size:.75rem">{{ auth()->user()->shop?->name ?? 'No Shop' }}</div>
            </div>
        </div>
    </div>

    <nav class="mt-2">
        <div class="nav-section">Main</div>
        <a href="{{ route('seller.dashboard') }}" class="nav-link {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
        <a href="{{ route('seller.shop.edit') }}" class="nav-link {{ request()->routeIs('seller.shop.*') ? 'active' : '' }}">
            <i class="bi bi-shop"></i> My Shop
        </a>

        <div class="nav-section">Surplus Stock Inventory</div>
        <a href="{{ route('seller.products.index') }}" class="nav-link {{ (request()->routeIs('seller.products.index') || request()->routeIs('seller.products.show') || request()->routeIs('seller.products.edit')) ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> <span>My Surplus Stock</span>
            @if(auth()->user()->shop)
                <span class="badge ms-auto rounded-pill" style="background:#2d3748;color:#a0aec0;font-size:0.75rem">{{ auth()->user()->shop->products()->count() }}</span>
            @endif
        </a>
        <a href="{{ route('seller.products.create') }}" class="nav-link {{ request()->routeIs('seller.products.create') ? 'active' : '' }}">
            <i class="bi bi-plus-circle-fill text-warning"></i> <span>List Surplus Stock</span>
            <span class="badge bg-warning text-dark ms-auto" style="font-size:0.65rem;font-weight:700">NEW</span>
        </a>

        <div class="nav-section">Insights</div>
        <a href="{{ route('seller.leads') }}" class="nav-link {{ request()->routeIs('seller.leads') ? 'active' : '' }}">
            <i class="bi bi-people"></i> In-Store Leads
        </a>
        <a href="{{ route('seller.analytics') }}" class="nav-link {{ request()->routeIs('seller.analytics') ? 'active' : '' }}">
            <i class="bi bi-bar-chart"></i> Analytics
        </a>

        <div class="nav-section">Account</div>
        <a href="{{ route('seller.subscription.index') }}" class="nav-link {{ request()->routeIs('seller.subscription.*') ? 'active' : '' }}">
            <i class="bi bi-credit-card"></i> Subscription
        </a>
        <a href="{{ route('seller.notifications') }}" class="nav-link {{ request()->routeIs('seller.notifications') ? 'active' : '' }}">
            <i class="bi bi-bell"></i> Notifications
            @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="badge ms-auto" style="background:var(--primary)">{{ auth()->user()->unreadNotifications->count() }}</span>
            @endif
        </a>
        <a href="{{ route('seller.settings') }}" class="nav-link">
            <i class="bi bi-gear"></i> Settings
        </a>
        <hr style="border-color:#2d3748;margin:0.5rem 1rem">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-link w-100 text-start border-0" style="background:none;color:#e53e3e">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </nav>
</aside>

{{-- Main Content --}}
<div class="main-content">
    <div class="top-bar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-outline-secondary d-md-none" onclick="document.getElementById('sellerSidebar').classList.toggle('show')">
                <i class="bi bi-list fs-5"></i>
            </button>
            <span class="page-title">@yield('page-title', 'Dashboard')</span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                <i class="bi bi-box-arrow-up-right me-1"></i>View Site
            </a>
            <a href="{{ route('seller.products.create') }}" class="btn btn-primary-bm btn-sm px-3 rounded-pill">
                <i class="bi bi-plus-circle-fill me-1"></i>+ Add Surplus Stock
            </a>
        </div>
    </div>

    {{-- Flash --}}
    <div class="px-3 pt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <div class="page-content">
        @yield('content')
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "4500",
        "extendedTimeOut": "1500",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    $(document).ready(function() {
        @if(session('success'))
            toastr.success("{{ addslashes(session('success')) }}", "Success");
        @endif
        @if(session('error'))
            toastr.error("{{ addslashes(session('error')) }}", "Notice");
        @endif
        @if(session('info'))
            toastr.info("{{ addslashes(session('info')) }}", "Information");
        @endif
        @if(session('warning'))
            toastr.warning("{{ addslashes(session('warning')) }}", "Warning");
        @endif
        @if($errors->any())
            @foreach($errors->all() as $error)
                toastr.error("{{ addslashes($error) }}", "Validation Error");
            @endforeach
        @endif
    });
</script>
@stack('scripts')
</body>
</html>
