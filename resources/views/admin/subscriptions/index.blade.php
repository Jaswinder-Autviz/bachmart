@extends('layouts.admin')
@section('title','Subscriptions')
@section('page-title','Subscription Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div class="d-flex gap-3">
        <div class="stat-card py-2 px-3"><span class="fw-700" style="color:#48BB78">{{ $stats['active'] }}</span><span class="text-muted small ms-2">Active</span></div>
        <div class="stat-card py-2 px-3"><span class="fw-700" style="color:#FF6B35">₹{{ number_format($stats['revenue']) }}</span><span class="text-muted small ms-2">Revenue</span></div>
    </div>
    <a href="{{ route('admin.subscription-plans.create') }}" class="btn btn-sm" style="background:#5a67d8;color:#fff;border:none">
        <i class="bi bi-plus me-1"></i>New Plan
    </a>
</div>

{{-- Plans --}}
<div class="card-admin p-4 mb-4">
    <h6 class="fw-700 mb-3">Subscription Plans</h6>
    <div class="row g-3">
        @foreach($plans as $plan)
        <div class="col-md-3">
            <div class="p-3 rounded-2 border h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="fw-700">{{ $plan->name }}</div>
                    <a href="{{ route('admin.subscription-plans.edit', $plan) }}" class="btn btn-xs btn-outline-secondary py-0 px-1" style="font-size:.75rem"><i class="bi bi-pencil"></i></a>
                </div>
                <div class="fw-800" style="color:#FF6B35;font-size:1.3rem">{{ $plan->isFree() ? 'Free' : '₹'.number_format($plan->price).'/mo' }}</div>
                <div class="text-muted small">Max: {{ $plan->max_products_label }} products</div>
                <div class="mt-2 d-flex gap-1 flex-wrap">
                    @if($plan->advanced_analytics)<span class="badge" style="background:#e6f7ee;color:#276749;font-size:.65rem">Analytics</span>@endif
                    @if($plan->featured_placement)<span class="badge" style="background:#fefcbf;color:#744210;font-size:.65rem">Featured</span>@endif
                    @if($plan->is_popular)<span class="badge" style="background:#FF6B35;color:#fff;font-size:.65rem">Popular</span>@endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Subscriptions table --}}
<div class="card-admin p-3 mb-4">
    <form method="GET" action="{{ route('admin.subscriptions.index') }}" class="d-flex gap-2 flex-wrap align-items-end">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Seller name or email..." value="{{ request('search') }}" style="max-width:240px">
        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="max-width:140px">
            <option value="">All</option>
            <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
            <option value="expired" {{ request('status')=='expired'?'selected':'' }}>Expired</option>
            <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
        </select>
        <button type="submit" class="btn btn-sm" style="background:#5a67d8;color:#fff;border:none">Search</button>
    </form>
</div>

<div class="card-admin overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 table-admin" style="font-size:.875rem">
            <thead><tr>
                <th class="ps-3 py-3">Seller</th>
                <th>Plan</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Expires</th>
                <th class="text-end pe-3">Actions</th>
            </tr></thead>
            <tbody>
                @forelse($subscriptions as $sub)
                <tr>
                    <td class="ps-3">
                        <div class="fw-600">{{ $sub->user?->name }}</div>
                        <div class="text-muted" style="font-size:.73rem">{{ $sub->user?->email }}</div>
                    </td>
                    <td><span class="badge badge-featured rounded-pill">{{ $sub->subscriptionPlan?->name }}</span></td>
                    <td class="fw-600">₹{{ number_format($sub->amount_paid) }}</td>
                    <td><span class="badge badge-{{ $sub->status === 'active' ? 'approved' : ($sub->status === 'pending' ? 'pending' : 'rejected') }} rounded-pill">{{ $sub->status }}</span></td>
                    <td class="text-muted small">{{ $sub->expires_at?->format('d M Y') ?? '—' }}</td>
                    <td class="text-end pe-3">
                        @if($sub->status === 'pending')
                        <form action="{{ route('admin.subscriptions.activate', $sub) }}" method="POST">
                            @csrf<button class="btn btn-xs py-1 px-2" style="background:#c6f6d5;color:#276749;border:none;border-radius:4px;font-size:.75rem">Activate</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-5 text-muted">No subscriptions found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">{{ $subscriptions->links() }}</div>
@endsection
