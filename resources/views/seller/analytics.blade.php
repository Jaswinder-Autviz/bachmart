@extends('layouts.seller')
@section('title', 'Analytics')
@section('page-title', 'Analytics')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')
{{-- Stats --}}
<div class="row g-3 mb-4">
    @foreach([
        ['label'=>'Total Leads','value'=>$stats['total_leads'],'icon'=>'bi-people','color'=>'#5a67d8','bg'=>'#ebf4ff'],
        ['label'=>'Call Leads','value'=>$stats['call_leads'],'icon'=>'bi-telephone-fill','color'=>'#48BB78','bg'=>'#f0fff4'],
        ['label'=>'WhatsApp Leads','value'=>$stats['whatsapp_leads'],'icon'=>'bi-whatsapp','color'=>'#25d366','bg'=>'#f0fff4'],
        ['label'=>'Direction Leads','value'=>$stats['direction_leads'],'icon'=>'bi-map','color'=>'#ed8936','bg'=>'#fffaf0'],
    ] as $card)
    <div class="col-6 col-md-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon flex-shrink-0" style="background:{{ $card['bg'] }};color:{{ $card['color'] }}"><i class="bi {{ $card['icon'] }}"></i></div>
            <div><div class="stat-value" style="color:{{ $card['color'] }}">{{ $card['value'] }}</div><div class="stat-label">{{ $card['label'] }}</div></div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-4">
    {{-- Monthly Chart --}}
    <div class="col-lg-8">
        <div class="card-bm p-4">
            <h6 class="fw-700 mb-3">Monthly Lead Trends (Last 6 Months)</h6>
            <canvas id="monthlyChart" height="100"></canvas>
        </div>
    </div>
    {{-- Breakdown --}}
    <div class="col-lg-4">
        <div class="card-bm p-4 h-100">
            <h6 class="fw-700 mb-3">Lead Breakdown</h6>
            <canvas id="pieChart" height="200"></canvas>
            <div class="mt-3">
                @php $total = $stats['total_leads'] ?: 1; @endphp
                @foreach([['label'=>'Calls','value'=>$stats['call_leads'],'color'=>'#48BB78'],['label'=>'WhatsApp','value'=>$stats['whatsapp_leads'],'color'=>'#25d366'],['label'=>'Directions','value'=>$stats['direction_leads'],'color'=>'#ed8936'],['label'=>'Views','value'=>$stats['view_leads'],'color'=>'#5a67d8']] as $item)
                <div class="d-flex justify-content-between small py-1 border-bottom">
                    <span><span class="d-inline-block rounded-circle me-2" style="width:10px;height:10px;background:{{ $item['color'] }}"></span>{{ $item['label'] }}</span>
                    <span class="fw-600">{{ $item['value'] }} ({{ round(($item['value']/$total)*100) }}%)</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Top Products --}}
@if($stats['top_products']->count())
<div class="card-bm p-4 mt-4">
    <h6 class="fw-700 mb-3">Top Performing Products</h6>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.875rem">
            <thead style="background:#f7f8fa">
                <tr>
                    <th class="ps-3 py-3">Product</th>
                    <th>Views</th>
                    <th>Calls</th>
                    <th>WhatsApp</th>
                    <th>Directions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stats['top_products'] as $product)
                <tr>
                    <td class="ps-3">
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $product->primary_image_url }}" width="40" height="40"
                                 class="rounded-2" style="object-fit:cover">
                            <div>
                                <div class="fw-600">{{ Str::limit($product->name,35) }}</div>
                                <div class="text-muted" style="font-size:.75rem">₹{{ number_format($product->offer_price) }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="fw-600">{{ number_format($product->views_count) }}</td>
                    <td class="fw-600 text-success">{{ $product->calls_count }}</td>
                    <td class="fw-600" style="color:#25d366">{{ $product->whatsapp_count }}</td>
                    <td class="fw-600 text-warning">{{ $product->directions_count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
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
        labels,
        datasets: [
            { label: 'Calls', data: callData, backgroundColor: '#48BB78', borderRadius: 4 },
            { label: 'WhatsApp', data: waData, backgroundColor: '#25d366', borderRadius: 4 },
            { label: 'Directions', data: dirData, backgroundColor: '#ed8936', borderRadius: 4 },
        ]
    },
    options: { responsive: true, plugins: { legend: { position: 'top' } }, scales: { x: { stacked: false }, y: { beginAtZero: true, ticks: { precision: 0 } } } }
});

new Chart(document.getElementById('pieChart'), {
    type: 'doughnut',
    data: {
        labels: ['Calls', 'WhatsApp', 'Directions', 'Views'],
        datasets: [{
            data: [{{ $stats['call_leads'] }}, {{ $stats['whatsapp_leads'] }}, {{ $stats['direction_leads'] }}, {{ $stats['view_leads'] }}],
            backgroundColor: ['#48BB78','#25d366','#ed8936','#5a67d8'],
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, cutout: '65%' }
});
</script>
@endpush
