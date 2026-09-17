@extends('layouts.admin')
@section('title', $user->name)
@section('page-title', 'Seller Profile')

@section('content')
<div class="d-flex gap-2 mb-4 flex-wrap">
    <a href="{{ route('admin.sellers.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
    @if($user->status === 'blocked')
        <form action="{{ route('admin.sellers.unblock', $user) }}" method="POST">@csrf<button type="submit" class="btn btn-sm btn-success">Unblock Seller</button></form>
    @else
        <form action="{{ route('admin.sellers.block', $user) }}" method="POST" onsubmit="return confirm('Block this seller?')">@csrf<button type="submit" class="btn btn-sm btn-danger">Block Seller</button></form>
    @endif
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card-admin p-4 mb-4 text-center">
            <img src="{{ $user->avatar_url }}" width="80" height="80" class="rounded-circle mb-3" style="object-fit:cover">
            <h5 class="fw-700 mb-1">{{ $user->name }}</h5>
            <div class="text-muted small mb-2">{{ $user->email }}</div>
            <div class="text-muted small mb-3">{{ $user->phone }}</div>
            <span class="badge badge-{{ $user->status === 'active' ? 'approved' : 'rejected' }} rounded-pill px-3">{{ $user->status }}</span>
        </div>
        <div class="card-admin p-4">
            <h6 class="fw-700 mb-3">Overview</h6>
            <table class="table table-sm table-borderless small mb-0">
                <tr><td class="text-muted">Total Products</td><td class="fw-600">{{ $stats['total_products'] ?? 0 }}</td></tr>
                <tr><td class="text-muted">Approved Products</td><td class="fw-600">{{ $stats['approved_products'] ?? 0 }}</td></tr>
                <tr><td class="text-muted">Total Leads</td><td class="fw-600">{{ $stats['total_leads'] ?? 0 }}</td></tr>
                <tr><td class="text-muted">Revenue Paid</td><td class="fw-600">₹{{ number_format($stats['total_revenue'] ?? 0) }}</td></tr>
                <tr><td class="text-muted">Joined</td><td class="fw-600">{{ $user->created_at->format('d M Y') }}</td></tr>
            </table>
        </div>
    </div>

    <div class="col-lg-8">
        @if($user->shop)
        <div class="card-admin p-4 mb-4">
            <h6 class="fw-700 mb-3">Shop Information</h6>
            <div class="d-flex gap-3 align-items-start">
                <img src="{{ $user->shop->logo_url }}" width="60" height="60" class="rounded-2" style="object-fit:cover">
                <div>
                    <a href="{{ route('admin.shops.show', $user->shop) }}" class="fw-700 text-decoration-none">{{ $user->shop->name }}</a>
                    <div class="text-muted small">{{ $user->shop->full_address }}</div>
                    <div class="d-flex gap-2 mt-1">
                        <span class="badge badge-{{ $user->shop->status === 'active' ? 'approved' : 'pending' }} rounded-pill">{{ $user->shop->status }}</span>
                        @if($user->shop->is_verified)<span class="badge badge-featured rounded-pill">Verified</span>@endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Recent Products --}}
        @if($user->shop && $user->shop->products->count())
        <div class="card-admin p-4 mb-4">
            <h6 class="fw-700 mb-3">Products</h6>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0" style="font-size:.8rem">
                    <thead style="background:#f7f8fa"><tr><th class="ps-2">Product</th><th>Price</th><th>Status</th></tr></thead>
                    <tbody>
                        @foreach($user->shop->products->take(8) as $product)
                        <tr>
                            <td class="ps-2 fw-600">{{ Str::limit($product->name, 40) }}</td>
                            <td>₹{{ number_format($product->offer_price) }}</td>
                            <td><span class="badge badge-{{ $product->status === 'approved' ? 'approved' : ($product->status === 'pending' ? 'pending' : 'rejected') }} rounded-pill text-capitalize">{{ $product->status }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Activity Log --}}
        @if($activityLogs->count())
        <div class="card-admin p-4">
            <h6 class="fw-700 mb-3">Activity Log</h6>
            @foreach($activityLogs as $log)
            <div class="d-flex gap-3 py-2 border-bottom small">
                <div class="text-muted flex-shrink-0" style="min-width:120px">{{ $log->created_at->format('d M, H:i') }}</div>
                <div>{{ $log->description }}</div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
