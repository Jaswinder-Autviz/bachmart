@extends('layouts.admin')
@section('title','Shops')
@section('page-title','Shop Management')

@section('content')
<div class="card-admin p-3 mb-4">
    <form method="GET" action="{{ route('admin.shops.index') }}" class="d-flex gap-2 flex-wrap align-items-end">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Shop name, city..." value="{{ request('search') }}" style="max-width:240px">
        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="max-width:140px">
            <option value="">All Status</option>
            <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
            <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
            <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
        </select>
        <button type="submit" class="btn btn-sm" style="background:#5a67d8;color:#fff;border:none">Search</button>
    </form>
</div>

<div class="card-admin overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 table-admin" style="font-size:.875rem">
            <thead>
                <tr>
                    <th class="ps-3 py-3">Shop</th>
                    <th>Seller</th>
                    <th>Location</th>
                    <th>Products</th>
                    <th>Status</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shops as $shop)
                <tr>
                    <td class="ps-3">
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $shop->logo_url }}" width="38" height="38" class="rounded-2" style="object-fit:cover;background:#f0f0f0">
                            <div>
                                <a href="{{ route('admin.shops.show', $shop) }}" class="fw-700 text-decoration-none text-dark">{{ Str::limit($shop->name,28) }}</a>
                                <div class="d-flex gap-1 mt-1">
                                    @if($shop->is_verified)<span class="badge" style="background:#e6f7ee;color:#276749;font-size:.65rem">Verified</span>@endif
                                    @if($shop->is_featured)<span class="badge badge-featured rounded-pill" style="font-size:.65rem">Featured</span>@endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="small">{{ $shop->user?->name }}<br><span class="text-muted" style="font-size:.73rem">{{ $shop->user?->email }}</span></td>
                    <td class="text-muted small">{{ $shop->city }}, {{ $shop->state }}</td>
                    <td><span class="fw-600">{{ $shop->products_count }}</span><span class="text-muted small">/{{ $shop->approved_products_count }} approved</span></td>
                    <td><span class="badge badge-{{ $shop->status === 'active' ? 'approved' : ($shop->status === 'blocked' ? 'rejected' : 'pending') }} rounded-pill text-capitalize">{{ $shop->status }}</span></td>
                    <td class="text-end pe-3">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('admin.shops.show', $shop) }}" class="btn btn-xs btn-outline-secondary py-1 px-2" style="font-size:.75rem"><i class="bi bi-eye"></i></a>
                            @if($shop->status !== 'active')
                            <form action="{{ route('admin.shops.activate', $shop) }}" method="POST">@csrf<button class="btn btn-xs py-1 px-2" style="background:#c6f6d5;color:#276749;border:none;border-radius:4px;font-size:.75rem">Activate</button></form>
                            @else
                            <form action="{{ route('admin.shops.deactivate', $shop) }}" method="POST">@csrf<button class="btn btn-xs py-1 px-2" style="background:#e2e8f0;color:#4a5568;border:none;border-radius:4px;font-size:.75rem">Deactivate</button></form>
                            @endif
                            @if(!$shop->is_verified)
                            <form action="{{ route('admin.shops.verify', $shop) }}" method="POST">@csrf<button class="btn btn-xs py-1 px-2" style="background:#bee3f8;color:#2c5282;border:none;border-radius:4px;font-size:.75rem">Verify</button></form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-5 text-muted">No shops found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">{{ $shops->links() }}</div>
@endsection
