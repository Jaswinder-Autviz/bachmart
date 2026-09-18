@extends('layouts.seller')
@section('title', 'My Surplus Stock')
@section('page-title', 'My Surplus Stock Inventory')

@section('content')
{{-- Plan usage --}}
@if($maxProducts !== -1)
<div class="alert mb-4 py-2 px-3 d-flex align-items-center justify-content-between gap-3"
     style="background:{{ $activeCount >= $maxProducts ? '#fff5f5' : '#f0fff4' }};border:1px solid {{ $activeCount >= $maxProducts ? '#fed7d7' : '#9ae6b4' }};border-radius:10px">
    <div class="small">
        <strong>{{ $activeCount }} / {{ $maxProducts }}</strong> Surplus Stock slots used.
        @if($activeCount >= $maxProducts)
            <span class="text-danger ms-2">Slot limit reached — <a href="{{ route('seller.subscription.index') }}">Upgrade Plan</a> to list more inventory.</span>
        @endif
    </div>
    <div class="progress flex-grow-1" style="max-width:200px;height:6px">
        <div class="progress-bar {{ $activeCount >= $maxProducts ? 'bg-danger' : 'bg-success' }}"
             style="width:{{ min(100, ($activeCount/$maxProducts)*100) }}%"></div>
    </div>
</div>
@endif

{{-- Status tabs --}}
<div class="d-flex gap-2 mb-4 flex-wrap align-items-center justify-content-between">
    <div class="d-flex gap-2 flex-wrap">
        @foreach(['' => 'All ('.$counts['all'].')', 'approved' => 'Live Approved ('.$counts['approved'].')', 'pending' => 'Pending Review ('.$counts['pending'].')', 'rejected' => 'Rejected ('.$counts['rejected'].')', 'draft' => 'Draft ('.$counts['draft'].')'] as $status => $label)
        <a href="{{ route('seller.products.index') }}?status={{ $status }}"
           class="btn btn-sm {{ request('status', '') === $status ? 'btn-primary-bm' : 'btn-outline-secondary' }} rounded-pill px-3">
            {{ $label }}
        </a>
        @endforeach
    </div>
    <div class="d-flex gap-2">
        <form method="GET" action="{{ route('seller.products.index') }}" class="d-flex gap-2">
            @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search Surplus Stock..." value="{{ request('search') }}" style="max-width:180px">
            <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
        </form>
        <a href="{{ route('seller.products.create') }}" class="btn btn-sm btn-primary-bm px-3 rounded-pill">
            <i class="bi bi-plus-circle-fill me-1"></i>+ Add Surplus Stock
        </a>
    </div>
</div>

@if($products->count())
<div class="card-bm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.875rem">
            <thead style="background:#f7f8fa">
                <tr>
                    <th class="ps-3 py-3" style="width:60px">Image</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Stats</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td class="ps-3">
                        <img src="{{ $product->primary_image_url }}" width="48" height="48"
                             class="rounded-2" style="object-fit:cover"
                             onerror="this.src='{{ asset('images/product-placeholder.png') }}'">
                    </td>
                    <td>
                        <div class="fw-600">{{ Str::limit($product->name, 40) }}</div>
                        <div class="text-muted" style="font-size:.75rem">
                                                   @if($product->is_featured) <span class="badge" style="background:#fff3cd;color:#856404;font-size:.65rem"><i class="bi bi-star-fill me-1"></i> Featured</span> @endif
                            Added {{ $product->created_at->diffForHumans() }}
                        </div>
                    </td>
                    <td>
                        <div class="fw-700" style="color:#FF6B35">₹{{ number_format($product->offer_price) }}</div>
                        <div class="text-muted text-decoration-line-through" style="font-size:.75rem">₹{{ number_format($product->original_price) }}</div>
                    </td>
                    <td><span class="badge rounded-pill" style="background:#f0f0f0;color:#666">{{ $product->category->name ?? '—' }}</span></td>
                    <td>
                        <span class="badge badge-status-{{ $product->status }} rounded-pill text-capitalize" style="font-size:.75rem">
                            {{ str_replace('_',' ',$product->status) }}
                        </span>
                        @if($product->status === 'rejected' && $product->rejection_reason)
                        <div class="text-danger mt-1" style="font-size:.72rem">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ Str::limit($product->rejection_reason, 40) }}
                        </div>
                        @endif
                    </td>
                    <td>
                        <div class="text-muted" style="font-size:.75rem">
                            <span title="Views"><i class="bi bi-eye me-1"></i>{{ $product->views_count }}</span> ·
                            <span title="Calls"><i class="bi bi-telephone me-1"></i>{{ $product->calls_count }}</span>
                        </div>
                    </td>
                    <td class="text-end pe-3">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('seller.products.show', $product) }}" class="btn btn-xs btn-outline-secondary py-1 px-2" title="View">
                                <i class="bi bi-eye" style="font-size:.8rem"></i>
                            </a>
                            <a href="{{ route('seller.products.edit', $product) }}" class="btn btn-xs btn-outline-secondary py-1 px-2" title="Edit">
                                <i class="bi bi-pencil" style="font-size:.8rem"></i>
                            </a>
                            @if($product->status === 'approved' && !$product->isFeaturedActive())
                            <a href="{{ route('seller.featured.show', $product) }}" class="btn btn-xs py-1 px-2" style="background:#fff3cd;color:#856404;font-size:.8rem;border:1px solid #ffc107" title="Feature">
                                <i class="bi bi-star" style="font-size:.8rem"></i>
                            </a>
                            @endif
                            <form action="{{ route('seller.products.destroy', $product) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this product?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-outline-danger py-1 px-2" title="Delete">
                                    <i class="bi bi-trash" style="font-size:.8rem"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">{{ $products->links() }}</div>
@else
<div class="text-center py-5 card-bm">
    <div style="font-size:4rem">📦</div>
    <h5 class="fw-700 mt-3">No products yet</h5>
    <p class="text-muted">Start listing your products to reach local customers</p>
    <a href="{{ route('seller.products.create') }}" class="btn btn-primary-bm px-4">
        <i class="bi bi-plus-lg me-2"></i>Add Your First Product
    </a>
</div>
@endif
@endsection
