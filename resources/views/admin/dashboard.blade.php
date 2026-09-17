@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')
{{-- Stats Grid --}}
<div class="row g-3 mb-4">
    @php
    $cards = [
        ['label'=>'Total Sellers','value'=>$stats['total_sellers'],'icon'=>'bi-shop','color'=>'#5a67d8','bg'=>'#ebf4ff'],
        ['label'=>'Total Customers','value'=>$stats['total_customers'],'icon'=>'bi-people','color'=>'#38b2ac','bg'=>'#e6fffa'],
        ['label'=>'Total Shops','value'=>$stats['total_shops'],'icon'=>'bi-buildings','color'=>'#48BB78','bg'=>'#f0fff4'],
        ['label'=>'Total Products','value'=>$stats['total_products'],'icon'=>'bi-box-seam','color'=>'#9f7aea','bg'=>'#faf5ff'],
        ['label'=>'Pending Approval','value'=>$stats['pending_products'],'icon'=>'bi-hourglass-split','color'=>'#ed8936','bg'=>'#fffaf0'],
        ['label'=>'Approved Products','value'=>$stats['approved_products'],'icon'=>'bi-check-circle','color'=>'#48BB78','bg'=>'#f0fff4'],
        ['label'=>'Total Leads','value'=>$stats['total_leads'],'icon'=>'bi-graph-up','color'=>'#e53e3e','bg'=>'#fff5f5'],
        ['label'=>'Monthly Revenue','value'=>'₹'.number_format($stats['monthly_revenue']),'icon'=>'bi-currency-rupee','color'=>'#FF6B35','bg'=>'#fff5f0'],
    ];
    @endphp
    @foreach($cards as $card)
    <div class="col-6 col-md-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width:44px;height:44px;background:{{ $card['bg'] }};color:{{ $card['color'] }};font-size:1.2rem">
                <i class="bi {{ $card['icon'] }}"></i>
            </div>
            <div>
                <div class="stat-value" style="color:{{ $card['color'] }}">{{ $card['value'] }}</div>
                <div class="stat-label">{{ $card['label'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Pending Products Alert --}}
@if($stats['pending_products'] > 0)
<div class="alert d-flex align-items-center justify-content-between gap-3 mb-4"
     style="background:#fffaf0;border:1px solid #fbd38d;border-radius:10px">
    <div><i class="bi bi-exclamation-circle-fill text-warning me-2"></i>
        <strong>{{ $stats['pending_products'] }} products</strong> are waiting for your approval.
    </div>
    <a href="{{ route('admin.products.index') }}?status=pending" class="btn btn-sm btn-warning">Review Now</a>
</div>
@endif

<div class="row g-4">
    {{-- Revenue Chart --}}
    <div class="col-lg-8">
        <div class="card-admin p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-700 mb-0">Revenue (Last 6 Months)</h6>
                <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-outline-secondary">View All</a>
            </div>
            <canvas id="revenueChart" height="100"></canvas>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="col-lg-4">
        <div class="card-admin p-4 h-100">
            <h6 class="fw-700 mb-3">Today's Activity</h6>
            <div class="d-flex flex-column gap-3">
                <div class="d-flex justify-content-between align-items-center p-3 rounded-2" style="background:#f7f8fa">
                    <span class="small fw-600">Leads Today</span>
                    <span class="fw-800" style="color:#5a67d8">{{ $stats['leads_today'] }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center p-3 rounded-2" style="background:#f7f8fa">
                    <span class="small fw-600">Active Shops</span>
                    <span class="fw-800 text-success">{{ $stats['active_shops'] }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center p-3 rounded-2" style="background:#f7f8fa">
                    <span class="small fw-600">Featured Products</span>
                    <span class="fw-800" style="color:#FF6B35">{{ $stats['featured_products'] }}</span>
                </div>
            </div>
            <div class="mt-3 d-flex flex-wrap gap-2">
                <a href="{{ route('admin.products.index') }}?status=pending" class="btn btn-sm btn-warning w-100">
                    <i class="bi bi-hourglass-split me-1"></i>Review Pending Products
                </a>
                <a href="{{ route('admin.reviews.index') }}?status=pending" class="btn btn-sm btn-outline-secondary w-100">
                    <i class="bi bi-star me-1"></i>Moderate Reviews
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-0">
    {{-- Recent Sellers --}}
    <div class="col-lg-6">
        <div class="card-admin p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-700 mb-0">Recent Sellers</h6>
                <a href="{{ route('admin.sellers.index') }}" class="btn btn-sm btn-outline-secondary">View All</a>
            </div>
            @foreach($recentSellers as $seller)
            <div class="d-flex align-items-center gap-3 py-2 border-bottom">
                <img src="{{ $seller->avatar_url }}" width="38" height="38" class="rounded-circle flex-shrink-0" style="object-fit:cover">
                <div class="flex-grow-1 min-w-0">
                    <div class="fw-600 small text-truncate">{{ $seller->name }}</div>
                    <div class="text-muted" style="font-size:.75rem">{{ $seller->email }} · {{ $seller->shop?->city ?? 'No shop' }}</div>
                </div>
                <span class="badge badge-{{ $seller->status === 'active' ? 'approved' : ($seller->status === 'blocked' ? 'rejected' : 'pending') }} rounded-pill" style="font-size:.7rem">{{ $seller->status }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Pending Products --}}
    <div class="col-lg-6">
        <div class="card-admin p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-700 mb-0">Pending Products</h6>
                <a href="{{ route('admin.products.index') }}?status=pending" class="btn btn-sm btn-outline-secondary">View All</a>
            </div>
            @forelse($recentProducts as $product)
            <div class="d-flex align-items-center gap-3 py-2 border-bottom">
                <img src="{{ $product->primary_image_url }}" width="42" height="42"
                     class="rounded-2 flex-shrink-0" style="object-fit:cover"
                     onerror="this.src='{{ asset('images/product-placeholder.png') }}'">
                <div class="flex-grow-1 min-w-0">
                    <div class="fw-600 small text-truncate">{{ $product->name }}</div>
                    <div class="text-muted" style="font-size:.75rem">{{ $product->shop?->name }} · ₹{{ number_format($product->offer_price) }}</div>
                </div>
                <div class="d-flex gap-1">
                    <form action="{{ route('admin.products.approve', $product) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-xs py-1 px-2" style="background:#c6f6d5;color:#276749;border:none;border-radius:4px;font-size:.75rem">✓</button>
                    </form>
                    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-xs btn-outline-secondary py-1 px-2" style="font-size:.75rem">View</a>
                </div>
            </div>
            @empty
            <p class="text-muted text-center py-3 small">No pending products 🎉</p>
            @endforelse
        </div>
    </div>

    {{-- Recent Leads --}}
    <div class="col-lg-6">
        <div class="card-admin p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-700 mb-0">Recent Leads</h6>
                <a href="{{ route('admin.leads.index') }}" class="btn btn-sm btn-outline-secondary">View All</a>
            </div>
            @foreach($recentLeads as $lead)
            <div class="d-flex align-items-center gap-3 py-2 border-bottom">
                <span class="fs-5">{{ $lead->type==='call'?'📞':($lead->type==='whatsapp'?'💬':($lead->type==='direction'?'📍':'👁')) }}</span>
                <div class="flex-grow-1 min-w-0">
                    <div class="fw-600 small text-capitalize">{{ $lead->type }} — {{ $lead->shop?->name }}</div>
                    <div class="text-muted" style="font-size:.75rem">{{ $lead->product?->name ?? 'Shop visit' }} · {{ $lead->created_at->diffForHumans() }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Recent Payments --}}
    <div class="col-lg-6">
        <div class="card-admin p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-700 mb-0">Recent Payments</h6>
                <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-outline-secondary">View All</a>
            </div>
            @foreach($recentPayments as $payment)
            <div class="d-flex align-items-center gap-3 py-2 border-bottom">
                <div class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:36px;height:36px;background:#f0fff4;font-size:1rem">💰</div>
                <div class="flex-grow-1 min-w-0">
                    <div class="fw-600 small">{{ $payment->user?->name }}</div>
                    <div class="text-muted" style="font-size:.75rem">{{ class_basename($payment->payable_type) }} · {{ $payment->created_at->diffForHumans() }}</div>
                </div>
                <div class="text-end">
                    <div class="fw-700 small">₹{{ number_format($payment->amount) }}</div>
                    <span class="badge badge-{{ $payment->status === 'completed' ? 'approved' : 'pending' }}" style="font-size:.7rem">{{ $payment->status }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const months = @json(collect($revenueData)->keys());
const revenues = @json(collect($revenueData)->values());

new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            label: 'Revenue (₹)',
            data: revenues,
            borderColor: '#5a67d8',
            backgroundColor: 'rgba(90,103,216,0.08)',
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#5a67d8',
            pointRadius: 5,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { callback: v => '₹'+v } } }
    }
});
</script>
@endpush
