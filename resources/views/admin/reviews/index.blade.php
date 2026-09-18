@extends('layouts.admin')
@section('title', 'Customer Reviews')
@section('page-title', 'Customer Reviews Moderation')

@section('content')
{{-- Filter Tabs --}}
<div class="d-flex gap-2 mb-4">
    <a href="{{ route('admin.reviews.index') }}"
       class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }}"
       style="{{ !request('status') ? 'background:#5a67d8;border:none;' : '' }}">
        All Reviews ({{ $counts['all'] ?? 0 }})
    </a>
    <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}"
       class="btn btn-sm {{ request('status') === 'pending' ? 'btn-warning text-dark' : 'btn-outline-warning text-dark' }}">
        Pending Approval ({{ $counts['pending'] ?? 0 }})
    </a>
    <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}"
       class="btn btn-sm {{ request('status') === 'approved' ? 'btn-success' : 'btn-outline-success' }}">
        Approved ({{ $counts['approved'] ?? 0 }})
    </a>
</div>

{{-- Reviews Table --}}
<div class="card-admin overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 table-admin" style="font-size:.875rem">
            <thead>
                <tr>
                    <th class="ps-3 py-3">Rating & Comment</th>
                    <th>Customer</th>
                    <th>Shop / Seller</th>
                    <th>Product</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                <tr>
                    <td class="ps-3" style="max-width: 320px;">
                        <div class="mb-1 text-warning">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                            @endfor
                            <span class="ms-1 fw-700 text-dark" style="font-size:.8rem">{{ $review->rating }}.0</span>
                        </div>
                        <p class="text-muted small mb-0" style="line-height:1.4">
                            {{ $review->comment ?: 'No written comment provided.' }}
                        </p>
                    </td>
                    <td>
                        <div class="fw-600">{{ $review->user->name ?? 'Customer' }}</div>
                        <div class="text-muted small">{{ $review->user->email ?? 'N/A' }}</div>
                    </td>
                    <td>
                        @if($review->shop)
                            <div class="fw-600">{{ $review->shop->name }}</div>
                            <div class="text-muted small">{{ $review->shop->city ?? 'Local' }}</div>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($review->product)
                            <a href="{{ route('product.show', $review->product->slug) }}" target="_blank" class="fw-600 text-dark text-decoration-none">
                                {{ Str::limit($review->product->name, 25) }}
                            </a>
                        @else
                            <span class="text-muted small">Store Review</span>
                        @endif
                    </td>
                    <td>
                        @if($review->is_approved)
                            <span class="badge badge-approved rounded-pill">Approved</span>
                        @else
                            <span class="badge badge-pending rounded-pill">Pending</span>
                        @endif
                    </td>
                    <td class="text-muted small">
                        {{ $review->created_at ? $review->created_at->format('d M Y') : 'N/A' }}
                    </td>
                    <td class="text-end pe-3">
                        <div class="d-flex gap-1 justify-content-end">
                            @if(!$review->is_approved)
                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success py-1 px-2" style="font-size:.75rem" title="Approve Review">
                                    <i class="bi bi-check-lg"></i> Approve
                                </button>
                            </form>
                            @endif
                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                                  onsubmit="return confirm('Delete this review permanently?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" style="font-size:.75rem" title="Delete Review">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-star fs-1 d-block mb-2 opacity-50"></i>
                        No customer reviews found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $reviews->links() }}
</div>
@endsection
