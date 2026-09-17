@extends('layouts.app')
@section('title', $shop->name . ' - Local Shop & Clearance Deals')

@section('content')
{{-- ── SHOP HERO COVER & PROFILE ── --}}
<div class="position-relative" style="background:#0F172A">
    {{-- Cover Image --}}
    <div style="height:260px;overflow:hidden;position:relative">
        <img src="{{ $shop->cover_url }}" class="w-100 h-100" style="object-fit:cover;opacity:0.85"
             alt="{{ $shop->name }} Cover" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1200&fit=crop&q=80';">
        <div class="position-absolute w-100 h-100 top-0 start-0" style="background:linear-gradient(to top, rgba(15,23,42,0.8) 0%, transparent 60%)"></div>
    </div>

    {{-- Shop Profile Information Bar --}}
    <div class="container position-relative" style="margin-top:-70px;z-index:10">
        <div class="card border-0 shadow-lg rounded-4 p-4 bg-white">
            <div class="row align-items-center g-3">
                <div class="col-auto">
                    <img src="{{ $shop->logo_url }}" width="96" height="96"
                         class="rounded-4 border border-4 border-white shadow flex-shrink-0"
                         style="object-fit:cover;background:#fff"
                         alt="{{ $shop->name }} Logo" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($shop->name) }}&background=FF5722&color=fff&size=96';">
                </div>
                <div class="col">
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                        <h1 class="fw-800 fs-3 mb-0 text-dark">{{ $shop->name }}</h1>
                        @if($shop->is_verified)
                            <span class="badge bg-success-subtle text-success fw-700">
                                <i class="bi bi-patch-check-fill me-1"></i>Verified Shop
                            </span>
                        @endif
                        @if($shop->is_featured)
                            <span class="badge" style="background:var(--bm-primary);color:#fff;font-weight:700"><i class="bi bi-star-fill me-1"></i> Featured Shop</span>
                        @endif
                        @if($shop->isOpenNow())
                            <span class="badge bg-success-subtle text-success">
                                <i class="bi bi-circle-fill me-1" style="font-size:0.4rem"></i>Open Now
                            </span>
                        @endif
                    </div>
                    <div class="d-flex gap-3 text-muted small flex-wrap mt-2">
                        <span><i class="bi bi-geo-alt-fill text-primary-bm me-1"></i>{{ $shop->full_address }}</span>
                        @if($shop->rating > 0)
                            <span class="text-warning fw-600">
                                {{ str_repeat('★', round($shop->rating)) }}{{ str_repeat('☆', 5 - round($shop->rating)) }}
                                <span class="text-muted">({{ $shop->reviews_count }} reviews)</span>
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Shop Contact Actions --}}
                <div class="col-12 col-md-auto d-flex gap-2 flex-wrap">
                    @if($shop->phone)
                    <a href="tel:{{ $shop->phone }}"
                       onclick="trackLead('call', {{ $shop->id }})"
                       class="btn btn-call px-3 py-2 fw-700 shadow-sm">
                        <i class="bi bi-telephone-fill me-1"></i>CALL SHOP
                    </a>
                    @endif
                    @if($shop->whatsapp)
                    <a href="{{ $shop->whatsapp_url }}" target="_blank"
                       onclick="trackLead('whatsapp', {{ $shop->id }})"
                       class="btn btn-whatsapp px-3 py-2 fw-700 shadow-sm">
                        <i class="bi bi-whatsapp me-1"></i>WHATSAPP
                    </a>
                    @endif
                    @if($shop->direction_url)
                    <a href="{{ $shop->direction_url }}" target="_blank"
                       onclick="trackLead('direction', {{ $shop->id }})"
                       class="btn btn-outline-secondary px-3 py-2 fw-700">
                        <i class="bi bi-map-fill me-1 text-danger"></i>DIRECTIONS
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        {{-- Main Content: Active Deals --}}
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h3 class="fw-800 mb-1">Clearance Deals From This Shop</h3>
                    <p class="text-muted small mb-0">{{ $products->total() }} active Surplus Stock deals listed</p>
                </div>
            </div>

            {{-- Category Filter Pills --}}
            @if($categories->count() > 1)
            <div class="d-flex gap-2 flex-wrap mb-4">
                <a href="{{ route('shop.show', $shop->slug) }}"
                   class="btn btn-sm {{ !request('category') ? 'btn-primary-bm' : 'btn-light border' }} rounded-pill px-3">
                    All Deals
                </a>
                @foreach($categories as $cat)
                <a href="{{ route('shop.show', $shop->slug) }}?category={{ $cat->id }}"
                   class="btn btn-sm {{ request('category') == $cat->id ? 'btn-primary-bm' : 'btn-light border' }} rounded-pill px-3">
                    {{ $cat->icon }} {{ $cat->name }}
                </a>
                @endforeach
            </div>
            @endif

            {{-- Deals Grid --}}
            @if($products->count())
            <div class="row g-3 g-md-4 mobile-2-col">
                @foreach($products as $product)
                <div class="col-6 col-md-4">
                    @include('components.product-card', ['product' => $product])
                </div>
                @endforeach
            </div>
            <div class="mt-4 d-flex justify-content-center">
                {{ $products->links() }}
            </div>
            @else
            <div class="text-center py-5 card border-0 shadow-sm rounded-4">
                <div class="fs-1">📦</div>
                <h5 class="fw-700 mt-2">No active clearance deals at the moment</h5>
                <p class="text-muted small">Check back soon for new Surplus Stock listings!</p>
            </div>
            @endif
        </div>

        {{-- Shop Sidebar Details --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white sticky-top" style="top:95px">
                <h5 class="fw-800 mb-3 text-dark">Shop Information</h5>
                
                @if($shop->description)
                <div class="mb-4">
                    <div class="text-muted small fw-700 text-uppercase mb-1">About Us</div>
                    <p class="text-secondary small mb-0" style="line-height:1.6">{{ $shop->description }}</p>
                </div>
                @endif

                <div class="mb-3">
                    <div class="text-muted small fw-700 text-uppercase mb-1">Store Address</div>
                    <div class="text-secondary small">
                        <i class="bi bi-geo-alt-fill text-primary-bm me-1"></i>{{ $shop->full_address }}
                    </div>
                </div>

                @if($shop->phone)
                <div class="mb-3">
                    <div class="text-muted small fw-700 text-uppercase mb-1">Contact Phone</div>
                    <div class="text-secondary small">
                        <i class="bi bi-telephone-fill text-success me-1"></i>{{ $shop->phone }}
                    </div>
                </div>
                @endif

                @if($shop->opening_hours)
                <div class="mb-3">
                    <div class="text-muted small fw-700 text-uppercase mb-2">Operating Hours</div>
                    <table class="table table-sm table-borderless small mb-0">
                        @foreach(['monday','tuesday','wednesday','thursday','friday','saturday','sunday'] as $d)
                            @php $h = $shop->opening_hours[$d] ?? null; @endphp
                            <tr>
                                <td class="text-capitalize text-muted py-1 ps-0">{{ substr($d,0,3) }}</td>
                                <td class="fw-600 py-1 text-end">
                                    @if(isset($h['open']) && $h['open'])
                                        {{ $h['from'] ?? '09:00' }} - {{ $h['to'] ?? '21:00' }}
                                    @else
                                        <span class="text-danger">Closed</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                @endif

                @if($shop->facebook || $shop->instagram || $shop->website)
                <div class="pt-3 border-top">
                    <div class="text-muted small fw-700 text-uppercase mb-2">Social & Web</div>
                    <div class="d-flex gap-2">
                        @if($shop->facebook)<a href="{{ $shop->facebook }}" target="_blank" class="btn btn-sm btn-light rounded-circle"><i class="bi bi-facebook"></i></a>@endif
                        @if($shop->instagram)<a href="{{ $shop->instagram }}" target="_blank" class="btn btn-sm btn-light rounded-circle"><i class="bi bi-instagram"></i></a>@endif
                        @if($shop->website)<a href="{{ $shop->website }}" target="_blank" class="btn btn-sm btn-light rounded-circle"><i class="bi bi-globe"></i></a>@endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
