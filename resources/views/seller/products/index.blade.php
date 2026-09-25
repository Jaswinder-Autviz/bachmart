@extends('layouts.seller')
@section('title', 'My Surplus Stock Listings')
@section('page-title', 'My Surplus Stock Inventory')

@section('content')

{{-- ── 1. UNIFIED TOOLBAR CARD (Status Tabs + Search + Action) ── --}}
<div class="card border-0 shadow-sm rounded-4 p-3 p-md-4 mb-4 bg-white" style="border: 1px solid var(--bm-border) !important;">
    <div class="row g-3 align-items-center justify-content-between">
        {{-- Status Filter Tabs --}}
        <div class="col-12 col-xl-7">
            <div class="d-flex gap-1.5 flex-wrap p-1 rounded-4 bg-light" style="border: 1px solid var(--bm-border-light);">
                @php
                $tabConfig = [
                    '' => ['label'=>'All', 'count'=>$counts['all']],
                    'approved' => ['label'=>'Live Approved', 'count'=>$counts['approved']],
                    'pending' => ['label'=>'Pending Review', 'count'=>$counts['pending']],
                    'rejected' => ['label'=>'Rejected', 'count'=>$counts['rejected']],
                    'draft' => ['label'=>'Draft', 'count'=>$counts['draft']],
                ];
                @endphp
                @foreach($tabConfig as $status => $tab)
                @php $isActive = request('status', '') === (string)$status; @endphp
                <a href="{{ route('seller.products.index') }}?status={{ $status }}"
                   class="btn btn-sm rounded-pill px-3 py-1.5 fw-700 d-inline-flex align-items-center gap-1.5 text-decoration-none transition-all"
                   style="{{ $isActive 
                       ? 'background: var(--bm-primary-gradient, #FF5722); color: #FFFFFF; box-shadow: 0 4px 12px rgba(255,87,34,0.3);' 
                       : 'background: transparent; color: #475569;' }}">
                    <span>{{ $tab['label'] }}</span>
                    <span class="badge rounded-pill" style="{{ $isActive ? 'background: rgba(255,255,255,0.25); color: #fff;' : 'background: #E2E8F0; color: #64748B;' }} font-size: 0.72rem;">
                        {{ $tab['count'] }}
                    </span>
                </a>
                @endforeach
            </div>
        </div>

        {{-- Search & Add Button --}}
        <div class="col-12 col-xl-5">
            <div class="d-flex gap-2 justify-content-xl-end align-items-center flex-wrap">
                <form method="GET" action="{{ route('seller.products.index') }}" class="flex-grow-1 flex-xl-grow-0">
                    @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
                    <div class="input-group input-group-sm rounded-pill overflow-hidden border bg-white" style="min-width: 190px; border-color: var(--bm-border) !important;">
                        <input type="text" name="search" class="form-control border-0 px-3" placeholder="Search stock..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-light border-0 px-3 text-muted"><i class="bi bi-search"></i></button>
                    </div>
                </form>

                <a href="{{ route('seller.products.create') }}" class="btn btn-primary-bm btn-sm px-3 px-md-4 rounded-pill">
                    <i class="bi bi-plus-circle-fill me-1"></i>List Stock (₹12)
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ── 2. INVENTORY LISTINGS (Desktop Table & Mobile Cards) ── --}}
@if($products->count())
<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden" style="border: 1px solid var(--bm-border) !important;">
    <div class="p-3 px-md-4 border-bottom bg-light bg-opacity-50 d-flex align-items-center justify-content-between">
        <h6 class="fw-800 mb-0 text-dark">Surplus Stock Items ({{ $products->total() }})</h6>
        <span class="small text-muted">Manage listings & prices</span>
    </div>

    {{-- Desktop Table View --}}
    <div class="table-responsive d-none d-lg-block">
        <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-3 text-uppercase fw-700 text-muted" style="font-size: 0.75rem; letter-spacing: 0.05em; width: 70px;">Item</th>
                    <th class="py-3 text-uppercase fw-700 text-muted" style="font-size: 0.75rem; letter-spacing: 0.05em;">Product Details</th>
                    <th class="py-3 text-uppercase fw-700 text-muted" style="font-size: 0.75rem; letter-spacing: 0.05em;">Clearance Price</th>
                    <th class="py-3 text-uppercase fw-700 text-muted" style="font-size: 0.75rem; letter-spacing: 0.05em;">Category</th>
                    <th class="py-3 text-uppercase fw-700 text-muted" style="font-size: 0.75rem; letter-spacing: 0.05em;">Status</th>
                    <th class="py-3 text-uppercase fw-700 text-muted" style="font-size: 0.75rem; letter-spacing: 0.05em;">Edits Allowed</th>
                    <th class="py-3 text-uppercase fw-700 text-muted" style="font-size: 0.75rem; letter-spacing: 0.05em;">Customer Stats</th>
                    <th class="pe-4 py-3 text-uppercase fw-700 text-muted text-end" style="font-size: 0.75rem; letter-spacing: 0.05em;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td class="ps-4">
                        <img src="{{ $product->primary_image_url }}" width="52" height="52"
                             class="rounded-3 border" style="object-fit: cover;"
                             onerror="this.src='{{ asset('images/product-placeholder.svg') }}'">
                    </td>
                    <td>
                        <div class="fw-800 text-dark text-truncate" style="max-width: 220px;">
                            {{ $product->name }}
                        </div>
                        <div class="text-muted small mt-0.5">
                            @if($product->is_featured)
                                <span class="badge rounded-pill bg-warning-subtle text-dark border border-warning px-2 py-0.5" style="font-size: 0.65rem;">
                                    <i class="bi bi-star-fill text-warning me-1"></i>Featured
                                </span>
                            @endif
                            <span class="text-muted" style="font-size: 0.75rem;">Added {{ $product->created_at->diffForHumans() }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="fw-800 text-dark">₹{{ number_format($product->offer_price) }}</div>
                        <div class="text-muted text-decoration-line-through small" style="font-size: 0.75rem;">₹{{ number_format($product->original_price) }}</div>
                    </td>
                    <td>
                        <span class="badge rounded-pill bg-light text-secondary border px-2.5 py-1" style="font-size: 0.75rem;">
                            {{ $product->category->name ?? '—' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-status-{{ $product->status }} rounded-pill text-capitalize px-2.5 py-1 fw-700" style="font-size: 0.75rem;">
                            {{ str_replace('_', ' ', $product->status) }}
                        </span>
                        @if($product->status === 'rejected' && $product->rejection_reason)
                        <div class="text-danger mt-1" style="font-size: 0.72rem;">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ Str::limit($product->rejection_reason, 30) }}
                        </div>
                        @endif
                    </td>
                    <td>
                        @if($product->edit_count >= 2)
                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2.5 py-1" style="font-size: 0.72rem;" title="2 of 2 free edits used. Editing is locked.">
                                <i class="bi bi-lock-fill me-1"></i>0 / 2 left
                            </span>
                        @else
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1" style="font-size: 0.72rem;" title="{{ 2 - $product->edit_count }} edits remaining">
                                <i class="bi bi-pencil-fill me-1"></i>{{ 2 - $product->edit_count }} / 2 left
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="small text-muted">
                            <span title="Product views"><i class="bi bi-eye text-primary-bm me-1"></i>{{ $product->views_count }}</span>
                            <span class="ms-2" title="Calls received"><i class="bi bi-telephone text-success me-1"></i>{{ $product->calls_count }}</span>
                        </div>
                    </td>
                    <td class="pe-4 text-end">
                        <div class="d-inline-flex gap-1 align-items-center">
                            <a href="{{ route('seller.products.show', $product) }}" class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="View Deal Details">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($product->canSellerEdit())
                                <a href="{{ route('seller.products.edit', $product) }}" class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Edit Deal ({{ $product->remaining_edits }} edits left)">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            @else
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center disabled opacity-50" style="width: 32px; height: 32px; cursor: not-allowed;" title="Edit limit reached (2/2 edits used)" disabled>
                                    <i class="bi bi-lock"></i>
                                </button>
                            @endif
                            @if($product->status === 'approved' && !$product->isFeaturedActive())
                            <a href="{{ route('seller.featured.show', $product) }}" class="btn btn-sm btn-outline-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Feature Deal">
                                <i class="bi bi-star"></i>
                            </a>
                            @endif
                            <form action="{{ route('seller.products.destroy', $product) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this surplus stock listing?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Delete Listing">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile Card List View (Responsive, App Feel) --}}
    <div class="d-lg-none p-3">
        <div class="d-flex flex-column gap-3">
            @foreach($products as $product)
            <div class="p-3 rounded-4 border bg-white shadow-sm" style="border-color: var(--bm-border) !important;">
                <div class="d-flex gap-3 align-items-start mb-2">
                    <img src="{{ $product->primary_image_url }}" width="64" height="64"
                         class="rounded-3 border flex-shrink-0" style="object-fit: cover;"
                         onerror="this.src='{{ asset('images/product-placeholder.svg') }}'">
                    <div class="flex-grow-1 min-w-0">
                        <div class="d-flex justify-content-between align-items-start gap-1">
                            <h6 class="fw-800 text-dark mb-1 text-truncate" style="font-size: 0.95rem;">
                                {{ $product->name }}
                            </h6>
                        </div>
                        <div class="d-flex align-items-baseline gap-2 mb-1">
                            <span class="fw-800 text-primary-bm fs-6">₹{{ number_format($product->offer_price) }}</span>
                            <span class="text-muted text-decoration-line-through small" style="font-size: 0.75rem;">₹{{ number_format($product->original_price) }}</span>
                        </div>
                        <div class="d-flex gap-1 flex-wrap">
                            <span class="badge badge-status-{{ $product->status }} rounded-pill text-capitalize px-2 py-0.5 fw-700" style="font-size: 0.7rem;">
                                {{ str_replace('_', ' ', $product->status) }}
                            </span>
                            <span class="badge bg-light text-secondary border rounded-pill px-2 py-0.5" style="font-size: 0.7rem;">
                                {{ $product->category->name ?? 'Surplus' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-2 border-top mt-2">
                    <div class="small text-muted">
                        <span><i class="bi bi-eye me-1"></i>{{ $product->views_count }}</span> ·
                        <span><i class="bi bi-telephone me-1"></i>{{ $product->calls_count }}</span>
                    </div>
                    <div class="d-flex gap-1.5">
                        <a href="{{ route('seller.products.show', $product) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1" style="font-size: 0.78rem;">
                            <i class="bi bi-eye me-1"></i>View
                        </a>
                        @if($product->canSellerEdit())
                            <a href="{{ route('seller.products.edit', $product) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1" style="font-size: 0.78rem;">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </a>
                        @endif
                        <form action="{{ route('seller.products.destroy', $product) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this listing?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2 py-1" style="font-size: 0.78rem;">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $products->links() }}
</div>
@else
<div class="text-center py-5 card border-0 shadow-sm rounded-4 p-5 bg-white" style="border: 1px solid var(--bm-border) !important;">
    <div style="font-size: 3.5rem">📦</div>
    <h5 class="fw-800 text-dark mt-3 mb-1">No surplus stock listed yet</h5>
    <p class="text-muted small mb-4">Pay ₹12 per product and list your surplus items for local in-store bargain shoppers.</p>
    <a href="{{ route('seller.products.create') }}" class="btn btn-primary-bm px-4 rounded-pill">
        <i class="bi bi-plus-lg me-2"></i>List Your First Surplus Stock (₹12)
    </a>
</div>
@endif

@endsection
