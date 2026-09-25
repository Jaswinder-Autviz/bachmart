@extends('layouts.app')
@section('title', 'Browse Local Shops - BachatMart')

@section('content')
<div class="container py-4 py-lg-5">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <span class="section-tag mb-2"><i class="bi bi-shop"></i> Merchant Directory</span>
            <h1 class="section-title mb-1">Local <span>Shops & Retailers</span></h1>
            <p class="section-subtitle">{{ $shops->total() }} verified local shops clearing surplus stock</p>
        </div>
        <form class="d-flex gap-2 flex-wrap" method="GET" action="{{ route('shops.index') }}">
            <input type="text" name="search" class="form-control form-control-sm rounded-pill px-3" style="min-width: 180px;"
                   placeholder="Search shop name..." value="{{ request('search') }}">
            <input type="text" name="city" class="form-control form-control-sm rounded-pill px-3" style="min-width: 140px;"
                   placeholder="City / Area..." value="{{ request('city') }}">
            <button type="submit" class="btn btn-primary-bm btn-sm px-4">Search</button>
            @if(request('search') || request('city'))
                <a href="{{ route('shops.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Clear</a>
            @endif
        </form>
    </div>

    @if($shops->count())
    <div class="row g-4">
        @foreach($shops as $shop)
        <div class="col-md-6 col-lg-4">
            @include('components.shop-card', ['shop' => $shop])
        </div>
        @endforeach
    </div>
    <div class="mt-5 d-flex justify-content-center">{{ $shops->links() }}</div>
    @else
    <div class="text-center py-5 card border-0 shadow-sm rounded-4 p-5 bg-white">
        <div style="font-size: 4rem">🏪</div>
        <h5 class="fw-800 text-dark mt-3">No local shops found</h5>
        <p class="text-muted small mb-3">Try adjusting your search criteria or city location.</p>
        <div>
            <a href="{{ route('shops.index') }}" class="btn btn-primary-bm px-4">Browse All Shops</a>
        </div>
    </div>
    @endif
</div>
@endsection
