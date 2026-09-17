@extends('layouts.admin')
@section('title', $shop->name)
@section('page-title', 'Shop Detail')

@section('content')
<div class="d-flex gap-2 mb-4 flex-wrap">
    <a href="{{ route('admin.shops.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
    @if($shop->status !== 'active')
        <form action="{{ route('admin.shops.activate', $shop) }}" method="POST">@csrf<button class="btn btn-sm btn-success">Activate</button></form>
    @else
        <form action="{{ route('admin.shops.deactivate', $shop) }}" method="POST">@csrf<button class="btn btn-sm btn-outline-secondary">Deactivate</button></form>
    @endif
    @if(!$shop->is_verified)
        <form action="{{ route('admin.shops.verify', $shop) }}" method="POST">@csrf<button class="btn btn-sm btn-info text-white">Verify Shop</button></form>
    @endif
    <form action="{{ route('admin.shops.feature', $shop) }}" method="POST">@csrf<button class="btn btn-sm btn-warning">{{ $shop->is_featured ? 'Unfeature' : 'Feature' }}</button></form>
    <a href="{{ route('shop.show', $shop->slug) }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-box-arrow-up-right me-1"></i>View Live</a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card-admin p-4 mb-4">
            <div class="text-center mb-3">
                <img src="{{ $shop->logo_url }}" width="80" height="80" class="rounded-3 mb-2" style="object-fit:cover;border:2px solid #e2e8f0">
                <h5 class="fw-700 mb-1">{{ $shop->name }}</h5>
                <div class="d-flex gap-2 justify-content-center flex-wrap mb-2">
                    <span class="badge badge-{{ $shop->status === 'active' ? 'approved' : 'pending' }} rounded-pill">{{ $shop->status }}</span>
                    @if($shop->is_verified)<span class="badge" style="background:#e6f7ee;color:#276749">Verified</span>@endif
                    @if($shop->is_featured)<span class="badge badge-featured rounded-pill">Featured</span>@endif
                </div>
            </div>
            <table class="table table-sm table-borderless small mb-0">
                <tr><td class="text-muted">Phone</td><td class="fw-600">{{ $shop->phone }}</td></tr>
                <tr><td class="text-muted">WhatsApp</td><td class="fw-600">{{ $shop->whatsapp ?: '—' }}</td></tr>
                <tr><td class="text-muted">Email</td><td class="fw-600">{{ $shop->email ?: '—' }}</td></tr>
                <tr><td class="text-muted">City</td><td class="fw-600">{{ $shop->city }}, {{ $shop->state }}</td></tr>
                <tr><td class="text-muted">Rating</td><td class="fw-600">{{ $shop->rating }}/5 ({{ $shop->reviews_count }})</td></tr>
                <tr><td class="text-muted">Seller</td><td class="fw-600"><a href="{{ route('admin.sellers.show', $shop->user) }}" class="text-decoration-none">{{ $shop->user?->name }}</a></td></tr>
            </table>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card-admin p-4 mb-4">
            <h6 class="fw-700 mb-3">Products ({{ $shop->products->count() }})</h6>
            <div class="row g-2">
                @foreach($shop->products->take(6) as $product)
                <div class="col-md-4">
                    <div class="d-flex gap-2 align-items-center p-2 rounded-2" style="background:#f7f8fa">
                        <img src="{{ $product->primary_image_url }}" width="40" height="40" class="rounded" style="object-fit:cover">
                        <div class="min-w-0">
                            <div class="small fw-600 text-truncate">{{ Str::limit($product->name,22) }}</div>
                            <span class="badge badge-{{ $product->status === 'approved' ? 'approved' : 'pending' }}" style="font-size:.65rem">{{ $product->status }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="card-admin p-4">
            <h6 class="fw-700 mb-3">Reviews</h6>
            @forelse($shop->reviews->take(5) as $review)
            <div class="d-flex gap-3 py-2 border-bottom small">
                <div class="stars">{{ str_repeat('★',$review->rating) }}{{ str_repeat('☆',5-$review->rating) }}</div>
                <div class="flex-grow-1">{{ $review->comment ?? 'No comment' }}</div>
                <div class="text-muted flex-shrink-0">{{ $review->user?->name }}</div>
                <span class="badge badge-{{ $review->is_approved?'approved':'pending' }}" style="font-size:.65rem">{{ $review->is_approved?'Approved':'Pending' }}</span>
            </div>
            @empty <p class="text-muted small">No reviews.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
