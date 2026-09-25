<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - BachatMart</title>
    
    {{-- Bootstrap 5 & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    {{-- Google Fonts - Montserrat --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,600;1,700&display=swap" rel="stylesheet">

    <style>
        :root {
            --admin-primary: #4F46E5;
            --admin-primary-hover: #4338CA;
            --sidebar-bg: #0F172A;
            --sidebar-width: 250px;
        }
        body { 
            font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important; 
            background: #F8FAFC; 
            color: #1E293B;
            font-size: 14px;
            font-weight: 400;
        }
        .admin-sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0; left: 0;
            overflow-y: auto;
            padding-bottom: 1.5rem;
            z-index: 100;
            border-right: 1px solid #1E293B;
            transition: transform 0.3s ease;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .admin-sidebar::-webkit-scrollbar { display: none; }
        .admin-sidebar .brand {
            padding: 1.25rem 1.25rem 0.9rem;
            font-weight: 700;
            font-size: 1.15rem;
            color: #FFFFFF;
            letter-spacing: -0.02em;
            border-bottom: 1px solid #1E293B;
        }
        .admin-sidebar .brand .badge-admin {
            font-size: 0.6rem;
            font-weight: 600;
            background: var(--admin-primary);
            color: #FFFFFF;
            padding: 2px 7px;
            border-radius: 6px;
            vertical-align: middle;
            letter-spacing: 0.04em;
        }
        .admin-sidebar .nav-section {
            padding: 1rem 0.85rem 0.3rem;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748B;
            font-weight: 600;
        }
        .admin-sidebar .nav-link {
            color: #94A3B8;
            padding: 0.55rem 0.85rem;
            border-radius: 10px;
            margin: 2px 0.5rem;
            font-size: 0.84rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            transition: all 0.2s ease;
        }
        .admin-sidebar .nav-link:hover { 
            background: #1E293B; 
            color: #FFFFFF; 
        }
        .admin-sidebar .nav-link.active { 
            background: var(--admin-primary); 
            color: #FFFFFF; 
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        }
        .admin-sidebar .nav-link i { width: 18px; font-size: 1rem; }
        
        .main-content { 
            margin-left: var(--sidebar-width); 
            min-height: 100vh; 
            background: #F8FAFC;
        }
        .top-bar {
            background: #FFFFFF;
            border-bottom: 1px solid #E2E8F0;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0; z-index: 99;
        }
        .top-bar .page-title { 
            font-size: 1.05rem; 
            font-weight: 600; 
            color: #0F172A;
            letter-spacing: -0.01em;
        }
        .page-content { padding: 1.5rem; }
        
        .stat-card {
            background: #FFFFFF;
            border-radius: 14px;
            padding: 1.1rem 1.25rem;
            box-shadow: 0 1px 4px rgba(15, 23, 42, 0.03);
            border: 1px solid #E2E8F0;
            transition: all 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
            border-color: #CBD5E1;
        }
        .stat-card .stat-value { 
            font-size: 1.55rem; 
            font-weight: 700; 
            letter-spacing: -0.02em;
            line-height: 1.15;
        }
        .stat-card .stat-label { 
            font-size: 0.78rem; 
            color: #64748B; 
            font-weight: 500; 
            margin-top: 3px;
        }
        
        .card-admin {
            background: #FFFFFF;
            border-radius: 14px;
            box-shadow: 0 1px 4px rgba(15, 23, 42, 0.03);
            border: 1px solid #E2E8F0;
        }
        .form-label {
            font-size: 0.82rem;
            font-weight: 500;
            color: #475569;
            margin-bottom: 0.35rem;
        }
        .form-control, .form-select {
            font-size: 0.86rem;
            font-weight: 400;
            border-radius: 8px;
            border: 1px solid #E2E8F0;
            color: #1E293B;
            padding: 0.5rem 0.75rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: #6366F1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        .table-admin thead th { 
            font-size: 0.75rem; 
            font-weight: 600; 
            color: #64748B; 
            text-transform: uppercase; 
            letter-spacing: 0.04em; 
            border-bottom: 1.5px solid #E2E8F0; 
            padding: 0.75rem 1rem;
        }
        .table-admin tbody td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
            font-size: 0.86rem;
            font-weight: 400;
        }

        .badge-pending { background: #FEF3C7; color: #92400E; border: 1px solid #FDE68A; }
        .badge-approved { background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; }
        .badge-rejected { background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; }
        .badge-active { background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; }
        .badge-blocked { background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; }
        .badge-featured { background: #EFF6FF; color: #1E40AF; border: 1px solid #BFDBFE; }

        @media (max-width: 768px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }

        /* Pagination Styling */
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
            gap: 6px !important;
            margin-bottom: 0 !important;
            align-items: center !important;
        }
        .pagination .page-item .page-link {
            border-radius: 9999px !important;
            margin: 0 2px !important;
            color: #475569 !important;
            border: 1.5px solid #E2E8F0 !important;
            padding: 0.45rem 0.95rem !important;
            font-weight: 700 !important;
            font-size: 0.88rem !important;
            transition: all 0.2s ease !important;
        }
        .pagination .page-item.active .page-link {
            background-color: var(--admin-primary) !important;
            border-color: var(--admin-primary) !important;
            color: #FFFFFF !important;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3) !important;
        }
        .pagination .page-item .page-link:hover {
            background-color: #EEF2FF !important;
            border-color: var(--admin-primary) !important;
            color: var(--admin-primary) !important;
        }
        .pagination .page-item.disabled .page-link {
            color: #94A3B8 !important;
            background-color: #F8FAFC !important;
            border-color: #E2E8F0 !important;
        }
    </style>
    @stack('styles')
</head>
<body>

<aside class="admin-sidebar" id="adminSidebar">
    <div class="brand">BachatMart <span class="badge-admin">ADMIN</span></div>

    <nav class="mt-2">
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

        <div class="nav-section">Content & Inventory</div>
        <a href="{{ route('admin.shops.index') }}" class="nav-link {{ request()->routeIs('admin.shops.*') ? 'active' : '' }}">
            <i class="bi bi-buildings"></i> Shops
        </a>
        <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> Products
            @php $pending = \App\Models\Product::where('status','pending')->count(); @endphp
            @if($pending > 0) <span class="badge ms-auto bg-danger rounded-pill px-2">{{ $pending }}</span> @endif
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

        <div class="nav-section">Performance</div>
        <a href="{{ route('admin.leads.index') }}" class="nav-link {{ request()->routeIs('admin.leads.*') ? 'active' : '' }}">
            <i class="bi bi-graph-up"></i> Leads
        </a>

        <div class="nav-section">System</div>
        <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <i class="bi bi-gear"></i> Settings
        </a>
        <hr style="border-color:#1E293B;margin:0.75rem 1rem">
        <a href="{{ route('home') }}" class="nav-link" target="_blank">
            <i class="bi bi-box-arrow-up-right"></i> View Live Site
        </a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-link w-100 text-start border-0" style="background:none;color:#EF4444">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </nav>
</aside>

<div class="main-content">
    <div class="top-bar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-outline-secondary d-md-none rounded-circle" onclick="document.getElementById('adminSidebar').classList.toggle('show')">
                <i class="bi bi-list fs-5"></i>
            </button>
            <span class="page-title">@yield('page-title', 'Dashboard')</span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3 py-1 fw-700">Admin</span>
            <span class="small fw-700 text-dark">{{ auth()->user()->name }}</span>
        </div>
    </div>

    <div class="px-3 pt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
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
