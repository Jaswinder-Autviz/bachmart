@extends('layouts.app')
@section('title', $q ? "Search: $q - BachatMart" : 'Search Clearance Deals - BachatMart')

@section('content')
<div class="container py-4">
    {{-- Search Bar Header --}}
    <div class="mb-4">
        <form action="{{ route('search') }}" method="GET" class="mb-3">
            <div class="input-group shadow-sm rounded-pill overflow-hidden border p-1 bg-white" style="max-width:680px">
                <span class="input-group-text bg-transparent border-0 ps-3 text-muted">
                    <i class="bi bi-search fs-5"></i>
                </span>
                <input type="text" name="q" class="form-control border-0 shadow-none fs-6"
                       placeholder="Search deals, products, shops, brands, or cities..."
                       value="{{ $q }}" autofocus>
                <button class="btn btn-primary-bm px-4" type="submit">Search</button>
            </div>
        </form>

        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                @if($q)
                    <h1 class="fs-4 fw-800 mb-0">Search results for <span class="text-primary-bm">"{{ $q }}"</span></h1>
                    <span class="text-muted small">{{ $products->total() }} matching clearance items found</span>
                @else
                    <h1 class="fs-4 fw-800 mb-0">All <span class="text-primary-bm">Clearance Deals</span></h1>
                    <span class="text-muted small">Showing {{ $products->total() }} available local Surplus Stock listings</span>
                @endif
            </div>

            {{-- Active Filter Pills --}}
            @if(request()->hasAny(['category','city','min_discount','min_price','max_price','available','featured','sort']))
                <a href="{{ route('search', ['q' => $q]) }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                    <i class="bi bi-x-circle me-1"></i>Reset All Filters
                </a>
            @endif
        </div>
    </div>

    <div class="row g-4">
        {{-- ── FILTERS SIDEBAR ── --}}
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 sticky-top p-3 bg-white" style="top:95px">
                <h6 class="fw-800 mb-3 pb-2 border-bottom d-flex align-items-center justify-content-between">
                    <span><i class="bi bi-sliders me-1 text-primary-bm"></i>Filter Deals</span>
                    @if(request()->hasAny(['category','city','min_discount','min_price','max_price','available','featured']))
                        <a href="{{ route('search', ['q' => $q]) }}" class="small text-danger text-decoration-none">Clear</a>
                    @endif
                </h6>

                <form method="GET" action="{{ route('search') }}" id="filterForm">
                    <input type="hidden" name="q" value="{{ $q }}">

                    {{-- Sort --}}
                    <div class="mb-3">
                        <label class="form-label small fw-700">Sort Deals By</label>
                        <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="" {{ !request('sort') ? 'selected' : '' }}>Featured First</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest Deals</option>
                            <option value="discount" {{ request('sort') == 'discount' ? 'selected' : '' }}>Highest Discount %</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Lowest Price</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Highest Price</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Viewed</option>
                        </select>
                    </div>

                    {{-- Category --}}
                    <div class="mb-3">
                        <label class="form-label small fw-700">Category</label>
                        <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->icon }} {{ $cat->name }} ({{ $cat->approved_products_count }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Minimum Discount % --}}
                    <div class="mb-3">
                        <label class="form-label small fw-700">Min Discount</label>
                        <select name="min_discount" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Any Discount</option>
                            <option value="15" {{ request('min_discount') == '15' ? 'selected' : '' }}>15% or more</option>
                            <option value="30" {{ request('min_discount') == '30' ? 'selected' : '' }}>30% or more</option>
                            <option value="50" {{ request('min_discount') == '50' ? 'selected' : '' }}>50% or more (Heavy Discount)</option>
                            <option value="70" {{ request('min_discount') == '70' ? 'selected' : '' }}>70% or more (Clearance)</option>
                        </select>
                    </div>

                    {{-- Location / City --}}
                    <div class="mb-3">
                        <label class="form-label small fw-700">City / Location</label>
                        <select name="city" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">All Cities</option>
                            @foreach($availableCities as $c)
                            <option value="{{ $c }}" {{ request('city') == $c ? 'selected' : '' }}>{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Price Range --}}
                    <div class="mb-3">
                        <label class="form-label small fw-700">Price Range (₹)</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" name="min_price" class="form-control form-control-sm"
                                       placeholder="Min ₹" value="{{ request('min_price') }}">
                            </div>
                            <div class="col-6">
                                <input type="number" name="max_price" class="form-control form-control-sm"
                                       placeholder="Max ₹" value="{{ request('max_price') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Availability Checkbox --}}
                    <div class="mb-2 form-check">
                        <input class="form-check-input" type="checkbox" name="available" value="1" id="availCheck"
                               {{ request('available') ? 'checked' : '' }} onchange="this.form.submit()">
                        <label class="form-check-label small fw-600" for="availCheck">In Stock Only</label>
                    </div>

                    {{-- Featured Checkbox --}}
                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" name="featured" value="1" id="featCheck"
                               {{ request('featured') ? 'checked' : '' }} onchange="this.form.submit()">
                        <label class="form-check-label small fw-600" for="featCheck">⭐ Featured Deals Only</label>
                    </div>

                    <button type="submit" class="btn btn-primary-bm btn-sm w-100 mb-2">Apply Filters</button>
                    @if(request()->hasAny(['category','city','min_discount','min_price','max_price','available','featured','sort']))
                        <a href="{{ route('search', ['q' => $q]) }}" class="btn btn-outline-secondary btn-sm w-100">Clear Filters</a>
                    @endif
                </form>
            </div>
        </div>

        {{-- ── SEARCH RESULTS GRID ── --}}
        <div class="col-lg-9">
            {{-- Matching Shops (if keyword searched) --}}
            @if($shops->count())
            <div class="mb-4">
                <h6 class="fw-800 mb-3 text-dark">
                    <i class="bi bi-shop text-primary-bm me-1"></i>Shops Matching "{{ $q }}"
                </h6>
                <div class="row g-3">
                    @foreach($shops as $shop)
                    <div class="col-md-6">
                        @include('components.shop-card', ['shop' => $shop])
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Products Grid --}}
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
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                    <div class="fs-1 mb-2">🔍</div>
                    <h5 class="fw-800 text-dark">No deals found</h5>
                    <p class="text-muted small mb-3">Try adjusting your keyword or clearing some filters.</p>
                    <div>
                        <a href="{{ route('deals') }}" class="btn btn-primary-bm btn-sm px-4">Browse All Live Deals</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
