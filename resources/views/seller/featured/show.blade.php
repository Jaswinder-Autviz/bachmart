@extends('layouts.seller')
@section('title', 'Feature Product')
@section('page-title', 'Feature This Product')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">

@if($activeFeatured && $activeFeatured->isActive())
<div class="alert alert-success mb-4 rounded-4 border-0 shadow-sm">
    <i class="bi bi-star-fill me-2"></i>
    This product is currently featured until <strong>{{ $activeFeatured->expires_at?->format('d M Y') }}</strong>.
</div>
@endif

{{-- Product preview --}}
<div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white" style="border: 1px solid var(--bm-border) !important;">
    <div class="d-flex align-items-center gap-3">
        <img src="{{ $product->primary_image_url }}" width="72" height="72" class="rounded-3 border" style="object-fit:cover">
        <div>
            <h6 class="fw-700 text-dark mb-1">{{ $product->name }}</h6>
            <div class="fw-700" style="color:#FF5722">₹{{ number_format($product->offer_price) }}
                <span class="badge bg-success-subtle text-success rounded-pill ms-1 px-2 py-0.5" style="font-size: 0.72rem;">{{ round($product->discount_percent) }}% OFF</span>
            </div>
            <div class="text-muted small">{{ $product->category->name }} · {{ $product->shop->name }}</div>
        </div>
    </div>
</div>

<h6 class="fw-700 text-dark mb-3">Choose a Featured Package</h6>

<form action="{{ route('seller.featured.store', $product) }}" method="POST">
@csrf
<div class="row g-3">
    @foreach($packages as $key => $package)
    <div class="col-md-4">
        <label class="d-block cursor-pointer" style="cursor:pointer">
            <input type="radio" name="package" value="{{ $key }}" class="d-none package-radio"
                   {{ $key === '7days' ? 'checked' : '' }}>
            <div class="card border-2 rounded-4 p-4 text-center package-card h-100 bg-white shadow-sm
                        {{ $key === '7days' ? 'border-primary' : 'border-light' }}"
                 style="transition:all .2s; border-color: {{ $key === '7days' ? '#FF5722 !important;' : '#E2E8F0 !important;' }}">
                @if($package['popular'])
                    <span class="badge mb-2 mx-auto rounded-pill px-3 py-1" style="background:#FF5722;color:#fff;font-size: 0.72rem;">Best Value</span>
                @endif
                <div class="fw-600 mb-1 text-dark">{{ $package['label'] }} Featured</div>
                <div class="fs-3 fw-800" style="color:#FF5722">₹{{ $package['price'] }}</div>
                <div class="text-muted small mt-2">₹{{ round($package['price']/$package['days'],1) }}/day</div>
            </div>
        </label>
    </div>
    @endforeach
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 mt-4 bg-white" style="border: 1px solid var(--bm-border) !important;">
    <h6 class="fw-700 text-dark mb-3">What you get with Featured:</h6>
    <div class="row g-2">
        @foreach(['<i class="bi bi-star-fill text-warning me-1"></i> "Featured" badge on your product card','🔝 Priority placement in search results','📈 Higher visibility = more customer leads','💰 Proven to increase call & WhatsApp clicks'] as $benefit)
        <div class="col-md-6 d-flex align-items-center gap-2 small text-muted"><i class="bi bi-check-circle-fill text-success"></i><span>{!! $benefit !!}</span></div>
        @endforeach
    </div>
</div>

<div class="d-flex gap-3 justify-content-end mt-4 mb-5">
    <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-600">Cancel</a>
    <button type="submit" class="btn btn-primary-bm px-5 rounded-pill fw-600">
        <i class="bi bi-star-fill me-1"></i>Feature This Product
    </button>
</div>
</form>
</div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.package-radio').forEach(radio => {
    radio.addEventListener('change', () => {
        document.querySelectorAll('.package-card').forEach(c => {
            c.classList.remove('border-warning');
            c.classList.add('border-light');
        });
        radio.closest('label').querySelector('.package-card').classList.remove('border-light');
        radio.closest('label').querySelector('.package-card').classList.add('border-warning');
    });
});
</script>
@endpush
