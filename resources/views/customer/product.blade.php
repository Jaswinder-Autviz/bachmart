@extends('layouts.app')

@section('title', $product->name . ' - BachatMart')
@section('meta_description', 'Clearance deal: ' . $product->name . ' at ₹' . number_format($product->offer_price) . ' (' . round($product->discount_percent) . '% OFF) from ' . ($product->shop->name ?? 'local shop') . '.')

@section('content')
<div class="container py-4 py-lg-5">
    {{-- Clean Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small text-muted">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('deals') }}" class="text-decoration-none text-muted">Clearance Deals</a></li>
            <li class="breadcrumb-item"><a href="{{ route('category.show', $product->category->slug) }}" class="text-decoration-none text-muted">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active text-dark fw-700" aria-current="page">{{ Str::limit($product->name, 35) }}</li>
        </ol>
    </nav>

    <div class="row g-4 g-lg-5">
        {{-- ── 1. PRODUCT GALLERY ── --}}
        <div class="col-lg-6">
            <div class="sticky-top" style="top: 95px; z-index: 10;">
                <div class="card border-0 rounded-4 overflow-hidden mb-3 bg-white shadow-sm" style="border: 1px solid var(--bm-border) !important;">
                    <div class="position-relative" style="aspect-ratio: 1; max-height: 480px; background: #F8FAFC;">
                        <img id="mainImage" src="{{ $product->primary_image_url }}" alt="{{ $product->name }}"
                             class="w-100 h-100" style="object-fit: cover;"
                             onerror="this.onerror=null; this.src='{{ asset('images/product-placeholder.svg') }}';">

                        @if($product->discount_percent > 0)
                            <span class="product-card-badge product-card-badge--discount fs-6 px-3 py-1">
                                -{{ round($product->discount_percent) }}% OFF
                            </span>
                        @endif

                        @if($product->isFeaturedActive())
                            <span class="product-card-badge product-card-badge--featured fs-6 px-3 py-1">
                                <i class="bi bi-star-fill me-1" style="font-size: 0.75rem;"></i>Featured Deal
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Thumbnails --}}
                @if($product->images->count() > 1)
                <div class="d-flex gap-2 flex-wrap mb-4">
                    @foreach($product->images as $index => $img)
                    <div class="rounded-3 overflow-hidden border thumb-box {{ $loop->first ? 'border-primary shadow-sm' : '' }}"
                         style="width: 72px; height: 72px; cursor: pointer; background: #F8FAFC; transition: all 0.2s ease;"
                         onclick="switchMainImage('{{ asset('storage/'.$img->image_path) }}', this)">
                        <img src="{{ asset('storage/'.$img->image_path) }}" class="w-100 h-100" style="object-fit: cover;" alt="Thumbnail">
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Trust Highlight --}}
                <div class="p-3 rounded-4 border bg-white d-flex align-items-center gap-3 shadow-sm" style="border-color: var(--bm-border) !important;">
                    <div class="text-success fs-3">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div class="small">
                        <div class="fw-800 text-dark">100% In-Store Inspection Guarantee</div>
                        <div class="text-muted">Contact the shopkeeper, visit the physical store, inspect the item in person, and pay directly.</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── 2. PRODUCT DETAILS & ACTIONS ── --}}
        <div class="col-lg-6">
            {{-- Category & Condition Pills --}}
            <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                <a href="{{ route('category.show', $product->category->slug) }}" class="badge bg-light text-secondary border text-decoration-none px-3 py-2 rounded-pill fw-600">
                    {{ $product->category->name }}
                </a>
                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-600 text-capitalize">
                    Condition: {{ str_replace('_', ' ', $product->condition) }}
                </span>
                @if($product->quantity > 0)
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-600">
                        In Stock ({{ $product->quantity }} units)
                    </span>
                @endif
            </div>

            {{-- Product Name --}}
            <h1 class="fw-900 text-dark mb-2" style="font-size: clamp(1.6rem, 2.5vw, 2.2rem); letter-spacing: -0.03em; line-height: 1.25;">
                {{ $product->name }}
            </h1>

            {{-- Shop / Brand Meta --}}
            <div class="d-flex align-items-center gap-2 text-muted small mb-4">
                <span>Sold by: <a href="{{ route('shop.show', $product->shop->slug) }}" class="text-dark fw-800 text-decoration-none">{{ $product->shop->name }}</a></span>
                @if($product->shop->is_verified)
                    <i class="bi bi-patch-check-fill text-primary" title="Verified Merchant"></i>
                @endif
                @if($product->shop->city)
                    <span>· {{ $product->shop->city }}</span>
                @endif
            </div>

            {{-- Clean Pricing Block --}}
            <div class="p-4 rounded-4 mb-4 bg-white shadow-sm" style="border: 1.5px solid var(--bm-border);">
                <div class="d-flex align-items-baseline gap-3 flex-wrap">
                    <span class="fw-900 text-dark" style="font-size: 2.4rem; letter-spacing: -0.03em; line-height: 1;">
                        ₹{{ number_format($product->offer_price) }}
                    </span>
                    @if($product->original_price > $product->offer_price)
                        <span class="fs-5 text-muted text-decoration-line-through">
                            ₹{{ number_format($product->original_price) }}
                        </span>
                        <span class="badge rounded-pill bg-dark text-white px-3 py-2 fw-700" style="font-size: 0.8rem;">
                            Save ₹{{ number_format($product->original_price - $product->offer_price) }} ({{ round($product->discount_percent) }}% OFF)
                        </span>
                    @endif
                </div>

                @if($product->is_negotiable)
                    <div class="mt-2 text-muted small">
                        <i class="bi bi-chat-dots me-1 text-primary-bm"></i>Price is open to reasonable in-store discussion.
                    </div>
                @endif
            </div>

            {{-- High-Converting Actions (WhatsApp & Call) --}}
            <div class="card border-0 rounded-4 p-4 mb-4 bg-white shadow-sm" style="border: 1px solid var(--bm-border) !important;">
                <h6 class="fw-800 mb-3 text-dark">Contact Shopkeeper Directly:</h6>
                <div class="row g-3">
                    @if($product->shop->whatsapp)
                    <div class="col-sm-6">
                        <a href="{{ $product->shop->whatsapp_url }}" target="_blank"
                           onclick="trackLead('whatsapp', {{ $product->shop->id }}, {{ $product->id }})"
                           class="btn btn-whatsapp w-100 py-3 fw-700">
                            <i class="bi bi-whatsapp fs-5"></i> Chat on WhatsApp
                        </a>
                    </div>
                    @endif

                    @if($product->shop->phone)
                    <div class="col-sm-6">
                        <a href="tel:{{ $product->shop->phone }}"
                           onclick="trackLead('call', {{ $product->shop->id }}, {{ $product->id }})"
                           class="btn btn-dark w-100 py-3 fw-700 rounded-pill d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-telephone fs-5"></i> Call Store
                        </a>
                    </div>
                    @endif

                    @if($product->shop->direction_url)
                    <div class="col-12">
                        <a href="{{ $product->shop->direction_url }}" target="_blank"
                           onclick="trackLead('direction', {{ $product->shop->id }}, {{ $product->id }})"
                           class="btn btn-outline-secondary w-100 py-2 rounded-pill fw-600 d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-geo-alt-fill text-danger"></i> View Store Location & Directions
                        </a>
                    </div>
                    @endif
                </div>

                <div class="mt-3 text-center small text-muted">
                    Mention <strong>BachatMart</strong> at the store counter to claim this clearance price.
                </div>
            </div>

            {{-- Product Description --}}
            <div class="card border-0 rounded-4 p-4 mb-4 bg-white shadow-sm" style="border: 1px solid var(--bm-border) !important;">
                <h6 class="fw-800 mb-3 text-dark">Item Details & Specifications</h6>
                <div class="text-secondary" style="line-height: 1.75; font-size: 0.95rem;">
                    {!! nl2br(e($product->description)) !!}
                </div>

                <div class="row g-2 mt-3 pt-3 border-top small text-muted">
                    @if($product->brand)
                        <div class="col-6"><strong>Brand:</strong> {{ $product->brand }}</div>
                    @endif
                    @if($product->sku)
                        <div class="col-6"><strong>SKU/Lot:</strong> {{ $product->sku }}</div>
                    @endif
                    <div class="col-6"><strong>Category:</strong> {{ $product->category->name }}</div>
                    <div class="col-6"><strong>Condition:</strong> {{ str_replace('_', ' ', $product->condition) }}</div>
                </div>
            </div>

            {{-- Shop Profile Card --}}
            @php $shop = $product->shop; @endphp
            <div class="card border-0 rounded-4 p-4 mb-4 bg-white shadow-sm" style="border: 1px solid var(--bm-border) !important;">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="fw-800 mb-0 text-dark">Store Information</h6>
                    @if($shop->isOpenNow())
                        <span class="badge bg-success-subtle text-success fw-700 rounded-pill px-3 py-1">Open Now</span>
                    @endif
                </div>

                <div class="d-flex align-items-center gap-3 mb-3">
                    <img src="{{ $shop->logo_url }}" width="56" height="56" class="rounded-4 border flex-shrink-0"
                         style="object-fit: cover;" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($shop->name) }}&background=0F172A&color=fff&size=56';">
                    <div>
                        <h6 class="fw-800 mb-1 text-dark">
                            {{ $shop->name }}
                            @if($shop->is_verified)
                                <i class="bi bi-patch-check-fill text-primary ms-1" title="Verified Store"></i>
                            @endif
                        </h6>
                        <div class="text-muted small">
                            <i class="bi bi-geo-alt-fill text-primary-bm me-1"></i>{{ $shop->full_address }}
                        </div>
                    </div>
                </div>

                <a href="{{ route('shop.show', $shop->slug) }}" class="btn btn-outline-secondary btn-sm w-100 rounded-pill py-2 fw-700">
                    View All Clearance Deals From This Store &rarr;
                </a>
            </div>

            {{-- Deal Stats --}}
            <div class="d-flex gap-4 text-muted small px-1">
                <span><i class="bi bi-eye me-1"></i>{{ number_format($product->views_count) }} views</span>
                <span><i class="bi bi-chat-dots me-1"></i>{{ $product->whatsapp_count }} WhatsApp inquiries</span>
                <span><i class="bi bi-telephone me-1"></i>{{ $product->calls_count }} calls</span>
            </div>
        </div>
    </div>

    {{-- ── 3. RELATED DEALS ── --}}
    @if(isset($relatedProducts) && $relatedProducts->count())
    <div class="mt-5 pt-5 border-top">
        <div class="section-header mb-4">
            <div>
                <span class="section-tag"><i class="bi bi-tags-fill"></i> Related Stock</span>
                <h3 class="section-title">More Clearance Deals in <span>{{ $product->category->name }}</span></h3>
                <p class="section-subtitle">Similar discounted inventory from verified neighborhood sellers</p>
            </div>
            <a href="{{ route('category.show', $product->category->slug) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-600">
                View All &rarr;
            </a>
        </div>

        <div class="row g-3 g-md-4 mobile-2-col">
            @foreach($relatedProducts as $related)
            <div class="col-6 col-md-4 col-lg-3">
                @include('components.product-card', ['product' => $related])
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

{{-- ── STICKY BOTTOM ACTION BAR ON MOBILE ── --}}
<div class="sticky-mobile-actions d-lg-none">
    <div class="d-flex gap-2 align-items-center">
        @if($product->shop->whatsapp)
        <a href="{{ $product->shop->whatsapp_url }}" target="_blank"
           onclick="trackLead('whatsapp', {{ $product->shop->id }}, {{ $product->id }})"
           class="btn btn-whatsapp flex-grow-1 py-2 fw-700" style="font-size: 0.88rem;">
            <i class="bi bi-whatsapp"></i> WhatsApp
        </a>
        @endif
        @if($product->shop->phone)
        <a href="tel:{{ $product->shop->phone }}"
           onclick="trackLead('call', {{ $product->shop->id }}, {{ $product->id }})"
           class="btn btn-dark rounded-pill py-2 px-3 fw-700" style="font-size: 0.88rem;">
            <i class="bi bi-telephone"></i> Call
        </a>
        @endif
    </div>
</div>

@push('scripts')
<script>
function switchMainImage(src, element) {
    const main = document.getElementById('mainImage');
    if (main) {
        main.src = src;
    }
    document.querySelectorAll('.thumb-box').forEach(el => {
        el.classList.remove('border-primary', 'shadow-sm');
    });
    if (element) {
        element.classList.add('border-primary', 'shadow-sm');
    }
}
</script>
@endpush

@endsection
