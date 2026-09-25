@extends('layouts.app')
@section('title', $category->name . ' Clearance Deals - BachatMart')

@section('content')
<div class="container py-4 py-lg-5">
    {{-- Category Banner --}}
    <div class="mb-4 p-4 p-lg-5 rounded-4 text-white shadow-sm position-relative overflow-hidden"
         style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); border: 1px solid #334155;">
        <div class="row align-items-center g-3">
            <div class="col-auto">
                <div class="d-flex align-items-center justify-content-center rounded-4 shadow-sm"
                     style="width: 72px; height: 72px; background: rgba(255, 87, 34, 0.15); border: 1.5px solid rgba(255, 87, 34, 0.35); font-size: 2.2rem; color: #FF7A45;">
                    {{ $category->icon ?? '🏷️' }}
                </div>
            </div>
            <div class="col">
                <span class="badge rounded-pill px-3 py-1 mb-2 fw-700" style="background: rgba(255, 255, 255, 0.1); font-size: 0.75rem;">DEPARTMENT CLEARANCE</span>
                <h1 class="fw-900 mb-1 text-white" style="font-size: clamp(1.8rem, 3vw, 2.4rem); letter-spacing: -0.03em;">{{ $category->name }}</h1>
                <p class="mb-0 text-light text-opacity-75 small">{{ $products->total() }} active clearance deals available in this category</p>
            </div>
        </div>
    </div>

    {{-- Other categories --}}
    <div class="d-flex gap-2 flex-wrap mb-4 pb-2">
        @foreach($categories as $cat)
        <a href="{{ route('category.show', $cat->slug) }}"
           class="btn btn-sm {{ $cat->slug === $category->slug ? 'btn-primary-bm' : 'btn-outline-secondary' }} rounded-pill px-3 fw-600">
            {{ $cat->icon }} {{ $cat->name }}
        </a>
        @endforeach
    </div>

    @if($products->count())
    <div class="row g-3 g-md-4 mobile-2-col">
        @foreach($products as $product)
        <div class="col-6 col-md-4 col-lg-3">
            @include('components.product-card', ['product' => $product])
        </div>
        @endforeach
    </div>
    <div class="mt-5 d-flex justify-content-center">{{ $products->links() }}</div>
    @else
    <div class="text-center py-5 card border-0 shadow-sm rounded-4 p-5 bg-white">
        <div style="font-size: 4rem">{{ $category->icon ?? '📦' }}</div>
        <h5 class="fw-800 text-dark mt-3 mb-1">No deals in {{ $category->name }} yet</h5>
        <p class="text-muted small mb-4">Check back soon as local shopkeepers add new surplus listings regularly.</p>
        <div>
            <a href="{{ route('deals') }}" class="btn btn-primary-bm px-4">Browse All Deals</a>
        </div>
    </div>
    @endif
</div>
@endsection
