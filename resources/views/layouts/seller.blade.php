<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Seller Dashboard') - BachatMart</title>
    
    {{-- Bootstrap 5 & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    
    {{-- Google Fonts - Montserrat --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,600;1,700&display=swap" rel="stylesheet">

    {{-- Marketplace CSS --}}
    <link href="{{ asset('css/marketplace.css') }}?v={{ file_exists(public_path('css/marketplace.css')) ? filemtime(public_path('css/marketplace.css')) : '2.2' }}" rel="stylesheet">

    <style>
        :root {
            --primary: #FF5722;
            --primary-dark: #E64A19;
            --sidebar-bg: #0F172A;
            --sidebar-text: #94A3B8;
            --sidebar-active: #FF5722;
            --sidebar-width: 260px;
        }
        html, body {
            overflow-x: hidden !important;
            max-width: 100vw;
        }
        body { 
            font-family: 'Montserrat', sans-serif !important; 
            background: #F8FAFC; 
            color: #0F172A;
            margin: 0;
            padding: 0;
        }
        
        /* ── Modern Dark Sidebar with Hidden Scrollbar ── */
        .seller-sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0; left: 0;
            overflow-y: auto;
            padding-bottom: 2rem;
            z-index: 1050;
            transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1);
            border-right: 1px solid #1E293B;
            scrollbar-width: none !important;
            -ms-overflow-style: none !important;
        }
        .seller-sidebar::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
        }
        .seller-sidebar .brand {
            padding: 1.4rem 1.4rem 1rem;
            font-weight: 900;
            font-size: 1.45rem;
            color: #fff;
            letter-spacing: -0.03em;
            border-bottom: 1px solid #1E293B;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .seller-sidebar .brand span { color: var(--primary); }
        .seller-sidebar .nav-section {
            padding: 1.1rem 1rem 0.35rem;
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748B;
            font-weight: 700;
        }
        .seller-sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 0.62rem 1rem;
            border-radius: 12px;
            margin: 3px 0.65rem;
            font-size: 0.88rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .seller-sidebar .nav-link:hover { 
            background: #1E293B; 
            color: #FFFFFF; 
        }
        .seller-sidebar .nav-link.active { 
            background: var(--bm-primary-gradient, #FF5722); 
            color: #FFFFFF; 
            box-shadow: 0 4px 14px rgba(255, 87, 34, 0.35);
        }
        .seller-sidebar .nav-link i { font-size: 1.05rem; width: 20px; }
        
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background: #F8FAFC;
            transition: margin-left 0.28s ease;
        }
        .top-bar {
            background: #FFFFFF;
            border-bottom: 1px solid #E2E8F0;
            padding: 0.85rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
        }
        .top-bar .page-title { 
            font-size: 1.15rem; 
            font-weight: 800; 
            color: #0F172A; 
            letter-spacing: -0.02em;
        }
        .page-content { padding: 1.5rem; }
        
        /* ── Sidebar Backdrop on Mobile ── */
        .sidebar-backdrop {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1040;
            display: none;
            opacity: 0;
            transition: opacity 0.25s ease;
        }
        .sidebar-backdrop.show {
            display: block;
            opacity: 1;
        }

        /* ── Status Badges ── */
        .badge-status-approved, .badge-status-active { background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; }
        .badge-status-pending { background: #FFFBEB; color: #92400E; border: 1px solid #FDE68A; }
        .badge-status-rejected { background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; }
        .badge-status-draft { background: #F1F5F9; color: #475569; border: 1px solid #E2E8F0; }
        .badge-status-featured { background: #EFF6FF; color: #1E40AF; border: 1px solid #BFDBFE; }

        @media (max-width: 991.98px) {
            .seller-sidebar { transform: translateX(-100%); }
            .seller-sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .page-content { padding: 1rem; }
            .top-bar { padding: 0.75rem 1rem; }
        }

        #toast-container > div {
            border-radius: 14px !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
            font-family: 'Montserrat', sans-serif !important;
            font-size: 0.88rem !important;
            font-weight: 600 !important;
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- Sidebar Backdrop for Mobile --}}
<div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSellerSidebar()"></div>

{{-- Sidebar --}}
<aside class="seller-sidebar" id="sellerSidebar">
    <div class="brand">
        <div>Bachat<span>Mart</span></div>
        <button class="btn btn-sm btn-link text-white d-lg-none p-0" onclick="toggleSellerSidebar()" aria-label="Close sidebar">
            <i class="bi bi-x-lg fs-5"></i>
        </button>
    </div>

    {{-- Seller info --}}
    <div class="px-3 py-3 border-bottom" style="border-color: #1E293B !important;">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ auth()->user()->avatar_url }}" class="rounded-circle border" width="40" height="40" alt="Avatar" style="object-fit: cover;">
            <div class="min-w-0">
                <div class="text-truncate" style="color:#fff;font-size:.88rem;font-weight:700">{{ auth()->user()->name }}</div>
                <div class="text-truncate" style="color:#94A3B8;font-size:.75rem">{{ auth()->user()->shop?->name ?? 'No Shop Set' }}</div>
            </div>
        </div>
    </div>

    <nav class="mt-2">
        <div class="nav-section">Main</div>
        <a href="{{ route('seller.dashboard') }}" class="nav-link {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>
        <a href="{{ route('seller.shop.edit') }}" class="nav-link {{ request()->routeIs('seller.shop.*') ? 'active' : '' }}">
            <i class="bi bi-shop"></i> My Store Profile
        </a>

        <div class="nav-section">Surplus Inventory</div>
        <a href="{{ route('seller.products.index') }}" class="nav-link {{ (request()->routeIs('seller.products.index') || request()->routeIs('seller.products.show') || request()->routeIs('seller.products.edit')) ? 'active' : '' }}">
            <i class="bi bi-box-seam-fill"></i> <span>My Surplus Stock</span>
            @if(auth()->user()->shop)
                <span class="badge ms-auto rounded-pill" style="background:#1E293B;color:#94A3B8;font-size:0.75rem">{{ auth()->user()->shop->products()->count() }}</span>
            @endif
        </a>
        <a href="{{ route('seller.products.create') }}" class="nav-link {{ (request()->routeIs('seller.products.create') || request()->routeIs('seller.products.payment')) ? 'active' : '' }}">
            <i class="bi bi-plus-circle-fill text-warning"></i> <span>List Stock (₹12)</span>
            <span class="badge bg-warning text-dark ms-auto" style="font-size:0.65rem;font-weight:800">₹12/Item</span>
        </a>

        <div class="nav-section">Performance & Leads</div>
        <a href="{{ route('seller.leads') }}" class="nav-link {{ request()->routeIs('seller.leads') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> In-Store Leads
        </a>
        <a href="{{ route('seller.analytics') }}" class="nav-link {{ request()->routeIs('seller.analytics') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-fill"></i> Analytics
        </a>

        <div class="nav-section">Billing & Settings</div>
        <a href="{{ route('seller.payments.index') }}" class="nav-link {{ request()->routeIs('seller.payments.*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i> Payment History
        </a>
        <a href="{{ route('seller.notifications') }}" class="nav-link {{ request()->routeIs('seller.notifications') ? 'active' : '' }}">
            <i class="bi bi-bell-fill"></i> Notifications
            @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="badge ms-auto rounded-pill" style="background:var(--primary)">{{ auth()->user()->unreadNotifications->count() }}</span>
            @endif
        </a>
        <a href="{{ route('seller.settings') }}" class="nav-link {{ request()->routeIs('seller.settings') ? 'active' : '' }}">
            <i class="bi bi-gear-fill"></i> Settings
        </a>
        <hr style="border-color:#1E293B;margin:0.75rem 1rem">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-link w-100 text-start border-0" style="background:none;color:#EF4444">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </nav>
</aside>

{{-- Main Content --}}
<div class="main-content">
    <div class="top-bar">
        <div class="d-flex align-items-center gap-2 gap-md-3">
            <button class="btn btn-sm btn-outline-secondary d-lg-none rounded-circle" onclick="toggleSellerSidebar()" aria-label="Toggle sidebar menu">
                <i class="bi bi-list fs-5"></i>
            </button>
            <span class="page-title text-truncate">@yield('page-title', 'Dashboard')</span>
        </div>
        <div class="d-flex align-items-center gap-2 gap-md-3">
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-600 d-none d-sm-inline-flex" target="_blank">
                <i class="bi bi-box-arrow-up-right me-1"></i>View Site
            </a>
            <a href="{{ route('seller.products.create') }}" class="btn btn-primary-bm btn-sm px-3 px-md-4 rounded-pill">
                <i class="bi bi-plus-circle-fill me-1"></i><span class="d-none d-sm-inline">+ List Stock — </span>₹12
            </a>
        </div>
    </div>

    {{-- Flash Notifications --}}
    <div class="px-3 pt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show rounded-4 shadow-sm border-0" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i>{{ session('info') }}
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
    function toggleSellerSidebar() {
        const sidebar = document.getElementById('sellerSidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        if (sidebar) sidebar.classList.toggle('show');
        if (backdrop) backdrop.classList.toggle('show');
    }

    toastr.options = {
        "closeButton": true,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "4500",
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
