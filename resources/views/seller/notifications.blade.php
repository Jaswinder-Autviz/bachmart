@extends('layouts.seller')
@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted small mb-0">{{ $notifications->total() }} notifications</p>
    @if(auth()->user()->unreadNotifications->count() > 0)
    <form action="{{ route('seller.notifications.read-all') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-secondary">Mark All Read</button>
    </form>
    @endif
</div>

@if($notifications->count())
<div class="card-bm overflow-hidden">
    @foreach($notifications as $notification)
    @php $data = $notification->data; @endphp
    <div class="d-flex align-items-start gap-3 p-3 border-bottom {{ $notification->read_at ? '' : 'bg-light' }}">
        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
             style="width:42px;height:42px;font-size:1.2rem;
             background:{{ ($data['type']??'') === 'product_approved' ? '#f0fff4' : (($data['type']??'') === 'product_rejected' ? '#fff5f5' : '#fff5f0') }}">
            {{ ($data['type']??'') === 'product_approved' ? '✅' : (($data['type']??'') === 'product_rejected' ? '❌' : (($data['type']??'') === 'subscription_activated' ? '🎉' : '🔔')) }}
        </div>
        <div class="flex-grow-1">
            <div class="small {{ $notification->read_at ? 'text-muted' : 'fw-600' }}">
                {{ $data['message'] ?? 'Notification' }}
            </div>
            <div class="text-muted mt-1" style="font-size:.75rem">{{ $notification->created_at->diffForHumans() }}</div>
        </div>
        @if(!$notification->read_at)
        <form action="{{ route('seller.notifications.read', $notification->id) }}" method="POST" class="flex-shrink-0">
            @csrf
            <button type="submit" class="btn btn-xs btn-outline-secondary py-1 px-2" style="font-size:.75rem">Mark Read</button>
        </form>
        @else
        <span class="flex-shrink-0 text-muted" style="font-size:.75rem">Read</span>
        @endif
    </div>
    @endforeach
</div>
<div class="mt-4">{{ $notifications->links() }}</div>
@else
<div class="text-center py-5 card-bm">
    <div style="font-size:3rem">🔔</div>
    <h5 class="fw-700 mt-3">No notifications</h5>
    <p class="text-muted">You'll be notified when products are approved or rejected.</p>
</div>
@endif
@endsection
