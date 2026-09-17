@extends('layouts.seller')
@section('title', 'Seller Dashboard')
@section('page-title', 'Seller Dashboard')

@section('content')
@php $shop = auth()->user()->shop; @endphp

{{-- Header Banner & Action --}}
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-800 mb-1 text-dark">Welcome back, {{ Str::words(auth()->user()->name, 1, '') }}! 👋</h4>
        <p class="text-muted small mb-0">Manage your Surplus Stock inventory, customer enquiries, and in-store leads.</p>
    </div>
    <a href="{{ route('seller.products.create') }}" class="btn btn-primary-bm px-4 py-2 fs-6">
        <i class="bi bi-plus-circle-fill me-1"></i>+ LIST Surplus Stock
    </a>
</div>

{{-- Subscription Status Banner --}}
@if(!$subscription)
<div class="card border-0 shadow-sm rounded-4 p-3 mb-4 d-flex flex-row align-items-center justify-content-between gap-3 flex-wrap"
     style="background:linear-gradient(135deg,#FFF7ED 0%,#FFF0EB 100%);border:1px solid #FFD3C4 !important">
    <div class="d-flex align-items-center gap-3">
        <div class="rounded-circle bg-white text-warning fs-4 d-flex align-items-center justify-content-center shadow-sm" style="width:44px;height:44px">
            <i class="bi bi-star-fill"></i>
        </div>
        <div>
            <div class="fw-700 text-dark">You are on the <span class="text-primary-bm">Free Basic Plan</span></div>
            <div class="text-muted small">List up to 5 Surplus Stock items. Upgrade for unlimited inventory and featured homepage spots.</div>
        </div>
    </div>
    <a href="{{ route('seller.subscription.index') }}" class="btn btn-primary-bm btn-sm px-3 rounded-pill">
        Upgrade Plan <i class="bi bi-arrow-right ms-1"></i>
    </a>
</div>
@else
<div class="card border-0 shadow-sm rounded-4 p-3 mb-4 d-flex flex-row align-items-center justify-content-between gap-3 flex-wrap"
     style="background:#ECFDF5;border:1px solid #A7F3D0 !important">
    <div class="d-flex align-items-center gap-3">
        <div class="rounded-circle bg-white text-success fs-4 d-flex align-items-center justify-content-center shadow-sm" style="width:44px;height:44px">
            <i class="bi bi-patch-check-fill text-success"></i>
        </div>
        <div>
            <div class="fw-700 text-dark">
                <strong>{{ $subscription->subscriptionPlan->name }} Plan</strong> is Active
            </div>
            <div class="text-muted small">
                Valid until {{ $subscription->expires_at?->format('d M Y') }} ({{ $subscription->days_remaining }} days remaining)
            </div>
        </div>
    </div>
    <a href="{{ route('seller.subscription.index') }}" class="btn btn-outline-success btn-sm px-3 rounded-pill">
        Manage Subscription
    </a>
</div>
@endif

{{-- ── 10 KEY METRIC STAT CARDS (Requirement 7) ── --}}
<div class="row g-3 mb-4">
    @php
    $statCards = [
        ['label'=>'Total Products', 'value'=>$stats['total_products'], 'icon'=>'bi-box-seam', 'color'=>'#6366F1', 'bg'=>'#EEF2FF'],
        ['label'=>'Live Products', 'value'=>$stats['active_products'], 'icon'=>'bi-check-circle-fill', 'color'=>'#10B981', 'bg'=>'#ECFDF5'],
        ['label'=>'Pending Approval', 'value'=>$stats['pending_products'], 'icon'=>'bi-hourglass-split', 'color'=>'#F59E0B', 'bg'=>'#FFFBEB'],
        ['label'=>'Sold Out', 'value'=>$stats['sold_out'] ?? 0, 'icon'=>'bi-bag-check-fill', 'color'=>'#64748B', 'bg'=>'#F1F5F9'],
        ['label'=>'Product Views', 'value'=>number_format($stats['total_views']), 'icon'=>'bi-eye-fill', 'color'=>'#8B5CF6', 'bg'=>'#F5F3FF'],
        ['label'=>'Call Clicks', 'value'=>$stats['total_calls'], 'icon'=>'bi-telephone-fill', 'color'=>'#059669', 'bg'=>'#ECFDF5'],
        ['label'=>'WhatsApp Clicks', 'value'=>$stats['total_whatsapp'], 'icon'=>'bi-whatsapp', 'color'=>'#25D366', 'bg'=>'#F0FDF4'],
        ['label'=>'Direction Requests', 'value'=>$stats['total_directions'], 'icon'=>'bi-map-fill', 'color'=>'#EF4444', 'bg'=>'#FEF2F2'],
        ['label'=>'Featured Products', 'value'=>$stats['featured_products'], 'icon'=>'bi-star-fill', 'color'=>'#FF5722', 'bg'=>'#FFF0EB'],
        ['label'=>'Current Plan', 'value'=>$subscription ? $subscription->subscriptionPlan->name : 'Free', 'icon'=>'bi-credit-card-2-front-fill', 'color'=>'#0EA5E9', 'bg'=>'#F0F9FF', 'is_text'=>true],
    ];
    @endphp

    @foreach($statCards as $card)
    <div class="col-6 col-md-4 col-lg-2-4" style="flex: 0 0 auto; width: 20%;">
        <div class="seller-stat-card">
            <div class="seller-stat-icon" style="background:{{ $card['bg'] }};color:{{ $card['color'] }}">
                <i class="bi {{ $card['icon'] }}"></i>
            </div>
            <div class="min-w-0">
                <div class="seller-stat-value text-truncate" style="{{ isset($card['is_text']) ? 'font-size:1.2rem;font-weight:700;' : '' }}">
                    {{ $card['value'] }}
                </div>
                <div class="seller-stat-label text-truncate">{{ $card['label'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-4">
    {{-- Recent Surplus Stock Listings --}}
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-800 mb-0 text-dark">Recent Surplus Stock Deals</h6>
                <a href="{{ route('seller.products.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    View All
                </a>
            </div>

            @forelse($recentProducts as $product)
            <div class="d-flex align-items-center gap-3 py-3 border-bottom">
                <img src="{{ $product->primary_image_url }}" width="56" height="56"
                     class="rounded-3 flex-shrink-0 border" style="object-fit:cover"
                     onerror="this.onerror=null; this.src='{{ asset('images/product-placeholder.svg') }}';">
                <div class="flex-grow-1 min-w-0">
                    <div class="fw-700 text-dark text-truncate">{{ $product->name }}</div>
                    <div class="text-muted small mt-1">
                        <span class="text-primary-bm fw-700">₹{{ number_format($product->offer_price) }}</span>
                        <span class="text-decoration-line-through ms-1">₹{{ number_format($product->original_price) }}</span>
                        · {{ $product->category->name ?? '' }}
                    </div>
                </div>
                <div class="text-end flex-shrink-0">
                    <span class="badge badge-status-{{ $product->status }} rounded-pill text-capitalize px-2 py-1"
                          style="font-size:0.75rem">
                        {{ str_replace('_', ' ', $product->status) }}
                    </span>
                    <div class="text-muted mt-1" style="font-size:0.75rem">
                        {{ $product->views_count }} views
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <div class="fs-1">📦</div>
                <p class="text-muted small mt-2">You haven't listed any Surplus Stock yet.</p>
                <a href="{{ route('seller.products.create') }}" class="btn btn-primary-bm btn-sm rounded-pill px-4">
                    + List Surplus Stock Now
                </a>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Recent Customer Leads & Contacts --}}
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-800 mb-0 text-dark">Recent In-Store Leads</h6>
                <a href="{{ route('seller.leads') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    All Leads
                </a>
            </div>

            @forelse($recentLeads as $lead)
            <div class="d-flex align-items-center gap-3 py-3 border-bottom">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 fs-5"
                     style="width:40px;height:40px;background:{{ $lead->type==='call'?'#ECFDF5':($lead->type==='whatsapp'?'#F0FDF4':($lead->type==='direction'?'#FEF2F2':'#EEF2FF')) }}">
                    {{ $lead->type==='call'?'📞':($lead->type==='whatsapp'?'💬':($lead->type==='direction'?'📍':'👁')) }}
                </div>
                <div class="flex-grow-1 min-w-0">
                    <div class="small fw-700 text-dark text-capitalize">
                        {{ $lead->type }} Lead
                    </div>
                    <div class="text-muted text-truncate" style="font-size:0.8rem">
                        {{ $lead->product?->name ?? 'Direct Shop Enquiry' }}
                    </div>
                </div>
                <div class="text-muted small text-end" style="font-size:0.75rem">
                    {{ $lead->created_at->diffForHumans() }}
                </div>
            </div>
            @empty
            <div class="text-center py-5 text-muted small">
                <i class="bi bi-bell-slash fs-2 mb-2 d-block text-secondary"></i>
                No leads recorded yet. Once your Surplus Stock is approved, customers will call and message you!
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Quick Navigation Bar --}}
<div class="card border-0 shadow-sm rounded-4 p-3 mt-4 bg-white">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <span class="small fw-700 text-dark">Quick Navigation:</span>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('seller.products.create') }}" class="btn btn-primary-bm btn-sm rounded-pill px-3">
                <i class="bi bi-plus-circle me-1"></i>List Surplus Stock
            </a>
            <a href="{{ route('seller.shop.edit') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-pencil-square me-1"></i>Edit Shop Info
            </a>
            <a href="{{ route('seller.leads') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-telephone-outbound me-1"></i>Lead Analytics
            </a>
            <a href="{{ route('shop.show', $shop->slug) }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-box-arrow-up-right me-1"></i>View Live Public Shop
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@media (max-width: 1199.98px) {
    .col-lg-2-4 {
        width: 33.333% !important;
    }
}
@media (max-width: 767.98px) {
    .col-lg-2-4 {
        width: 50% !important;
    }
}
</style>
@endpush
