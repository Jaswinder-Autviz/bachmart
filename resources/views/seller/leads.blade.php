@extends('layouts.seller')
@section('title', 'Leads')
@section('page-title', 'Customer Leads')

@section('content')
{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    @foreach([['label'=>'Call Leads','value'=>$summary['call'],'icon'=>'bi-telephone-fill','color'=>'#48BB78','bg'=>'#f0fff4'],['label'=>'WhatsApp Leads','value'=>$summary['whatsapp'],'icon'=>'bi-whatsapp','color'=>'#25d366','bg'=>'#f0fff4'],['label'=>'Directions','value'=>$summary['direction'],'icon'=>'bi-map','color'=>'#ed8936','bg'=>'#fffaf0'],['label'=>'Product Views','value'=>$summary['view'],'icon'=>'bi-eye','color'=>'#5a67d8','bg'=>'#ebf4ff']] as $card)
    <div class="col-6 col-md-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon flex-shrink-0" style="background:{{ $card['bg'] }};color:{{ $card['color'] }}"><i class="bi {{ $card['icon'] }}"></i></div>
            <div><div class="stat-value" style="color:{{ $card['color'] }}">{{ $card['value'] }}</div><div class="stat-label">{{ $card['label'] }}</div></div>
        </div>
    </div>
    @endforeach
</div>

{{-- Filters --}}
<div class="card-bm p-3 mb-4">
    <form method="GET" action="{{ route('seller.leads') }}" class="d-flex gap-2 flex-wrap align-items-end">
        <div>
            <label class="form-label small fw-600 mb-1">Type</label>
            <select name="type" class="form-select form-select-sm" onchange="this.form.submit()" style="min-width:130px">
                <option value="">All Types</option>
                <option value="call" {{ request('type')=='call'?'selected':'' }}>📞 Call</option>
                <option value="whatsapp" {{ request('type')=='whatsapp'?'selected':'' }}>💬 WhatsApp</option>
                <option value="direction" {{ request('type')=='direction'?'selected':'' }}>📍 Direction</option>
                <option value="view" {{ request('type')=='view'?'selected':'' }}>👁 View</option>
            </select>
        </div>
        <div>
            <label class="form-label small fw-600 mb-1">Period</label>
            <select name="period" class="form-select form-select-sm" onchange="this.form.submit()" style="min-width:130px">
                <option value="">All Time</option>
                <option value="today" {{ request('period')=='today'?'selected':'' }}>Today</option>
                <option value="week" {{ request('period')=='week'?'selected':'' }}>This Week</option>
                <option value="month" {{ request('period')=='month'?'selected':'' }}>This Month</option>
            </select>
        </div>
        @if(request('type') || request('period'))
        <a href="{{ route('seller.leads') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
        @endif
    </form>
</div>

{{-- Table --}}
@if($leads->count())
<div class="card-bm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.875rem">
            <thead style="background:#f7f8fa">
                <tr>
                    <th class="ps-3 py-3">Type</th>
                    <th>Product</th>
                    <th>Customer</th>
                    <th>Date & Time</th>
                    <th class="pe-3">IP</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leads as $lead)
                <tr>
                    <td class="ps-3">
                        @php
                        $typeConfig = ['call'=>['label'=>'Call','bg'=>'#f0fff4','color'=>'#276749','icon'=>'📞'],'whatsapp'=>['label'=>'WhatsApp','bg'=>'#e6fffa','color'=>'#234e52','icon'=>'💬'],'direction'=>['label'=>'Direction','bg'=>'#fff5f5','color'=>'#9b2c2c','icon'=>'📍'],'view'=>['label'=>'View','bg'=>'#ebf8ff','color'=>'#2c5282','icon'=>'👁']];
                        $tc = $typeConfig[$lead->type] ?? ['label'=>$lead->type,'bg'=>'#f0f0f0','color'=>'#666','icon'=>'•'];
                        @endphp
                        <span class="badge rounded-pill px-2" style="background:{{ $tc['bg'] }};color:{{ $tc['color'] }}">
                            {{ $tc['icon'] }} {{ $tc['label'] }}
                        </span>
                    </td>
                    <td>
                        @if($lead->product)
                        <a href="{{ route('seller.products.show', $lead->product) }}" class="text-decoration-none fw-600 text-dark">
                            {{ Str::limit($lead->product->name, 35) }}
                        </a>
                        @else
                        <span class="text-muted">Shop Visit</span>
                        @endif
                    </td>
                    <td>
                        @if($lead->customer)
                            <span class="fw-600">{{ $lead->customer->name }}</span>
                        @else
                            <span class="text-muted small">Guest</span>
                        @endif
                    </td>
                    <td class="text-muted small">{{ $lead->created_at->format('d M Y, h:i A') }}</td>
                    <td class="pe-3 text-muted small">{{ $lead->ip_address }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">{{ $leads->links() }}</div>
@else
<div class="text-center py-5 card-bm">
    <div style="font-size:3rem">📊</div>
    <h5 class="fw-700 mt-3">No leads yet</h5>
    <p class="text-muted">Leads are recorded when customers call, WhatsApp, or request directions.</p>
</div>
@endif
@endsection
