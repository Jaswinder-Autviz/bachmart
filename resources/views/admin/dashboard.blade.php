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
        ['label'=>'Total Sellers','value'=>$stats['total_sellers'],'icon'=>'bi-shop','color'=>'#4F46E5','bg'=>'#EEF2FF'],
        ['label'=>'Total Customers','value'=>$stats['total_customers'],'icon'=>'bi-people-fill','color'=>'#0D9488','bg'=>'#CCFBF1'],
        ['label'=>'Total Shops','value'=>$stats['total_shops'],'icon'=>'bi-buildings-fill','color'=>'#10B981','bg'=>'#ECFDF5'],
        ['label'=>'Total Products','value'=>$stats['total_products'],'icon'=>'bi-box-seam-fill','color'=>'#8B5CF6','bg'=>'#F5F3FF'],
        ['label'=>'Pending Approval','value'=>$stats['pending_products'],'icon'=>'bi-hourglass-split','color'=>'#F59E0B','bg'=>'#FEF3C7'],
        ['label'=>'Approved Products','value'=>$stats['approved_products'],'icon'=>'bi-check-circle-fill','color'=>'#10B981','bg'=>'#ECFDF5'],
        ['label'=>'Total Leads','value'=>$stats['total_leads'],'icon'=>'bi-graph-up','color'=>'#EF4444','bg'=>'#FEE2E2'],
        ['label'=>'Monthly Revenue','value'=>'₹'.number_format($stats['monthly_revenue']),'icon'=>'bi-currency-rupee','color'=>'#FF5722','bg'=>'#FFF0EB'],
    ];
    @endphp
    @foreach($cards as $card)
    <div class="col-6 col-md-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width:48px;height:48px;background:{{ $card['bg'] }};color:{{ $card['color'] }};font-size:1.3rem">
                <i class="bi {{ $card['icon'] }}"></i>
            </div>
            <div class="min-w-0">
                <div class="stat-value text-truncate" style="color:{{ $card['color'] }}">{{ $card['value'] }}</div>
                <div class="stat-label text-truncate">{{ $card['label'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Pending Products Alert --}}
@if($stats['pending_products'] > 0)
<div class="alert d-flex align-items-center justify-content-between gap-3 mb-4 rounded-4 shadow-sm border-0"
     style="background: #FFFBEB; border: 1.5px solid #FDE68A !important;">
    <div class="d-flex align-items-center gap-2">
        <i class="bi bi-exclamation-circle-fill text-warning fs-5"></i>
        <span class="text-dark fw-700"><strong>{{ $stats['pending_products'] }} products</strong> are waiting for review & approval.</span>
    </div>
    <a href="{{ route('admin.products.index') }}?status=pending" class="btn btn-sm btn-warning rounded-pill px-3 fw-700">Review Now</a>
</div>
@endif

<div class="row g-4">
    {{-- Revenue Chart --}}
    <div class="col-lg-8">
        <div class="card-admin p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-800 mb-0 text-dark">Revenue Overview (Last 6 Months)</h6>
                <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-600">View History</a>
            </div>
            <canvas id="revenueChart" height="110"></canvas>
        </div>
    </div>

    {{-- Quick Activity Stats --}}
    <div class="col-lg-4">
        <div class="card-admin p-4 h-100">
            <h6 class="fw-800 mb-3 text-dark">Today's Activity</h6>
            <div class="d-flex flex-column gap-3">
                <div class="d-flex justify-content-between align-items-center p-3 rounded-3" style="background:#F8FAFC; border: 1px solid #E2E8F0;">
                    <span class="small fw-700 text-dark">Customer Leads Today</span>
                    <span class="fw-900" style="color:#4F46E5">{{ $stats['leads_today'] }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center p-3 rounded-3" style="background:#F8FAFC; border: 1px solid #E2E8F0;">
                    <span class="small fw-700 text-dark">Active Shops</span>
                    <span class="fw-900 text-success">{{ $stats['active_shops'] }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center p-3 rounded-3" style="background:#F8FAFC; border: 1px solid #E2E8F0;">
                    <span class="small fw-700 text-dark">Featured Products</span>
                    <span class="fw-900" style="color:#FF5722">{{ $stats['featured_products'] }}</span>
                </div>
            </div>
            <div class="mt-4 d-flex flex-column gap-2">
                <a href="{{ route('admin.products.index') }}?status=pending" class="btn btn-sm btn-warning w-100 rounded-pill fw-700">
                    <i class="bi bi-hourglass-split me-1"></i>Review Pending Products
                </a>
                <a href="{{ route('admin.reviews.index') }}?status=pending" class="btn btn-sm btn-outline-secondary w-100 rounded-pill fw-600">
                    <i class="bi bi-star me-1"></i>Moderate Reviews
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    {{-- Recent Sellers --}}
    <div class="col-lg-6">
        <div class="card-admin p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-800 mb-0 text-dark">Recent Sellers</h6>
                <a href="{{ route('admin.sellers.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-600">View All</a>
            </div>
            @foreach($recentSellers as $seller)
            <div class="d-flex align-items-center gap-3 py-3 border-bottom">
                <img src="{{ $seller->avatar_url }}" width="40" height="40" class="rounded-circle flex-shrink-0 border" style="object-fit:cover">
                <div class="flex-grow-1 min-w-0">
                    <div class="fw-700 small text-dark text-truncate">{{ $seller->name }}</div>
                    <div class="text-muted" style="font-size:.78rem">{{ $seller->email }} · {{ $seller->shop?->city ?? 'No shop' }}</div>
                </div>
                <span class="badge badge-{{ $seller->status === 'active' ? 'approved' : ($seller->status === 'blocked' ? 'rejected' : 'pending') }} rounded-pill px-3 py-1 fw-700" style="font-size:.72rem">{{ $seller->status }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Pending Products --}}
    <div class="col-lg-6">
        <div class="card-admin p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-800 mb-0 text-dark">Pending Products</h6>
                <a href="{{ route('admin.products.index') }}?status=pending" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-600">View All</a>
            </div>
            @forelse($recentProducts as $product)
            <div class="d-flex align-items-center gap-3 py-3 border-bottom">
                <img src="{{ $product->primary_image_url }}" width="44" height="44"
                     class="rounded-3 flex-shrink-0 border" style="object-fit:cover"
                     onerror="this.src='{{ asset('images/product-placeholder.svg') }}'">
                <div class="flex-grow-1 min-w-0">
                    <div class="fw-700 small text-dark text-truncate">{{ $product->name }}</div>
                    <div class="text-muted" style="font-size:.78rem">{{ $product->shop?->name }} · <strong class="text-primary-bm">₹{{ number_format($product->offer_price) }}</strong></div>
                </div>
                <div class="d-flex gap-2">
                    <form action="{{ route('admin.products.approve', $product) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 fw-700" style="font-size:.78rem">Approve</button>
                    </form>
                    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-600" style="font-size:.78rem">View</a>
                </div>
            </div>
            @empty
            <div class="text-center py-4 text-muted small">
                <i class="bi bi-check2-circle fs-3 text-success d-block mb-1"></i>
                No pending products waiting for review 🎉
            </div>
            @endforelse
        </div>
    </div>

    {{-- Recent Leads --}}
    <div class="col-lg-6">
        <div class="card-admin p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-800 mb-0 text-dark">Recent Leads Activity</h6>
                <a href="{{ route('admin.leads.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-600">View All</a>
            </div>
            @foreach($recentLeads as $lead)
            <div class="d-flex align-items-center gap-3 py-3 border-bottom">
                <span class="fs-4">{{ $lead->type==='call'?'📞':($lead->type==='whatsapp'?'💬':($lead->type==='direction'?'📍':'👁')) }}</span>
                <div class="flex-grow-1 min-w-0">
                    <div class="fw-700 small text-dark text-capitalize">{{ $lead->type }} Lead — {{ $lead->shop?->name }}</div>
                    <div class="text-muted" style="font-size:.78rem">{{ $lead->product?->name ?? 'Direct Shop Visit' }} · {{ $lead->created_at->diffForHumans() }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Recent Payments --}}
    <div class="col-lg-6">
        <div class="card-admin p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-800 mb-0 text-dark">Recent Payments</h6>
                <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-600">View All</a>
            </div>
            @foreach($recentPayments as $payment)
            <div class="d-flex align-items-center gap-3 py-3 border-bottom">
                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:40px;height:40px;background:#F0FDF4;color:#16A34A;font-size:1.1rem">💰</div>
                <div class="flex-grow-1 min-w-0">
                    <div class="fw-700 small text-dark">{{ $payment->user?->name }}</div>
                    <div class="text-muted" style="font-size:.78rem">{{ class_basename($payment->payable_type) }} · {{ $payment->created_at->diffForHumans() }}</div>
                </div>
                <div class="text-end">
                    <div class="fw-900 small text-dark">₹{{ number_format($payment->amount) }}</div>
                    <span class="badge badge-{{ $payment->status === 'completed' ? 'approved' : 'pending' }} rounded-pill px-2 py-1 fw-700" style="font-size:.7rem">{{ $payment->status }}</span>
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
            borderColor: '#4F46E5',
            backgroundColor: 'rgba(79,70,229,0.08)',
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#4F46E5',
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
