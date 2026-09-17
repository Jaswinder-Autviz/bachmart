@extends('layouts.app')
@section('title', $category->name . ' Deals - BachatMart')

@section('content')
<div class="container py-4">
    {{-- Header --}}
    <div class="mb-4 p-4 rounded-3 text-white" style="background:linear-gradient(135deg,#FF6B35,#FF8C61)">
        <div class="d-flex align-items-center gap-3">
            <span style="font-size:3rem">{{ $category->icon }}</span>
            <div>
                <h1 class="fw-800 mb-1" style="font-size:2rem">{{ $category->name }}</h1>
                <p class="mb-0 opacity-90">{{ $products->total() }} deals available in this category</p>
            </div>
        </div>
    </div>

    {{-- Other categories --}}
    <div class="d-flex gap-2 flex-wrap mb-4">
        @foreach($categories as $cat)
        <a href="{{ route('category.show', $cat->slug) }}"
           class="btn btn-sm {{ $cat->slug === $category->slug ? 'btn-primary-bm' : 'btn-outline-secondary' }}">
            {{ $cat->icon }} {{ $cat->name }}
        </a>
        @endforeach
    </div>

    @if($products->count())
    <div class="row g-3">
        @foreach($products as $product)
        <div class="col-6 col-md-4 col-lg-3">
            @include('components.product-card', ['product' => $product])
        </div>
        @endforeach
    </div>
    <div class="mt-4 d-flex justify-content-center">{{ $products->links() }}</div>
    @else
    <div class="text-center py-5">
        <div style="font-size:4rem">{{ $category->icon }}</div>
        <h5 class="fw-700 mt-3">No deals in {{ $category->name }} yet</h5>
        <p class="text-muted">Check back soon or browse other categories</p>
        <a href="{{ route('deals') }}" class="btn btn-primary-bm">Browse All Deals</a>
    </div>
    @endif
</div>
@endsection
