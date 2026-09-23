@extends('layouts.admin')
@section('title', 'Payments')
@section('page-title', 'Payment Transactions')

@section('content')
{{-- Revenue Stats --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-value text-success">₹{{ number_format($stats['total_revenue'] ?? 0) }}</div>
            <div class="stat-label">Total Completed Revenue</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-value" style="color:#5a67d8">₹{{ number_format($stats['monthly_revenue'] ?? 0) }}</div>
            <div class="stat-label">This Month's Revenue</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-value text-warning">{{ $stats['pending_count'] ?? 0 }}</div>
            <div class="stat-label">Pending Verifications</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-value" style="color:#FF5722">₹{{ number_format($stats['listing_revenue'] ?? 0) }}</div>
            <div class="stat-label">Product Listing Fees (₹12)</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card-admin p-3 mb-4">
    <form method="GET" action="{{ route('admin.payments.index') }}" class="row g-2 align-items-center">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control form-control-sm"
                   placeholder="Search transaction ID, user or email..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select form-select-sm">
                <option value="">All Statuses</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-sm" style="background:#5a67d8;color:#fff">
                <i class="bi bi-filter me-1"></i>Filter
            </button>
            <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-outline-secondary ms-1">Reset</a>
        </div>
    </form>
</div>

{{-- Payments Table --}}
<div class="card-admin overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 table-admin" style="font-size:.875rem">
            <thead>
                <tr>
                    <th class="ps-3 py-3">Txn ID / Gateway</th>
                    <th>User / Seller</th>
                    <th>Payable Item / Purpose</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td class="ps-3">
                        <div class="fw-700 text-dark">{{ $payment->transaction_id ?? $payment->payment_id ?? 'TXN-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</div>
                        <div class="text-muted small">{{ ucfirst($payment->payment_method ?? $payment->gateway ?? 'direct') }}</div>
                    </td>
                    <td>
                        <div class="fw-600">{{ $payment->user->name ?? 'User #' . $payment->user_id }}</div>
                        <div class="text-muted small">{{ $payment->user->email ?? 'N/A' }}</div>
                    </td>
                    <td>
                        @if($payment->type === 'product_listing' || $payment->payable_type === \App\Models\Product::class)
                            <span class="badge bg-primary-subtle text-primary fw-600">Product Listing (₹12)</span>
                        @elseif($payment->payable_type === \App\Models\FeaturedProduct::class || $payment->type === 'featured')
                            <span class="badge bg-warning-subtle text-warning-emphasis fw-600">Featured Listing</span>
                        @elseif($payment->payable_type === \App\Models\Subscription::class)
                            <span class="badge bg-secondary-subtle text-secondary fw-600">Subscription (Legacy)</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary">{{ ucfirst($payment->type ?? class_basename($payment->payable_type ?? 'Payment')) }}</span>
                        @endif
                    </td>
                    <td>
                        <span class="fw-800 text-dark">₹{{ number_format($payment->amount, 2) }}</span>
                    </td>
                    <td>
                        @if($payment->status === 'completed')
                            <span class="badge badge-approved rounded-pill">Completed</span>
                        @elseif($payment->status === 'pending')
                            <span class="badge badge-pending rounded-pill">Pending</span>
                        @else
                            <span class="badge badge-rejected rounded-pill">{{ ucfirst($payment->status) }}</span>
                        @endif
                    </td>
                    <td class="text-muted small">
                        {{ $payment->created_at ? $payment->created_at->format('d M Y, h:i A') : 'N/A' }}
                    </td>
                    <td class="text-end pe-3">
                        @if($payment->status === 'pending')
                            <form action="{{ route('admin.payments.approve', $payment) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Confirm and approve this payment?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success py-1 px-2" style="font-size:.78rem">
                                    <i class="bi bi-check-lg me-1"></i>Approve
                                </button>
                            </form>
                        @else
                            <span class="text-muted small">Processed</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-receipt fs-1 d-block mb-2 opacity-50"></i>
                        No payment transactions found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $payments->links() }}
</div>
@endsection
