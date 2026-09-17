@extends('layouts.admin')
@section('title', $product->name . ' - Deal Review')
@section('page-title', 'Deal Review & Management')

@section('content')
<div class="d-flex gap-2 mb-4 flex-wrap align-items-center justify-content-between">
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Products
        </a>

        @if($product->status === 'pending')
        <form action="{{ route('admin.products.approve', $product) }}" method="POST">
            @csrf
            <button class="btn btn-sm btn-success fw-600 shadow-sm">
                <i class="bi bi-check-circle-fill me-1"></i>Approve Deal
            </button>
        </form>
        <button class="btn btn-sm btn-danger fw-600 shadow-sm" data-bs-toggle="modal" data-bs-target="#rejectModal">
            <i class="bi bi-x-circle-fill me-1"></i>Reject Deal
        </button>
        @endif

        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary fw-600">
            <i class="bi bi-pencil-square me-1"></i>Edit Deal
        </a>

        @if(!$product->is_featured)
        <form action="{{ route('admin.products.feature', $product) }}" method="POST">
            @csrf
            <button class="btn btn-sm btn-outline-warning text-dark fw-600">
                <i class="bi bi-star-fill text-warning me-1"></i>Feature on Homepage
            </button>
        </form>
        @else
        <form action="{{ route('admin.products.unfeature', $product) }}" method="POST">
            @csrf
            <button class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-star me-1"></i>Unfeature
            </button>
        </form>
        @endif

        @if($product->status !== 'sold_out')
        <form action="{{ route('admin.products.sold-out', $product) }}" method="POST">
            @csrf
            <button class="btn btn-sm btn-outline-secondary" onclick="return confirm('Mark this product as Sold Out?')">
                <i class="bi bi-bag-check me-1"></i>Mark Sold Out
            </button>
        </form>
        @endif

        @if($product->status !== 'inactive')
        <form action="{{ route('admin.products.deactivate', $product) }}" method="POST">
            @csrf
            <button class="btn btn-sm btn-outline-secondary" onclick="return confirm('Deactivate this product from public view?')">
                <i class="bi bi-slash-circle me-1"></i>Deactivate
            </button>
        </form>
        @endif
    </div>

    <div class="d-flex gap-2">
        @if($product->status === 'approved')
        <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="btn btn-sm btn-outline-success">
            <i class="bi bi-box-arrow-up-right me-1"></i>View Live Page
        </a>
        @endif
        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this deal permanently?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-outline-danger">
                <i class="bi bi-trash me-1"></i>Delete
            </button>
        </form>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        {{-- Product Overview Card --}}
        <div class="card-admin p-4 mb-4">
            <div class="d-flex gap-4 align-items-start flex-wrap">
                <img src="{{ $product->primary_image_url }}" width="130" height="130"
                     class="rounded-3 flex-shrink-0 border" style="object-fit:cover"
                     onerror="this.onerror=null; this.src='{{ asset('images/product-placeholder.svg') }}';">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                        <span class="badge badge-{{ $product->status === 'approved' ? 'approved' : ($product->status === 'pending' ? 'pending' : ($product->status === 'rejected' ? 'rejected' : 'draft')) }} rounded-pill text-capitalize px-3 py-1">
                            Status: {{ str_replace('_', ' ', $product->status) }}
                        </span>
                        @if($product->is_featured)
                            <span class="badge badge-featured rounded-pill">⭐ Featured</span>
                        @endif
                        <span class="badge bg-light text-muted border rounded-pill">{{ $product->category?->name }}</span>
                    </div>

                    <h4 class="fw-800 text-dark mb-2">{{ $product->name }}</h4>

                    <div class="d-flex gap-3 align-items-baseline mb-2">
                        <span class="fw-800 fs-4 text-primary-bm">₹{{ number_format($product->offer_price) }}</span>
                        @if($product->original_price > $product->offer_price)
                            <span class="text-muted text-decoration-line-through">₹{{ number_format($product->original_price) }}</span>
                            <span class="badge bg-success">{{ round($product->discount_percent) }}% OFF</span>
                        @endif
                    </div>

                    @if($product->rejection_reason)
                    <div class="alert alert-danger mt-2 py-2 px-3 mb-0 small">
                        <strong>Rejection Reason:</strong> {{ $product->rejection_reason }}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Description --}}
        <div class="card-admin p-4 mb-4">
            <h6 class="fw-700 mb-2">Product Description</h6>
            <div class="text-secondary" style="line-height:1.7">{!! nl2br(e($product->description)) !!}</div>
        </div>

        {{-- Images Gallery --}}
        <div class="card-admin p-4 mb-4">
            <h6 class="fw-700 mb-3">Uploaded Images ({{ $product->images->count() }})</h6>
            <div class="d-flex gap-3 flex-wrap">
                @forelse($product->images as $img)
                <div class="position-relative">
                    <img src="{{ $img->url }}" width="100" height="100" class="rounded-3 border" style="object-fit:cover">
                    @if($img->is_primary)
                        <span class="position-absolute bottom-0 start-0 w-100 bg-dark text-white text-center py-1" style="font-size:10px">PRIMARY</span>
                    @endif
                </div>
                @empty
                <div class="text-muted small">No images uploaded. (Using category default visual)</div>
                @endforelse
            </div>
        </div>

        {{-- Leads Recorded --}}
        <div class="card-admin p-4">
            <h6 class="fw-700 mb-3">Customer Leads & Direct Contacts</h6>
            @forelse($product->leads as $lead)
            <div class="d-flex align-items-center justify-content-between py-2 border-bottom small">
                <div class="d-flex align-items-center gap-2">
                    <span class="fs-5">{{ $lead->type==='call'?'📞':($lead->type==='whatsapp'?'💬':($lead->type==='direction'?'📍':'👁')) }}</span>
                    <span class="fw-700 text-capitalize">{{ $lead->type }} Lead</span>
                    <span class="text-muted">({{ $lead->ip_address }})</span>
                </div>
                <div class="text-muted">{{ $lead->created_at->format('d M Y, H:i') }}</div>
            </div>
            @empty
            <p class="text-muted small mb-0">No customer interactions recorded yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Sidebar: Details & Seller/Shop --}}
    <div class="col-lg-4">
        {{-- Product Details --}}
        <div class="card-admin p-4 mb-4">
            <h6 class="fw-700 mb-3">Deal Specifications</h6>
            <table class="table table-sm table-borderless small mb-0">
                <tr><td class="text-muted ps-0">Brand</td><td class="fw-600 text-end">{{ $product->brand ?: '—' }}</td></tr>
                <tr><td class="text-muted ps-0">Condition</td><td class="fw-600 text-capitalize text-end">{{ str_replace('_',' ',$product->condition) }}</td></tr>
                <tr><td class="text-muted ps-0">SKU</td><td class="fw-600 text-end">{{ $product->sku ?: '—' }}</td></tr>
                <tr><td class="text-muted ps-0">Quantity</td><td class="fw-600 text-end">{{ $product->quantity }} available</td></tr>
                <tr><td class="text-muted ps-0">Views</td><td class="fw-600 text-end">{{ number_format($product->views_count) }}</td></tr>
                <tr><td class="text-muted ps-0">Calls</td><td class="fw-600 text-end">{{ $product->calls_count }}</td></tr>
                <tr><td class="text-muted ps-0">WhatsApp</td><td class="fw-600 text-end">{{ $product->whatsapp_count }}</td></tr>
                <tr><td class="text-muted ps-0">Directions</td><td class="fw-600 text-end">{{ $product->directions_count }}</td></tr>
                <tr><td class="text-muted ps-0">Submitted</td><td class="fw-600 text-end">{{ $product->created_at->format('d M Y') }}</td></tr>
                @if($product->expires_at)
                <tr><td class="text-muted ps-0">Expires</td><td class="fw-600 text-end text-danger">{{ $product->expires_at->format('d M Y') }}</td></tr>
                @endif
            </table>
        </div>

        {{-- Shop / Seller Information --}}
        @if($product->shop)
        <div class="card-admin p-4">
            <h6 class="fw-700 mb-3">Seller & Shop Information</h6>
            <div class="d-flex gap-3 align-items-center mb-3">
                <img src="{{ $product->shop->logo_url }}" width="52" height="52" class="rounded-2 border" style="object-fit:cover">
                <div>
                    <a href="{{ route('admin.shops.show', $product->shop) }}" class="fw-700 text-decoration-none text-dark">
                        {{ $product->shop->name }}
                    </a>
                    <div class="text-muted small">{{ $product->shop->city }}</div>
                    <div class="small text-muted">Owner: {{ $product->user->name }}</div>
                </div>
            </div>
            <div class="small text-muted border-top pt-2">
                <div><strong>Phone:</strong> {{ $product->shop->phone }}</div>
                <div><strong>WhatsApp:</strong> {{ $product->shop->whatsapp }}</div>
                <div class="mt-1"><strong>Address:</strong> {{ $product->shop->full_address }}</div>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Reject Reason Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-800 text-danger">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>Reject Deal Listing
                </h6>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.products.reject', $product) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="small text-muted mb-3">
                        Provide a clear rejection reason so the shopkeeper can rectify and resubmit:
                    </p>
                    <label class="form-label small fw-700">Rejection Reason <span class="text-danger">*</span></label>
                    <textarea name="reason" class="form-control" rows="4" required
                              placeholder="e.g. Photo resolution is too low, prices do not reflect an authentic clearance discount, or description lacks item condition."></textarea>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm rounded-pill px-4">Reject Deal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
