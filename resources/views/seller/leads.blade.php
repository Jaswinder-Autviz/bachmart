@extends('layouts.seller')
@section('title', 'In-Store Leads')
@section('page-title', 'Customer Leads & Enquiries')

@section('content')
{{-- ── 1. SUMMARY STAT CARDS ── --}}
<div class="row g-3 mb-4">
    @php
    $leadStats = [
        ['label'=>'Call Leads', 'value'=>$summary['call'], 'icon'=>'bi-telephone-fill', 'color'=>'#059669', 'bg'=>'#ECFDF5'],
        ['label'=>'WhatsApp Leads', 'value'=>$summary['whatsapp'], 'icon'=>'bi-whatsapp', 'color'=>'#25D366', 'bg'=>'#F0FDF4'],
        ['label'=>'Directions', 'value'=>$summary['direction'], 'icon'=>'bi-geo-alt-fill', 'color'=>'#EF4444', 'bg'=>'#FEF2F2'],
        ['label'=>'Product Views', 'value'=>$summary['view'], 'icon'=>'bi-eye-fill', 'color'=>'#6366F1', 'bg'=>'#EEF2FF']
    ];
    @endphp

    @foreach($leadStats as $card)
    <div class="col-6 col-md-3">
        <div class="seller-stat-card">
            <div class="seller-stat-icon" style="background:{{ $card['bg'] }}; color:{{ $card['color'] }};">
                <i class="bi {{ $card['icon'] }}"></i>
            </div>
            <div class="min-w-0">
                <div class="seller-stat-value text-truncate" style="color: {{ $card['color'] }};">
                    {{ $card['value'] }}
                </div>
                <div class="seller-stat-label text-truncate">{{ $card['label'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- ── 2. FILTER & TOOLBAR CARD ── --}}
<div class="card border-0 shadow-sm rounded-4 p-3 p-md-4 mb-4 bg-white" style="border: 1px solid var(--bm-border) !important;">
    <form method="GET" action="{{ route('seller.leads') }}" class="row g-3 align-items-end">
        <div class="col-6 col-md-4 col-lg-3">
            <label class="form-label small fw-700 text-dark mb-1">Lead Type</label>
            <select name="type" class="form-select form-select-sm rounded-3" onchange="this.form.submit()">
                <option value="">All Lead Types</option>
                <option value="call" {{ request('type')=='call'?'selected':'' }}>📞 Call Leads</option>
                <option value="whatsapp" {{ request('type')=='whatsapp'?'selected':'' }}>💬 WhatsApp Chats</option>
                <option value="direction" {{ request('type')=='direction'?'selected':'' }}>📍 Map Directions</option>
                <option value="view" {{ request('type')=='view'?'selected':'' }}>👁 Product Views</option>
            </select>
        </div>

        <div class="col-6 col-md-4 col-lg-3">
            <label class="form-label small fw-700 text-dark mb-1">Time Period</label>
            <select name="period" class="form-select form-select-sm rounded-3" onchange="this.form.submit()">
                <option value="">All Time History</option>
                <option value="today" {{ request('period')=='today'?'selected':'' }}>Today</option>
                <option value="week" {{ request('period')=='week'?'selected':'' }}>This Week</option>
                <option value="month" {{ request('period')=='month'?'selected':'' }}>This Month</option>
            </select>
        </div>

        @if(request('type') || request('period'))
        <div class="col-12 col-md-4 col-lg-2">
            <a href="{{ route('seller.leads') }}" class="btn btn-outline-secondary btn-sm w-100 rounded-pill fw-600">
                <i class="bi bi-x-circle me-1"></i>Reset Filters
            </a>
        </div>
        @endif
    </form>
</div>

{{-- ── 3. LEADS LIST / TABLE CARD ── --}}
@if($leads->count())
<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden" style="border: 1px solid var(--bm-border) !important;">
    <div class="p-3 px-md-4 border-bottom bg-light bg-opacity-50 d-flex align-items-center justify-content-between">
        <h6 class="fw-800 mb-0 text-dark">Recent In-Store Enquiries ({{ $leads->total() }})</h6>
        <span class="small text-muted">Direct customer interactions</span>
    </div>

    {{-- Desktop Table View --}}
    <div class="table-responsive d-none d-md-block">
        <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-3 text-uppercase fw-700 text-muted" style="font-size: 0.75rem; letter-spacing: 0.05em;">Interaction Type</th>
                    <th class="py-3 text-uppercase fw-700 text-muted" style="font-size: 0.75rem; letter-spacing: 0.05em;">Surplus Product</th>
                    <th class="py-3 text-uppercase fw-700 text-muted" style="font-size: 0.75rem; letter-spacing: 0.05em;">Customer</th>
                    <th class="py-3 text-uppercase fw-700 text-muted" style="font-size: 0.75rem; letter-spacing: 0.05em;">Date & Time</th>
                    <th class="pe-4 py-3 text-uppercase fw-700 text-muted text-end" style="font-size: 0.75rem; letter-spacing: 0.05em;">Details</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leads as $lead)
                <tr>
                    <td class="ps-4">
                        @php
                        $typeConfig = [
                            'call'=>['label'=>'Call Lead','bg'=>'#ECFDF5','color'=>'#065F46','border'=>'#A7F3D0','icon'=>'bi-telephone-fill'],
                            'whatsapp'=>['label'=>'WhatsApp Chat','bg'=>'#F0FDF4','color'=>'#166534','border'=>'#BBF7D0','icon'=>'bi-whatsapp'],
                            'direction'=>['label'=>'Map Direction','bg'=>'#FEF2F2','color'=>'#991B1B','border'=>'#FECACA','icon'=>'bi-geo-alt-fill'],
                            'view'=>['label'=>'Product View','bg'=>'#EEF2FF','color'=>'#3730A3','border'=>'#C7D2FE','icon'=>'bi-eye-fill']
                        ];
                        $tc = $typeConfig[$lead->type] ?? ['label'=>$lead->type,'bg'=>'#F1F5F9','color'=>'#475569','border'=>'#E2E8F0','icon'=>'bi-dot'];
                        @endphp
                        <span class="badge rounded-pill px-3 py-1 fw-700 d-inline-flex align-items-center gap-1.5"
                              style="background: {{ $tc['bg'] }}; color: {{ $tc['color'] }}; border: 1px solid {{ $tc['border'] }}; font-size: 0.75rem;">
                            <i class="bi {{ $tc['icon'] }}"></i> {{ $tc['label'] }}
                        </span>
                    </td>
                    <td>
                        @if($lead->product)
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $lead->product->primary_image_url }}" width="38" height="38" class="rounded-2 border flex-shrink-0" style="object-fit:cover"
                                 onerror="this.src='{{ asset('images/product-placeholder.svg') }}'">
                            <div>
                                <a href="{{ route('seller.products.show', $lead->product) }}" class="text-decoration-none fw-700 text-dark d-block text-truncate" style="max-width: 260px;">
                                    {{ $lead->product->name }}
                                </a>
                                <span class="text-muted small">₹{{ number_format($lead->product->offer_price) }}</span>
                            </div>
                        </div>
                        @else
                        <span class="badge bg-light text-secondary border px-2 py-1 rounded-pill">Direct Shop Visit</span>
                        @endif
                    </td>
                    <td>
                        @if($lead->customer)
                            <div class="fw-700 text-dark">{{ $lead->customer->name }}</div>
                            <div class="text-muted small">{{ $lead->customer->phone ?? $lead->customer->email }}</div>
                        @else
                            <span class="badge bg-light text-muted border px-2 py-1 rounded-pill">Local Guest Shopper</span>
                        @endif
                    </td>
                    <td>
                        <div class="fw-600 text-dark small">{{ $lead->created_at->format('d M Y, h:i A') }}</div>
                        <div class="text-muted small" style="font-size: 0.75rem;">{{ $lead->created_at->diffForHumans() }}</div>
                    </td>
                    <td class="pe-4 text-end">
                        <span class="badge bg-light text-muted border rounded-pill" style="font-size: 0.72rem;">{{ $lead->ip_address }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile Card List View (No Overflow, App Experience) --}}
    <div class="d-md-none p-3">
        <div class="d-flex flex-column gap-3">
            @foreach($leads as $lead)
            @php
            $typeConfigMobile = [
                'call'=>['label'=>'Call Lead','bg'=>'#ECFDF5','color'=>'#065F46','border'=>'#A7F3D0','icon'=>'bi-telephone-fill'],
                'whatsapp'=>['label'=>'WhatsApp Chat','bg'=>'#F0FDF4','color'=>'#166534','border'=>'#BBF7D0','icon'=>'bi-whatsapp'],
                'direction'=>['label'=>'Map Direction','bg'=>'#FEF2F2','color'=>'#991B1B','border'=>'#FECACA','icon'=>'bi-geo-alt-fill'],
                'view'=>['label'=>'Product View','bg'=>'#EEF2FF','color'=>'#3730A3','border'=>'#C7D2FE','icon'=>'bi-eye-fill']
            ];
            $tcm = $typeConfigMobile[$lead->type] ?? ['label'=>$lead->type,'bg'=>'#F1F5F9','color'=>'#475569','border'=>'#E2E8F0','icon'=>'bi-dot'];
            @endphp
            <div class="p-3 rounded-4 border bg-white shadow-sm" style="border-color: var(--bm-border) !important;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="badge rounded-pill px-2.5 py-1 fw-700 d-inline-flex align-items-center gap-1"
                          style="background: {{ $tcm['bg'] }}; color: {{ $tcm['color'] }}; border: 1px solid {{ $tcm['border'] }}; font-size: 0.72rem;">
                        <i class="bi {{ $tcm['icon'] }}"></i> {{ $tcm['label'] }}
                    </span>
                    <span class="text-muted small" style="font-size: 0.75rem;">{{ $lead->created_at->diffForHumans() }}</span>
                </div>

                @if($lead->product)
                <div class="d-flex align-items-center gap-2 mb-2">
                    <img src="{{ $lead->product->primary_image_url }}" width="44" height="44" class="rounded-3 border flex-shrink-0" style="object-fit:cover"
                         onerror="this.src='{{ asset('images/product-placeholder.svg') }}'">
                    <div class="min-w-0">
                        <a href="{{ route('seller.products.show', $lead->product) }}" class="text-decoration-none fw-700 text-dark text-truncate d-block" style="font-size: 0.88rem;">
                            {{ $lead->product->name }}
                        </a>
                        <span class="text-primary-bm fw-700 small">₹{{ number_format($lead->product->offer_price) }}</span>
                    </div>
                </div>
                @else
                <div class="text-muted small mb-2"><i class="bi bi-shop me-1"></i>Direct In-Store Inquiry</div>
                @endif

                <div class="d-flex justify-content-between align-items-center pt-2 border-top small text-muted">
                    <span>Customer: <strong class="text-dark">{{ $lead->customer->name ?? 'Guest User' }}</strong></span>
                    <span>{{ $lead->created_at->format('h:i A') }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $leads->links() }}
</div>
@else
<div class="text-center py-5 card border-0 shadow-sm rounded-4 p-5 bg-white" style="border: 1px solid var(--bm-border) !important;">
    <div style="font-size: 3.5rem">📊</div>
    <h5 class="fw-800 text-dark mt-3 mb-1">No customer leads recorded yet</h5>
    <p class="text-muted small mb-0">Once shoppers view, call, or WhatsApp you about your deals, their interactions will be tracked here in real-time.</p>
</div>
@endif

@endsection
