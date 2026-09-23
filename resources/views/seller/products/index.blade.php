@extends('layouts.seller')
@section('title', 'My Surplus Stock Listings')
@section('page-title', 'My Surplus Stock Inventory')

@section('content')
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
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search stock..." value="{{ request('search') }}" style="max-width:180px">
            <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
        </form>
        <a href="{{ route('seller.products.create') }}" class="btn btn-sm btn-primary-bm px-3 rounded-pill">
            <i class="bi bi-plus-circle-fill me-1"></i>+ List Stock (₹12)
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
                    <th>Edits Left</th>
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
                             onerror="this.src='{{ asset('images/product-placeholder.svg') }}'">
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
                        @if($product->edit_count >= 2)
                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2 py-1" style="font-size:.72rem" title="2 of 2 edits used. Editing is locked.">
                                <i class="bi bi-lock-fill me-1"></i>0 / 2 left (Locked)
                            </span>
                        @else
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1" style="font-size:.72rem" title="{{ 2 - $product->edit_count }} edits remaining">
                                <i class="bi bi-pencil-fill me-1"></i>{{ 2 - $product->edit_count }} / 2 left
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="text-muted" style="font-size:.75rem">
                            <span title="Views"><i class="bi bi-eye me-1"></i>{{ $product->views_count }}</span> ·
                            <span title="Calls"><i class="bi bi-telephone me-1"></i>{{ $product->calls_count }}</span>
                        </div>
                    </td>
                    <td class="text-end pe-3">
                        <div class="d-flex gap-1 justify-content-end align-items-center">
                            <a href="{{ route('seller.products.show', $product) }}" class="btn btn-xs btn-outline-secondary py-1 px-2" title="View Details">
                                <i class="bi bi-eye" style="font-size:.8rem"></i>
                            </a>
                            @if($product->canSellerEdit())
                                <a href="{{ route('seller.products.edit', $product) }}" class="btn btn-xs btn-outline-secondary py-1 px-2" title="Edit ({{ $product->remaining_edits }} edits left)">
                                    <i class="bi bi-pencil" style="font-size:.8rem"></i>
                                </a>
                            @else
                                <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2 disabled opacity-50" title="Edit limit reached (2/2 edits used)" disabled style="cursor:not-allowed">
                                    <i class="bi bi-lock" style="font-size:.8rem"></i>
                                </button>
                            @endif
                            @if($product->status === 'approved' && !$product->isFeaturedActive())
                            <a href="{{ route('seller.featured.show', $product) }}" class="btn btn-xs py-1 px-2" style="background:#fff3cd;color:#856404;font-size:.8rem;border:1px solid #ffc107" title="Feature">
                                <i class="bi bi-star" style="font-size:.8rem"></i>
                            </a>
                            @endif
                            <form action="{{ route('seller.products.destroy', $product) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this product listing?')">
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
    <h5 class="fw-700 mt-3">No surplus stock listed yet</h5>
    <p class="text-muted">Pay ₹12 per product and list your surplus items for nearby shoppers</p>
    <a href="{{ route('seller.products.create') }}" class="btn btn-primary-bm px-4 rounded-pill">
        <i class="bi bi-plus-lg me-2"></i>List Your First Surplus Stock (₹12)
    </a>
</div>
@endif
@endsection
