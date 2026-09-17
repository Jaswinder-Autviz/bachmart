@extends('layouts.admin')
@section('title', 'Sellers')
@section('page-title', 'Seller Management')

@section('content')
{{-- Filters --}}
<div class="card-admin p-3 mb-4">
    <form method="GET" action="{{ route('admin.sellers.index') }}" class="d-flex gap-2 flex-wrap align-items-end">
        <div>
            <label class="form-label small fw-600 mb-1">Search</label>
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Name, email, phone..." value="{{ request('search') }}" style="min-width:220px">
        </div>
        <div>
            <label class="form-label small fw-600 mb-1">Status</label>
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All</option>
                <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
                <option value="blocked" {{ request('status')=='blocked'?'selected':'' }}>Blocked</option>
                <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-sm" style="background:#5a67d8;color:#fff;border:none">Search</button>
        @if(request('search') || request('status'))
            <a href="{{ route('admin.sellers.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
        @endif
    </form>
</div>

<div class="card-admin overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 table-admin" style="font-size:.875rem">
            <thead>
                <tr>
                    <th class="ps-3 py-3">Seller</th>
                    <th>Shop</th>
                    <th>Plan</th>
                    <th>Products</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sellers as $seller)
                <tr>
                    <td class="ps-3">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $seller->avatar_url }}" width="38" height="38" class="rounded-circle" style="object-fit:cover">
                            <div>
                                <div class="fw-600">{{ $seller->name }}</div>
                                <div class="text-muted" style="font-size:.75rem">{{ $seller->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($seller->shop)
                            <a href="{{ route('admin.shops.show', $seller->shop) }}" class="text-decoration-none fw-600">
                                {{ Str::limit($seller->shop->name, 25) }}
                            </a>
                            <div class="text-muted" style="font-size:.75rem">{{ $seller->shop->city }}</div>
                        @else
                            <span class="text-muted small">No shop</span>
                        @endif
                    </td>
                    <td>
                        @if($seller->activeSubscription)
                            <span class="badge badge-featured rounded-pill">{{ $seller->activeSubscription->subscriptionPlan->name }}</span>
                        @else
                            <span class="text-muted small">FREE</span>
                        @endif
                    </td>
                    <td class="fw-600">{{ $seller->shop?->products()->count() ?? 0 }}</td>
                    <td>
                        <span class="badge badge-{{ $seller->status === 'active' ? 'approved' : ($seller->status === 'blocked' ? 'rejected' : 'pending') }} rounded-pill text-capitalize">
                            {{ $seller->status }}
                        </span>
                    </td>
                    <td class="text-muted small">{{ $seller->created_at->format('d M Y') }}</td>
                    <td class="text-end pe-3">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('admin.sellers.show', $seller) }}" class="btn btn-xs btn-outline-secondary py-1 px-2" title="View" style="font-size:.75rem"><i class="bi bi-eye"></i></a>
                            @if($seller->status === 'blocked')
                                <form action="{{ route('admin.sellers.unblock', $seller) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-xs py-1 px-2" style="background:#c6f6d5;color:#276749;border:none;border-radius:4px;font-size:.75rem" title="Unblock">Unblock</button>
                                </form>
                            @else
                                <form action="{{ route('admin.sellers.block', $seller) }}" method="POST" onsubmit="return confirm('Block this seller?')">
                                    @csrf
                                    <button type="submit" class="btn btn-xs py-1 px-2" style="background:#fed7d7;color:#9b2c2c;border:none;border-radius:4px;font-size:.75rem" title="Block">Block</button>
                                </form>
                            @endif
                            <form action="{{ route('admin.sellers.destroy', $seller) }}" method="POST" onsubmit="return confirm('Delete seller account? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-outline-danger py-1 px-2" title="Delete" style="font-size:.75rem"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5 text-muted">No sellers found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">{{ $sellers->links() }}</div>
@endsection
