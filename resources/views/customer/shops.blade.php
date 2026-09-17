@extends('layouts.app')
@section('title', 'Browse Shops - BachatMart')

@section('content')
<div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h1 class="section-title mb-1">Local <span>Shops</span></h1>
            <p class="text-muted small mb-0">{{ $shops->total() }} shops found</p>
        </div>
        <form class="d-flex gap-2" method="GET" action="{{ route('shops.index') }}">
            <input type="text" name="search" class="form-control form-control-sm" style="max-width:200px"
                   placeholder="Search shops..." value="{{ request('search') }}">
            <input type="text" name="city" class="form-control form-control-sm" style="max-width:150px"
                   placeholder="City..." value="{{ request('city') }}">
            <button type="submit" class="btn btn-primary-bm btn-sm px-3">Go</button>
            @if(request('search') || request('city'))
                <a href="{{ route('shops.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
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
    <div class="text-center py-5">
        <div style="font-size:4rem">🏪</div>
        <h5 class="fw-700 mt-3">No shops found</h5>
        <p class="text-muted">Try a different search or city</p>
        <a href="{{ route('shops.index') }}" class="btn btn-primary-bm">Browse All Shops</a>
    </div>
    @endif
</div>
@endsection
