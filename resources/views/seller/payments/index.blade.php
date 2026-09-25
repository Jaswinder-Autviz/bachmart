@extends('layouts.seller')
@section('title', 'Payment History')
@section('page-title', 'Payment History')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h5 class="fw-800 mb-1 text-dark">Listing Fee & Payment History</h5>
        <p class="text-muted small mb-0">Record of all ₹12 listing payments and transaction receipts.</p>
    </div>
    <a href="{{ route('seller.products.payment') }}" class="btn btn-primary-bm px-4 py-2 fs-6 rounded-pill">
        <i class="bi bi-plus-circle-fill me-1"></i>+ Pay & List New Stock
    </a>
</div>

{{-- Payments Table Card --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white" style="border: 1px solid var(--bm-border) !important;">
    <div class="p-3 px-md-4 border-bottom bg-light bg-opacity-50 d-flex align-items-center justify-content-between">
        <h6 class="fw-800 mb-0 text-dark">Transaction Records</h6>
        <span class="small text-muted">{{ $payments->total() }} payments found</span>
    </div>

    {{-- Desktop Table View --}}
    <div class="table-responsive d-none d-lg-block">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-3 text-uppercase text-muted fw-700" style="font-size:0.75rem">Transaction ID</th>
                    <th class="py-3 text-uppercase text-muted fw-700" style="font-size:0.75rem">Date & Time</th>
                    <th class="py-3 text-uppercase text-muted fw-700" style="font-size:0.75rem">Type / Purpose</th>
                    <th class="py-3 text-uppercase text-muted fw-700" style="font-size:0.75rem">Gateway</th>
                    <th class="py-3 text-uppercase text-muted fw-700" style="font-size:0.75rem">Amount</th>
                    <th class="py-3 text-uppercase text-muted fw-700" style="font-size:0.75rem">Linked Product</th>
                    <th class="pe-4 py-3 text-uppercase text-muted fw-700 text-end" style="font-size:0.75rem">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td class="ps-4">
                        <div class="fw-700 text-dark" style="font-size:0.85rem">
                            #{{ $payment->payment_id }}
                        </div>
                        @if($payment->notes)
                            <div class="text-muted small" style="font-size:0.75rem">{{ Str::limit($payment->notes, 30) }}</div>
                        @endif
                    </td>
                    <td>
                        <div class="text-dark small fw-600">{{ $payment->created_at->format('d M Y') }}</div>
                        <div class="text-muted small" style="font-size:0.75rem">{{ $payment->created_at->format('h:i A') }}</div>
                    </td>
                    <td>
                        @if($payment->type === 'product_listing')
                            <span class="badge bg-primary-subtle text-primary fw-700 px-2.5 py-1 rounded-pill" style="font-size:0.75rem">
                                Listing Fee (₹12)
                            </span>
                        @elseif($payment->type === 'featured')
                            <span class="badge bg-warning-subtle text-warning-emphasis fw-700 px-2.5 py-1 rounded-pill" style="font-size:0.75rem">
                                Featured Boost
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary fw-700 px-2.5 py-1 rounded-pill" style="font-size:0.75rem">
                                {{ ucfirst($payment->type) }}
                            </span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border text-uppercase rounded-pill px-2.5 py-1" style="font-size:0.7rem">
                            {{ $payment->gateway ?? 'Direct' }}
                        </span>
                    </td>
                    <td>
                        <div class="fw-800 text-dark">₹{{ number_format($payment->amount, 2) }}</div>
                    </td>
                    <td>
                        @if($payment->product)
                            <a href="{{ route('seller.products.edit', $payment->product->id) }}" class="text-decoration-none fw-600 text-primary-bm small">
                                {{ Str::limit($payment->product->name, 25) }} <i class="bi bi-box-arrow-up-right ms-1" style="font-size:0.7rem"></i>
                            </a>
                        @elseif($payment->status === 'completed' && !$payment->used_at)
                            <span class="badge bg-success-subtle text-success fw-700 px-2.5 py-1 rounded-pill" style="font-size:0.72rem">
                                <i class="bi bi-check2-circle me-1"></i>Active Credit Ready
                            </span>
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td class="pe-4 text-end">
                        @if($payment->status === 'completed')
                            <span class="badge bg-success-subtle text-success fw-700 px-2.5 py-1 rounded-pill" style="font-size:0.75rem">
                                <i class="bi bi-check-circle-fill me-1"></i>Completed
                            </span>
                        @elseif($payment->status === 'pending')
                            <span class="badge bg-warning-subtle text-warning-emphasis fw-700 px-2.5 py-1 rounded-pill" style="font-size:0.75rem">
                                <i class="bi bi-hourglass-split me-1"></i>Pending
                            </span>
                        @else
                            <span class="badge bg-danger-subtle text-danger fw-700 px-2.5 py-1 rounded-pill" style="font-size:0.75rem">
                                {{ ucfirst($payment->status) }}
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="fs-1">💳</div>
                        <h6 class="fw-700 mt-2">No payment transactions yet</h6>
                        <p class="text-muted small">Your listing transaction history will appear here once you pay and list items.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Card View (No Overflow) --}}
    <div class="d-lg-none p-3">
        @forelse($payments as $payment)
        <div class="p-3 rounded-4 border bg-white shadow-sm mb-3" style="border-color: var(--bm-border) !important;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-800 text-dark" style="font-size: 0.88rem;">#{{ $payment->payment_id }}</span>
                <span class="fw-800 text-primary-bm fs-6">₹{{ number_format($payment->amount, 2) }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2 small text-muted">
                <span>{{ $payment->created_at->format('d M Y, h:i A') }}</span>
                <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-0.5 fw-700" style="font-size: 0.72rem;">{{ ucfirst($payment->status) }}</span>
            </div>
            @if($payment->product)
            <div class="pt-2 border-top small">
                <span class="text-muted">Item:</span>
                <a href="{{ route('seller.products.edit', $payment->product->id) }}" class="fw-700 text-dark text-decoration-none ms-1">
                    {{ $payment->product->name }}
                </a>
            </div>
            @endif
        </div>
        @empty
        <div class="text-center py-4 text-muted small">
            No payments recorded yet.
        </div>
        @endforelse
    </div>
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $payments->links() }}
</div>
@endsection
