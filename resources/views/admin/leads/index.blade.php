@extends('layouts.admin')
@section('title', 'Leads & Reports')
@section('page-title', 'Customer Inquiry & Lead Reports')

@section('content')
{{-- Lead Summary Metrics --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-value text-dark">{{ number_format($summary['total'] ?? 0) }}</div>
            <div class="stat-label">Total Inquiries</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-value" style="color:#25D366">{{ number_format($summary['whatsapp'] ?? 0) }}</div>
            <div class="stat-label">WhatsApp Leads</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-value text-primary">{{ number_format($summary['call'] ?? 0) }}</div>
            <div class="stat-label">Phone Calls</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-value text-danger">{{ number_format($summary['direction'] ?? 0) }}</div>
            <div class="stat-label">Store Directions</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-value text-secondary">{{ number_format($summary['view'] ?? 0) }}</div>
            <div class="stat-label">Product Views</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-value" style="color:#FF5722">{{ number_format($summary['today'] ?? 0) }}</div>
            <div class="stat-label">Today's Leads</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card-admin p-3 mb-4">
    <form method="GET" action="{{ route('admin.leads.index') }}" class="row g-2 align-items-center">
        <div class="col-md-3">
            <select name="type" class="form-select form-select-sm">
                <option value="">All Inquiry Types</option>
                <option value="whatsapp" {{ request('type') === 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                <option value="call" {{ request('type') === 'call' ? 'selected' : '' }}>Phone Call</option>
                <option value="direction" {{ request('type') === 'direction' ? 'selected' : '' }}>Store Direction</option>
                <option value="view" {{ request('type') === 'view' ? 'selected' : '' }}>Product View</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="period" class="form-select form-select-sm">
                <option value="">All Time</option>
                <option value="today" {{ request('period') === 'today' ? 'selected' : '' }}>Today</option>
                <option value="week" {{ request('period') === 'week' ? 'selected' : '' }}>This Week</option>
                <option value="month" {{ request('period') === 'month' ? 'selected' : '' }}>This Month</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-sm" style="background:#5a67d8;color:#fff">
                <i class="bi bi-filter me-1"></i>Filter
            </button>
            <a href="{{ route('admin.leads.index') }}" class="btn btn-sm btn-outline-secondary ms-1">Reset</a>
        </div>
    </form>
</div>

{{-- Leads Table --}}
<div class="card-admin overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 table-admin" style="font-size:.875rem">
            <thead>
                <tr>
                    <th class="ps-3 py-3">Type</th>
                    <th>Product</th>
                    <th>Shop / Seller</th>
                    <th>Customer Info</th>
                    <th>IP / Source</th>
                    <th class="text-end pe-3">Date & Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                <tr>
                    <td class="ps-3">
                        @if($lead->type === 'whatsapp')
                            <span class="badge bg-success-subtle text-success fw-600 px-2 py-1 rounded-pill">
                                <i class="bi bi-whatsapp me-1"></i>WhatsApp
                            </span>
                        @elseif($lead->type === 'call')
                            <span class="badge bg-primary-subtle text-primary fw-600 px-2 py-1 rounded-pill">
                                <i class="bi bi-telephone-fill me-1"></i>Call
                            </span>
                        @elseif($lead->type === 'direction')
                            <span class="badge bg-danger-subtle text-danger fw-600 px-2 py-1 rounded-pill">
                                <i class="bi bi-map-fill me-1"></i>Direction
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary fw-600 px-2 py-1 rounded-pill">
                                <i class="bi bi-eye-fill me-1"></i>View
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($lead->product)
                            <a href="{{ route('product.show', $lead->product->slug) }}" target="_blank" class="fw-600 text-dark text-decoration-none">
                                {{ Str::limit($lead->product->name, 35) }}
                            </a>
                        @else
                            <span class="text-muted small">Direct Shop Lead</span>
                        @endif
                    </td>
                    <td>
                        @if($lead->shop)
                            <div class="fw-600">{{ $lead->shop->name }}</div>
                            <div class="text-muted small">{{ $lead->shop->city ?? 'Local' }}</div>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($lead->customer)
                            <div class="fw-600">{{ $lead->customer->name }}</div>
                            <div class="text-muted small">{{ $lead->customer->phone ?? $lead->customer->email }}</div>
                        @else
                            <span class="text-muted small">Anonymous Visitor</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border small">{{ $lead->ip_address ?? '127.0.0.1' }}</span>
                    </td>
                    <td class="text-end pe-3 text-muted small">
                        {{ $lead->created_at ? $lead->created_at->format('d M Y, h:i A') : 'N/A' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-graph-up fs-1 d-block mb-2 opacity-50"></i>
                        No customer inquiries tracked yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $leads->links() }}
</div>
@endsection
