@extends('layouts.seller')
@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h5 class="fw-700 text-dark mb-1">Activity Notifications</h5>
        <p class="text-muted small mb-0">{{ $notifications->total() }} alerts received</p>
    </div>
    @if(auth()->user()->unreadNotifications->count() > 0)
    <form action="{{ route('seller.notifications.read-all') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-600">
            <i class="bi bi-check2-all me-1"></i>Mark All Read
        </button>
    </form>
    @endif
</div>

@if($notifications->count())
<div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white" style="border: 1px solid var(--bm-border) !important;">
    @foreach($notifications as $notification)
    @php $data = $notification->data; @endphp
    <div class="d-flex align-items-start gap-3 p-3.5 border-bottom {{ $notification->read_at ? 'bg-white' : 'bg-light bg-opacity-50' }}">
        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
             style="width:40px;height:40px;font-size:1.1rem;
             background:{{ ($data['type']??'') === 'product_approved' ? '#ECFDF5' : (($data['type']??'') === 'product_rejected' ? '#FEF2F2' : '#FFF5F0') }}">
            {{ ($data['type']??'') === 'product_approved' ? '✅' : (($data['type']??'') === 'product_rejected' ? '❌' : (($data['type']??'') === 'subscription_activated' ? '🎉' : '🔔')) }}
        </div>
        <div class="flex-grow-1 min-w-0">
            <div class="small {{ $notification->read_at ? 'text-secondary' : 'fw-600 text-dark' }}">
                {{ $data['message'] ?? 'Notification' }}
            </div>
            <div class="text-muted mt-1" style="font-size:.75rem">{{ $notification->created_at->diffForHumans() }}</div>
        </div>
        @if(!$notification->read_at)
        <form action="{{ route('seller.notifications.read', $notification->id) }}" method="POST" class="flex-shrink-0">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-secondary py-1 px-2.5 rounded-pill" style="font-size:.75rem">Mark Read</button>
        </form>
        @else
        <span class="flex-shrink-0 text-muted small" style="font-size:.75rem">Read</span>
        @endif
    </div>
    @endforeach
</div>
<div class="mt-4 d-flex justify-content-center">{{ $notifications->links() }}</div>
@else
<div class="text-center py-5 card border-0 shadow-sm rounded-4 bg-white" style="border: 1px solid var(--bm-border) !important;">
    <div style="font-size:2.5rem">🔔</div>
    <h6 class="fw-700 text-dark mt-3">No notifications yet</h6>
    <p class="text-muted small">You'll be notified when your surplus stock deals are reviewed or leads arrive.</p>
</div>
@endif
@endsection
