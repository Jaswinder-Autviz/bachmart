@extends('layouts.seller')
@section('title', 'Analytics')
@section('page-title', 'Performance Analytics')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')
{{-- ── 1. SUMMARY METRIC CARDS ── --}}
<div class="row g-3 mb-4">
    @php
    $analyticsStats = [
        ['label'=>'Total Leads', 'value'=>$stats['total_leads'], 'icon'=>'bi-people-fill', 'color'=>'#4F46E5', 'bg'=>'#EEF2FF'],
        ['label'=>'Call Leads', 'value'=>$stats['call_leads'], 'icon'=>'bi-telephone-fill', 'color'=>'#059669', 'bg'=>'#ECFDF5'],
        ['label'=>'WhatsApp Leads', 'value'=>$stats['whatsapp_leads'], 'icon'=>'bi-whatsapp', 'color'=>'#25D366', 'bg'=>'#F0FDF4'],
        ['label'=>'Direction Clicks', 'value'=>$stats['direction_leads'], 'icon'=>'bi-geo-alt-fill', 'color'=>'#EF4444', 'bg'=>'#FEF2F2'],
    ];
    @endphp
    @foreach($analyticsStats as $card)
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

<div class="row g-4">
    {{-- Monthly Lead Trends Chart --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100" style="border: 1px solid var(--bm-border) !important;">
            <h6 class="fw-800 mb-3 text-dark">Monthly In-Store Lead Trends</h6>
            <div style="position: relative; min-height: 240px;">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Lead Breakdown Chart --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100" style="border: 1px solid var(--bm-border) !important;">
            <h6 class="fw-800 mb-3 text-dark">Lead Source Breakdown</h6>
            <div style="position: relative; max-height: 200px;" class="d-flex justify-content-center mb-3">
                <canvas id="pieChart"></canvas>
            </div>
            <div class="mt-2">
                @php $total = $stats['total_leads'] ?: 1; @endphp
                @foreach([
                    ['label'=>'Calls', 'value'=>$stats['call_leads'], 'color'=>'#059669', 'icon'=>'bi-telephone-fill'],
                    ['label'=>'WhatsApp', 'value'=>$stats['whatsapp_leads'], 'color'=>'#25D366', 'icon'=>'bi-whatsapp'],
                    ['label'=>'Directions', 'value'=>$stats['direction_leads'], 'color'=>'#EF4444', 'icon'=>'bi-geo-alt-fill'],
                    ['label'=>'Views', 'value'=>$stats['view_leads'], 'color'=>'#4F46E5', 'icon'=>'bi-eye-fill']
                ] as $item)
                <div class="d-flex justify-content-between align-items-center small py-2 border-bottom">
                    <span class="fw-600 text-dark d-flex align-items-center gap-1.5">
                        <i class="bi {{ $item['icon'] }}" style="color: {{ $item['color'] }};"></i>
                        {{ $item['label'] }}
                    </span>
                    <span class="fw-800 text-dark">{{ $item['value'] }} <span class="text-muted fw-500" style="font-size: 0.75rem;">({{ round(($item['value']/$total)*100) }}%)</span></span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Top Performing Products --}}
@if($stats['top_products']->count())
<div class="card border-0 shadow-sm rounded-4 mt-4 bg-white overflow-hidden" style="border: 1px solid var(--bm-border) !important;">
    <div class="p-3 px-md-4 border-bottom bg-light bg-opacity-50">
        <h6 class="fw-800 mb-0 text-dark">Top Performing Surplus Stock Items</h6>
    </div>

    {{-- Desktop Table --}}
    <div class="table-responsive d-none d-md-block">
        <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-3 text-uppercase fw-700 text-muted" style="font-size: 0.75rem;">Product</th>
                    <th class="py-3 text-uppercase fw-700 text-muted" style="font-size: 0.75rem;">Views</th>
                    <th class="py-3 text-uppercase fw-700 text-muted" style="font-size: 0.75rem;">Calls</th>
                    <th class="py-3 text-uppercase fw-700 text-muted" style="font-size: 0.75rem;">WhatsApp</th>
                    <th class="pe-4 py-3 text-uppercase fw-700 text-muted text-end" style="font-size: 0.75rem;">Directions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stats['top_products'] as $product)
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $product->primary_image_url }}" width="42" height="42"
                                 class="rounded-3 border" style="object-fit:cover"
                                 onerror="this.src='{{ asset('images/product-placeholder.svg') }}'">
                            <div>
                                <div class="fw-700 text-dark">{{ Str::limit($product->name, 35) }}</div>
                                <div class="text-muted small">₹{{ number_format($product->offer_price) }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="fw-700">{{ number_format($product->views_count) }}</td>
                    <td class="fw-700 text-success">{{ $product->calls_count }}</td>
                    <td class="fw-700" style="color: #25D366">{{ $product->whatsapp_count }}</td>
                    <td class="pe-4 text-end fw-700 text-danger">{{ $product->directions_count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile Cards --}}
    <div class="d-md-none p-3">
        <div class="d-flex flex-column gap-3">
            @foreach($stats['top_products'] as $product)
            <div class="p-3 rounded-4 border bg-white shadow-sm" style="border-color: var(--bm-border) !important;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <img src="{{ $product->primary_image_url }}" width="44" height="44"
                         class="rounded-3 border flex-shrink-0" style="object-fit:cover"
                         onerror="this.src='{{ asset('images/product-placeholder.svg') }}'">
                    <div class="min-w-0">
                        <div class="fw-800 text-dark text-truncate" style="font-size: 0.9rem;">{{ $product->name }}</div>
                        <div class="text-primary-bm fw-700 small">₹{{ number_format($product->offer_price) }}</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between pt-2 border-top small text-muted">
                    <span><i class="bi bi-eye text-primary-bm me-1"></i>{{ $product->views_count }}</span>
                    <span><i class="bi bi-telephone text-success me-1"></i>{{ $product->calls_count }}</span>
                    <span><i class="bi bi-whatsapp text-success me-1"></i>{{ $product->whatsapp_count }}</span>
                    <span><i class="bi bi-geo-alt text-danger me-1"></i>{{ $product->directions_count }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
const monthlyData = @json($stats['monthly_leads']);
const labels = monthlyData.map(d => d.month);
const callData = monthlyData.map(d => d.call);
const waData = monthlyData.map(d => d.whatsapp);
const dirData = monthlyData.map(d => d.direction);

new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [
            { label: 'WhatsApp', data: waData, backgroundColor: '#25D366', borderRadius: 6 },
            { label: 'Calls', data: callData, backgroundColor: '#059669', borderRadius: 6 },
            { label: 'Directions', data: dirData, backgroundColor: '#EF4444', borderRadius: 6 }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'top' } },
        scales: {
            x: { stacked: true, grid: { display: false } },
            y: { stacked: true, beginAtZero: true }
        }
    }
});

new Chart(document.getElementById('pieChart'), {
    type: 'doughnut',
    data: {
        labels: ['Calls', 'WhatsApp', 'Directions', 'Views'],
        datasets: [{
            data: [{{ $stats['call_leads'] }}, {{ $stats['whatsapp_leads'] }}, {{ $stats['direction_leads'] }}, {{ $stats['view_leads'] }}],
            backgroundColor: ['#059669', '#25D366', '#EF4444', '#4F46E5'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        cutout: '70%'
    }
});
</script>
@endpush
