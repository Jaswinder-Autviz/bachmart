@extends('layouts.seller')
@section('title', 'Feature Product')
@section('page-title', 'Feature This Product')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">

@if($activeFeatured && $activeFeatured->isActive())
<div class="alert alert-success mb-4">
    <i class="bi bi-star-fill me-2"></i>
    This product is currently featured until <strong>{{ $activeFeatured->expires_at?->format('d M Y') }}</strong>.
</div>
@endif

{{-- Product preview --}}
<div class="card-bm p-4 mb-4">
    <div class="d-flex align-items-center gap-3">
        <img src="{{ $product->primary_image_url }}" width="80" height="80" class="rounded-2" style="object-fit:cover">
        <div>
            <h6 class="fw-700 mb-1">{{ $product->name }}</h6>
            <div class="fw-700" style="color:#FF6B35">₹{{ number_format($product->offer_price) }}
                <span class="badge bg-success ms-1">{{ round($product->discount_percent) }}% OFF</span>
            </div>
            <div class="text-muted small">{{ $product->category->name }} · {{ $product->shop->name }}</div>
        </div>
    </div>
</div>

<h5 class="fw-700 mb-4">Choose a Featured Package</h5>

<form action="{{ route('seller.featured.store', $product) }}" method="POST">
@csrf
<div class="row g-3">
    @foreach($packages as $key => $package)
    <div class="col-md-4">
        <label class="d-block cursor-pointer" style="cursor:pointer">
            <input type="radio" name="package" value="{{ $key }}" class="d-none package-radio"
                   {{ $key === '7days' ? 'checked' : '' }}>
            <div class="card border-2 rounded-3 p-4 text-center package-card h-100
                        {{ $key === '7days' ? 'border-warning' : 'border-light' }}"
                 style="transition:all .2s">
                @if($package['popular'])
                    <span class="badge mb-2" style="background:#FF6B35;color:#fff">Best Value</span>
                @endif
                <div class="fw-600 mb-1">{{ $package['label'] }} Featured</div>
                <div style="font-size:1.8rem;font-weight:800;color:#FF6B35">₹{{ $package['price'] }}</div>
                <div class="text-muted small mt-2">₹{{ round($package['price']/$package['days'],1) }}/day</div>
            </div>
        </label>
    </div>
    @endforeach
</div>

<div class="card-bm p-4 mt-4">
    <h6 class="fw-700 mb-3">What you get with Featured:</h6>
    <div class="row g-2">
        @foreach(['<i class="bi bi-star-fill me-1"></i> "Featured" badge on your product card','🔝 Priority placement in search results','📈 Higher visibility = more customer leads','💰 Proven to increase call & WhatsApp clicks'] as $benefit)
        <div class="col-md-6 d-flex align-items-center gap-2 small"><i class="bi bi-check-circle-fill text-success"></i>{{ $benefit }}</div>
        @endforeach
    </div>
</div>

<div class="d-flex gap-3 justify-content-end mt-4">
    <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
    <button type="submit" class="btn btn-primary-bm px-5">
        <i class="bi bi-star-fill me-2"></i>Feature This Product
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
