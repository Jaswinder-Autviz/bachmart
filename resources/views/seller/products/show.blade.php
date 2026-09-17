@extends('layouts.seller')
@section('title', $product->name)
@section('page-title', 'Product Details')

@section('content')
<div class="d-flex align-items-center justify-content-between gap-2 mb-4">
    <div class="d-flex gap-2">
        <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary btn-sm px-3 rounded-3">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
        <a href="{{ route('seller.products.edit', $product) }}" class="btn btn-dark btn-sm px-3 rounded-3">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
    </div>
    @if($product->status === 'approved')
    <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="btn btn-outline-secondary btn-sm px-3 rounded-3">
        <i class="bi bi-box-arrow-up-right me-1"></i>View Live Deal
    </a>
    @endif
</div>

<div class="row g-4">
    <div class="col-lg-8">
        {{-- Main Product Card --}}
        <div class="card-bm p-4 mb-4 bg-white rounded-4 border" style="border-color: #E2E8F0 !important;">
            <div class="d-flex gap-4 align-items-start">
                <img src="{{ $product->primary_image_url }}" width="110" height="110"
                     class="rounded-3 border flex-shrink-0" style="object-fit: cover;"
                     onerror="this.src='{{ asset('images/product-placeholder.png') }}'">
                <div class="flex-grow-1">
                    <div class="d-flex gap-2 flex-wrap mb-2">
                        <span class="badge badge-status-{{ $product->status }} rounded-pill text-capitalize">
                            {{ str_replace('_',' ',$product->status) }}
                        </span>
                        @if($product->is_featured)
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill">
                                Featured
                            </span>
                        @endif
                        <span class="badge bg-light text-secondary border rounded-pill">
                            {{ $product->category->name }}
                        </span>
                    </div>

                    <h5 class="fw-800 text-dark mb-2" style="font-size: 1.25rem;">{{ $product->name }}</h5>

                    <div class="d-flex align-items-baseline gap-3">
                        <span class="fw-800 text-dark fs-4">₹{{ number_format($product->offer_price) }}</span>
                        @if($product->original_price > $product->offer_price)
                            <span class="text-muted text-decoration-line-through">₹{{ number_format($product->original_price) }}</span>
                            <span class="badge bg-dark text-white rounded-pill fw-600 px-2 py-1 small">
                                {{ round($product->discount_percent) }}% OFF
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            @if($product->rejection_reason)
            <div class="alert alert-danger mt-3 mb-0 rounded-3 small">
                <strong>Rejection Reason:</strong> {{ $product->rejection_reason }}
            </div>
            @endif
        </div>

        {{-- Lead Performance Analytics --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="p-3 bg-white border rounded-3 text-center h-100">
                    <div class="text-muted small mb-1">Total Views</div>
                    <div class="fw-800 text-dark fs-4">{{ number_format($leadStats['views']) }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 bg-white border rounded-3 text-center h-100">
                    <div class="text-muted small mb-1">Phone Calls</div>
                    <div class="fw-800 text-dark fs-4">{{ number_format($leadStats['calls']) }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 bg-white border rounded-3 text-center h-100">
                    <div class="text-muted small mb-1">WhatsApp Leads</div>
                    <div class="fw-800 text-success fs-4">{{ number_format($leadStats['whatsapp']) }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 bg-white border rounded-3 text-center h-100">
                    <div class="text-muted small mb-1">Directions</div>
                    <div class="fw-800 text-dark fs-4">{{ number_format($leadStats['directions']) }}</div>
                </div>
            </div>
        </div>

        {{-- Images Gallery --}}
        @if($product->images->count())
        <div class="card-bm p-4 mb-4 bg-white rounded-4 border" style="border-color: #E2E8F0 !important;">
            <h6 class="fw-700 mb-3 text-dark">Product Images</h6>
            <div class="d-flex gap-3 flex-wrap">
                @foreach($product->images as $img)
                <div class="position-relative">
                    <img src="{{ $img->url }}" width="90" height="90" class="rounded-3 border"
                         style="object-fit: cover; border-color: {{ $img->is_primary ? '#0F172A !important; border-width: 2px !important;' : '#E2E8F0' }}">
                    @if($img->is_primary)
                        <span class="badge bg-dark text-white position-absolute top-0 start-0 m-1" style="font-size: 0.6rem;">Primary</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        {{-- Product Details Specs --}}
        <div class="card-bm p-4 mb-4 bg-white rounded-4 border" style="border-color: #E2E8F0 !important;">
            <h6 class="fw-700 mb-3 text-dark">Inventory Information</h6>
            <table class="table table-sm table-borderless small mb-0">
                <tr><td class="text-muted py-1">Brand</td><td class="fw-600 text-end py-1">{{ $product->brand ?: '—' }}</td></tr>
                <tr><td class="text-muted py-1">Condition</td><td class="fw-600 text-end py-1 text-capitalize">{{ str_replace('_',' ',$product->condition) }}</td></tr>
                <tr><td class="text-muted py-1">Available Quantity</td><td class="fw-600 text-end py-1">{{ $product->quantity }}</td></tr>
                <tr><td class="text-muted py-1">SKU / Lot</td><td class="fw-600 text-end py-1">{{ $product->sku ?: '—' }}</td></tr>
                <tr><td class="text-muted py-1">Listed On</td><td class="fw-600 text-end py-1">{{ $product->created_at->format('d M Y') }}</td></tr>
                @if($product->expires_at)<tr><td class="text-muted py-1">Expires On</td><td class="fw-600 text-end py-1">{{ $product->expires_at->format('d M Y') }}</td></tr>@endif
            </table>
        </div>

        @if($product->status === 'approved' && !$product->isFeaturedActive())
        <div class="card-bm p-4 bg-white rounded-4 border" style="border-color: #FED7AA !important; background: #FFF7ED !important;">
            <h6 class="fw-800 text-dark mb-1">Promote This Deal</h6>
            <p class="small text-muted mb-3">Feature on homepage to reach up to 5x more local shoppers.</p>
            <a href="{{ route('seller.featured.show', $product) }}" class="btn btn-dark btn-sm w-100 py-2 rounded-3 fw-600">
                Feature Product &rarr;
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
