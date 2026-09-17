@extends('layouts.admin')
@section('title', 'Clearance Deals & Products')
@section('page-title', 'Clearance Deals Approval & Management')

@section('content')
{{-- Status Tabs --}}
<div class="d-flex gap-2 mb-4 flex-wrap align-items-center justify-content-between">
    <div class="d-flex gap-2 flex-wrap">
        @php
        $tabs = [
            '' => 'All ('.$counts['all'].')',
            'pending' => 'Pending Review ('.$counts['pending'].')',
            'approved' => 'Live Approved ('.$counts['approved'].')',
            'rejected' => 'Rejected ('.$counts['rejected'].')',
            'sold_out' => 'Sold Out ('.($counts['sold_out'] ?? 0).')',
            'inactive' => 'Inactive ('.($counts['inactive'] ?? 0).')',
        ];
        @endphp
        @foreach($tabs as $status => $label)
        <a href="{{ route('admin.products.index') }}?status={{ $status }}"
           class="btn btn-sm {{ request('status','') === $status ? ($status === 'pending' ? 'btn-warning text-dark fw-700' : 'btn-primary') : 'btn-outline-secondary' }} rounded-pill px-3">
            {{ $label }}
        </a>
        @endforeach
    </div>

    <form method="GET" class="d-flex gap-2" action="{{ route('admin.products.index') }}">
        @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
        <select name="category" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()" style="max-width:160px">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected':'' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <input type="text" name="search" class="form-control form-control-sm rounded-pill" placeholder="Search deals or shops..." value="{{ request('search') }}" style="max-width:200px">
        <button type="submit" class="btn btn-sm btn-outline-secondary rounded-circle"><i class="bi bi-search"></i></button>
    </form>
</div>

<div class="card-admin overflow-hidden rounded-4 border-0 shadow-sm bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 table-admin" style="font-size:.875rem">
            <thead>
                <tr>
                    <th class="ps-3 py-3" style="width:56px">Img</th>
                    <th>Product / Deal</th>
                    <th>Shop & City</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th class="text-end pe-3">Approval & Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td class="ps-3">
                        <img src="{{ $product->primary_image_url }}" width="46" height="46"
                             class="rounded-2 border" style="object-fit:cover"
                             onerror="this.onerror=null; this.src='{{ asset('images/product-placeholder.svg') }}';">
                    </td>
                    <td>
                        <div class="fw-700 text-dark">
                            <a href="{{ route('admin.products.show', $product) }}" class="text-decoration-none text-dark">
                                {{ Str::limit($product->name, 36) }}
                            </a>
                        </div>
                        <div class="text-muted" style="font-size:.75rem">
                            {{ $product->brand ? $product->brand.' · ' : '' }}{{ $product->category?->name }}
                            @if($product->is_featured) <span class="badge badge-featured ms-1">⭐ Featured</span>@endif
                        </div>
                    </td>
                    <td>
                        @if($product->shop)
                        <a href="{{ route('admin.shops.show', $product->shop) }}" class="text-decoration-none small fw-700 text-dark">
                            {{ Str::limit($product->shop->name, 22) }}
                        </a>
                        <div class="text-muted" style="font-size:.72rem">{{ $product->shop->city }}</div>
                        @else
                        <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td>
                        <div class="fw-800 text-primary-bm">₹{{ number_format($product->offer_price) }}</div>
                        <div class="text-muted text-decoration-line-through" style="font-size:.73rem">₹{{ number_format($product->original_price) }}</div>
                    </td>
                    <td>
                        <span class="badge badge-{{ $product->status === 'approved' ? 'approved' : ($product->status === 'pending' ? 'pending' : ($product->status === 'rejected' ? 'rejected' : 'draft')) }} rounded-pill text-capitalize px-2 py-1">
                            {{ str_replace('_',' ',$product->status) }}
                        </span>
                    </td>
                    <td class="text-muted small">{{ $product->created_at->format('d M Y') }}</td>
                    <td class="text-end pe-3">
                        <div class="d-flex gap-1 justify-content-end flex-wrap align-items-center">
                            <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-outline-secondary py-1 px-2" title="View details" style="font-size:.75rem">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary py-1 px-2" title="Edit deal" style="font-size:.75rem">
                                <i class="bi bi-pencil"></i>
                            </a>

                            @if($product->status === 'pending')
                            <form action="{{ route('admin.products.approve', $product) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm py-1 px-2 fw-700" style="background:#c6f6d5;color:#276749;border:none;border-radius:4px;font-size:.75rem">
                                    ✓ Approve
                                </button>
                            </form>
                            <button type="button" class="btn btn-sm py-1 px-2 fw-700" style="background:#fed7d7;color:#9b2c2c;border:none;border-radius:4px;font-size:.75rem"
                                    data-bs-toggle="modal" data-bs-target="#rejectModal{{ $product->id }}">
                                ✗ Reject
                            </button>
                            @endif

                            @if(!$product->is_featured)
                            <form action="{{ route('admin.products.feature', $product) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm py-1 px-2" style="background:#fefcbf;color:#744210;border:1px solid #f6e05e;border-radius:4px;font-size:.75rem" title="Feature deal">
                                    ★
                                </button>
                            </form>
                            @else
                            <form action="{{ route('admin.products.unfeature', $product) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm py-1 px-2" style="background:#e2e8f0;color:#4a5568;border:none;border-radius:4px;font-size:.75rem" title="Unfeature deal">
                                    Unfeature
                                </button>
                            </form>
                            @endif

                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                  onsubmit="return confirm('Delete this deal permanently?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" style="font-size:.75rem">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                {{-- Reject Modal for each item --}}
                <div class="modal fade" id="rejectModal{{ $product->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 rounded-4 shadow">
                            <div class="modal-header border-0 pb-0">
                                <h6 class="modal-title fw-800 text-danger">Reject Clearance Deal</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('admin.products.reject', $product) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <p class="small text-muted mb-2">Deal: <strong>{{ $product->name }}</strong></p>
                                    <label class="form-label small fw-700">Reason for rejection <span class="text-danger">*</span></label>
                                    <textarea name="reason" class="form-control" rows="3" required
                                              placeholder="e.g. Image quality is unclear, price mismatch, missing size/color details..."></textarea>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger btn-sm rounded-pill px-4">Reject Deal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <tr><td colspan="7" class="text-center py-5 text-muted">No deals found matching this status.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4 d-flex justify-content-center">{{ $products->links() }}</div>
@endsection
