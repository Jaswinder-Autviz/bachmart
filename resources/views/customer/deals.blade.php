@extends('layouts.app')
@section('title', 'All Clearance Deals - BachatMart')

@section('content')
<div class="container py-4 py-lg-5">
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <span class="section-tag mb-2"><i class="bi bi-lightning-charge-fill"></i> Live Catalog</span>
            <h1 class="section-title mb-1">Local <span>Clearance Deals</span></h1>
            <p class="section-subtitle">{{ $products->total() }} active Surplus Stock items ready for in-store purchase</p>
        </div>
        @if(request()->hasAny(['category','city','min_discount','sort','featured']))
            <a href="{{ route('deals') }}" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-600">
                <i class="bi bi-x-circle me-1"></i>Reset All Filters
            </a>
        @endif
    </div>

    <div class="row g-4">
        {{-- ── FILTERS SIDEBAR ── --}}
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 sticky-top p-4 bg-white" style="top: 95px; border: 1px solid var(--bm-border) !important;">
                <h6 class="fw-800 mb-3 pb-2 border-bottom d-flex align-items-center justify-content-between">
                    <span><i class="bi bi-funnel-fill text-primary-bm me-2"></i>Filter Deals</span>
                    @if(request()->hasAny(['category','city','min_discount','sort','featured']))
                        <a href="{{ route('deals') }}" class="small text-danger text-decoration-none fw-600">Clear</a>
                    @endif
                </h6>

                <form method="GET" action="{{ route('deals') }}" id="filterForm">
                    {{-- Sort --}}
                    <div class="mb-3">
                        <label class="form-label small fw-700 text-dark">Sort By</label>
                        <select name="sort" class="form-select form-select-sm rounded-3" onchange="this.form.submit()">
                            <option value="" {{ !request('sort') ? 'selected' : '' }}>Featured First</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest Listed</option>
                            <option value="discount" {{ request('sort') == 'discount' ? 'selected' : '' }}>Highest Discount %</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                        </select>
                    </div>

                    {{-- Category --}}
                    <div class="mb-3">
                        <label class="form-label small fw-700 text-dark">Category</label>
                        <select name="category" class="form-select form-select-sm rounded-3" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->icon }} {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Min Discount --}}
                    <div class="mb-3">
                        <label class="form-label small fw-700 text-dark">Min Discount %</label>
                        <select name="min_discount" class="form-select form-select-sm rounded-3" onchange="this.form.submit()">
                            <option value="">Any Discount</option>
                            <option value="15" {{ request('min_discount') == '15' ? 'selected' : '' }}>15% or more</option>
                            <option value="30" {{ request('min_discount') == '30' ? 'selected' : '' }}>30% or more</option>
                            <option value="50" {{ request('min_discount') == '50' ? 'selected' : '' }}>50% or more (Half Price)</option>
                            <option value="70" {{ request('min_discount') == '70' ? 'selected' : '' }}>70% or more (Super Clearance)</option>
                        </select>
                    </div>

                    {{-- City --}}
                    <div class="mb-3">
                        <label class="form-label small fw-700 text-dark">City / Location</label>
                        <input type="text" name="city" class="form-control form-control-sm rounded-3"
                               placeholder="e.g. Mumbai, Delhi" value="{{ request('city', session('selected_city')) }}">
                    </div>

                    {{-- Featured --}}
                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" name="featured" value="1"
                               id="featuredCheck" {{ request('featured') ? 'checked' : '' }} onchange="this.form.submit()">
                        <label class="form-check-label small fw-700 text-dark" for="featuredCheck">⭐ Featured Deals Only</label>
                    </div>

                    <button type="submit" class="btn btn-primary-bm btn-sm w-100 mb-2">Apply Filters</button>
                    @if(request()->hasAny(['category','city','min_discount','sort','featured']))
                        <a href="{{ route('deals') }}" class="btn btn-outline-secondary btn-sm w-100 rounded-pill fw-600">Clear All</a>
                    @endif
                </form>
            </div>
        </div>

        {{-- ── PRODUCT GRID ── --}}
        <div class="col-lg-9">
            @if($products->count())
                <div class="row g-3 g-md-4 mobile-2-col">
                    @foreach($products as $product)
                    <div class="col-6 col-md-4">
                        @include('components.product-card', ['product' => $product])
                    </div>
                    @endforeach
                </div>
                <div class="mt-5 d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white" style="border: 1px solid var(--bm-border) !important;">
                    <div class="fs-1 mb-3">🔍</div>
                    <h5 class="fw-800 text-dark mb-1">No clearance deals found</h5>
                    <p class="text-muted small mb-4">Try removing some filters to see more available deals.</p>
                    <div>
                        <a href="{{ route('deals') }}" class="btn btn-primary-bm px-4">Clear All Filters</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
