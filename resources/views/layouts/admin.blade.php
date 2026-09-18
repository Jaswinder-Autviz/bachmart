<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - BachatMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --admin-primary: #5a67d8;
            --admin-dark: #434190;
            --sidebar-bg: #1a202c;
            --sidebar-width: 260px;
        }
        body { font-family: 'Inter', sans-serif; background: #f7f8fa; }
        .admin-sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0; left: 0;
            overflow-y: auto;
            padding-bottom: 0.75rem;
            z-index: 100;
        }
        .admin-sidebar .brand {
            padding: 1.5rem;
            font-weight: 800;
            font-size: 1.3rem;
            color: #fff;
            border-bottom: 1px solid #2d3748;
        }
        .admin-sidebar .brand .badge-admin {
            font-size: 0.6rem;
            background: var(--admin-primary);
            color: #fff;
            padding: 2px 7px;
            border-radius: 4px;
            vertical-align: middle;
        }
        .admin-sidebar .nav-section {
            padding: 0.75rem 1rem 0.25rem;
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #4a5568;
            font-weight: 600;
        }
        .admin-sidebar .nav-link {
            color: #a0aec0;
            padding: 0.6rem 1rem;
            border-radius: 8px;
            margin: 2px 0.5rem;
            font-size: 0.88rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all .2s;
        }
        .admin-sidebar .nav-link:hover { background: #2d3748; color: #fff; }
        .admin-sidebar .nav-link.active { background: var(--admin-primary); color: #fff; }
        .admin-sidebar .nav-link i { width: 18px; }
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; }
        .top-bar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.8rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0; z-index: 99;
        }
        .top-bar .page-title { font-size: 1.1rem; font-weight: 700; }
        .page-content { padding: 1.5rem; }
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            border: none;
        }
        .stat-card .stat-value { font-size: 1.9rem; font-weight: 800; }
        .stat-card .stat-label { font-size: 0.8rem; color: #718096; font-weight: 500; }
        .card-admin {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            border: none;
        }
        .table-admin thead { background: #f7f8fa; }
        .table-admin thead th { font-size: 0.8rem; font-weight: 600; color: #4a5568; text-transform: uppercase; letter-spacing: .5px; border-bottom: 2px solid #e2e8f0; }
        .badge-pending { background: #feebc8; color: #7b341e; }
        .badge-approved { background: #c6f6d5; color: #276749; }
        .badge-rejected { background: #fed7d7; color: #9b2c2c; }
        .badge-active { background: #c6f6d5; color: #276749; }
        .badge-blocked { background: #fed7d7; color: #9b2c2c; }
        .badge-featured { background: #bee3f8; color: #2c5282; }
        @media (max-width: 768px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-sidebar.show { transform: translateX(0); }
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
            background-color: #5A67D8 !important;
            border-color: #5A67D8 !important;
            color: #FFFFFF !important;
            box-shadow: 0 2px 8px rgba(90, 103, 216, 0.3) !important;
        }
        .pagination .page-item .page-link:hover {
            background-color: #EEF2FF !important;
            border-color: #5A67D8 !important;
            color: #5A67D8 !important;
        }
        .pagination .page-item.disabled .page-link {
            color: #A0AEC0 !important;
            background-color: #F7FAFC !important;
            border-color: #E2E8F0 !important;
        }
    </style>
    @stack('styles')
</head>
<body>

<aside class="admin-sidebar" id="adminSidebar">
    <div class="brand">BachatMart <span class="badge-admin">ADMIN</span></div>

    <nav class="mt-1">
        <div class="nav-section">Overview</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="nav-section">Users</div>
        <a href="{{ route('admin.sellers.index') }}" class="nav-link {{ request()->routeIs('admin.sellers.*') ? 'active' : '' }}">
            <i class="bi bi-shop"></i> Sellers
        </a>
        <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Customers
        </a>

        <div class="nav-section">Content</div>
        <a href="{{ route('admin.shops.index') }}" class="nav-link {{ request()->routeIs('admin.shops.*') ? 'active' : '' }}">
            <i class="bi bi-buildings"></i> Shops
        </a>
        <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> Products
            @php $pending = \App\Models\Product::where('status','pending')->count(); @endphp
            @if($pending > 0) <span class="badge ms-auto bg-danger">{{ $pending }}</span> @endif
        </a>
        <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i> Categories
        </a>
        <a href="{{ route('admin.reviews.index') }}" class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
            <i class="bi bi-star"></i> Reviews
        </a>

        <div class="nav-section">Monetization</div>
        <a href="{{ route('admin.featured.index') }}" class="nav-link {{ request()->routeIs('admin.featured.*') ? 'active' : '' }}">
            <i class="bi bi-star-fill"></i> Featured Listings
        </a>
        <a href="{{ route('admin.subscriptions.index') }}" class="nav-link {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
            <i class="bi bi-credit-card"></i> Subscriptions
        </a>
        <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
            <i class="bi bi-currency-rupee"></i> Payments
        </a>

        <div class="nav-section">Reports</div>
        <a href="{{ route('admin.leads.index') }}" class="nav-link {{ request()->routeIs('admin.leads.*') ? 'active' : '' }}">
            <i class="bi bi-graph-up"></i> Leads
        </a>

        <div class="nav-section">Config</div>
        <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <i class="bi bi-gear"></i> Settings
        </a>
        <hr style="border-color:#2d3748;margin:0.5rem 1rem">
        <a href="{{ route('home') }}" class="nav-link" target="_blank">
            <i class="bi bi-box-arrow-up-right"></i> View Site
        </a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-link w-100 text-start border-0" style="background:none;color:#e53e3e">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </nav>
</aside>

<div class="main-content">
    <div class="top-bar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-outline-secondary d-md-none" onclick="document.getElementById('adminSidebar').classList.toggle('show')">
                <i class="bi bi-list fs-5"></i>
            </button>
            <span class="page-title">@yield('page-title', 'Dashboard')</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-success">Admin</span>
            <span class="small text-muted">{{ auth()->user()->name }}</span>
        </div>
    </div>

    <div class="px-3 pt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <div class="page-content">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
