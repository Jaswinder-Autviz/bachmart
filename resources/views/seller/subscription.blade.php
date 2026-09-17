@extends('layouts.seller')
@section('title', 'Subscription')
@section('page-title', 'Subscription Plans')

@section('content')
{{-- Current Plan --}}
@if($currentSubscription)
<div class="alert d-flex align-items-center gap-3 mb-4"
     style="background:#f0fff4;border:1px solid #9ae6b4;border-radius:12px">
    <i class="bi bi-patch-check-fill text-success fs-4"></i>
    <div class="flex-grow-1">
        <div class="fw-700">{{ $currentSubscription->subscriptionPlan->name }} Plan Active</div>
        <div class="small text-muted">Expires {{ $currentSubscription->expires_at?->format('d M Y') }} · {{ $currentSubscription->days_remaining }} days remaining</div>
    </div>
    <span class="badge bg-success">Active</span>
</div>
@endif

<div class="text-center mb-5">
    <h4 class="fw-800 mb-1">Choose Your Plan</h4>
    <p class="text-muted">Upgrade to list more products and get more visibility</p>
</div>

<div class="row g-4 justify-content-center">
    @foreach($plans as $plan)
    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-3 h-100 position-relative {{ $plan->is_popular ? 'border-2' : '' }}"
             style="{{ $plan->is_popular ? 'border:2px solid #FF6B35 !important;' : '' }}">
            @if($plan->is_popular)
            <div class="position-absolute top-0 start-50 translate-middle">
                <span class="badge px-3 py-1 rounded-pill" style="background:#FF6B35;color:#fff">MOST POPULAR</span>
            </div>
            @endif
            <div class="card-body p-4 d-flex flex-column">
                <div class="text-center mb-4 {{ $plan->is_popular ? 'mt-3' : '' }}">
                    <div class="fw-800 fs-5 mb-1">{{ $plan->name }}</div>
                    @if($plan->isFree())
                        <div style="font-size:2rem;font-weight:800;color:#1a202c">Free</div>
                    @else
                        <div>
                            <span style="font-size:2rem;font-weight:800;color:#FF6B35">₹{{ number_format($plan->price) }}</span>
                            <span class="text-muted small">/month</span>
                        </div>
                    @endif
                    <div class="text-muted small mt-1">{{ $plan->description }}</div>
                </div>

                <ul class="list-unstyled flex-grow-1 mb-4">
                    <li class="d-flex align-items-center gap-2 mb-2 small">
                        <i class="bi bi-check-circle-fill text-success"></i>
                        <span>{{ $plan->max_products_label }} active products</span>
                    </li>
                    @foreach($plan->features ?? [] as $feature)
                    <li class="d-flex align-items-center gap-2 mb-2 small">
                        <i class="bi bi-check-circle-fill text-success"></i>
                        <span>{{ $feature }}</span>
                    </li>
                    @endforeach
                    @if($plan->featured_product_discount > 0)
                    <li class="d-flex align-items-center gap-2 mb-2 small">
                        <i class="bi bi-check-circle-fill text-success"></i>
                        <span>{{ $plan->featured_product_discount }}% off featured listings</span>
                    </li>
                    @endif
                </ul>

                @php
                    $isCurrentPlan = $currentSubscription && $currentSubscription->subscriptionPlan->slug === $plan->slug;
                @endphp

                @if($isCurrentPlan)
                    <button class="btn btn-success w-100" disabled><i class="bi bi-check2 me-1"></i>Current Plan</button>
                @elseif($plan->isFree())
                    <button class="btn btn-outline-secondary w-100" disabled>Free Plan</button>
                @else
                    <form action="{{ route('seller.subscription.subscribe', $plan) }}" method="POST"
                          onsubmit="return confirm('Subscribe to {{ $plan->name }} plan for ₹{{ number_format($plan->price) }}/month?')">
                        @csrf
                        <button type="submit" class="btn w-100 {{ $plan->is_popular ? 'btn-primary-bm' : 'btn-outline-secondary' }}">
                            {{ $currentSubscription ? 'Switch to ' : 'Subscribe — ' }}₹{{ number_format($plan->price) }}/mo
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Payment note --}}
<div class="alert alert-light mt-4 text-center small">
    <i class="bi bi-info-circle me-1"></i>
    For this MVP, subscriptions are auto-activated. In production, a payment gateway (Razorpay/Paytm) will be integrated.
</div>

{{-- History --}}
@if($history->count())
<div class="card-bm p-4 mt-5">
    <h6 class="fw-700 mb-3">Subscription History</h6>
    <div class="table-responsive">
        <table class="table table-sm align-middle mb-0" style="font-size:.875rem">
            <thead style="background:#f7f8fa">
                <tr><th class="ps-3 py-2">Plan</th><th>Amount</th><th>Status</th><th>Start</th><th class="pe-3">Expires</th></tr>
            </thead>
            <tbody>
                @foreach($history as $sub)
                <tr>
                    <td class="ps-3 fw-600">{{ $sub->subscriptionPlan->name }}</td>
                    <td>₹{{ number_format($sub->amount_paid) }}</td>
                    <td><span class="badge badge-{{ $sub->status === 'active' ? 'approved' : 'pending' }} rounded-pill text-capitalize">{{ $sub->status }}</span></td>
                    <td class="text-muted">{{ $sub->starts_at?->format('d M Y') ?? '—' }}</td>
                    <td class="pe-3 text-muted">{{ $sub->expires_at?->format('d M Y') ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
