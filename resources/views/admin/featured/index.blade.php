@extends('layouts.admin')
@section('title','Featured Listings')
@section('page-title','Featured Listings')

@section('content')
<div class="row g-3 mb-4">
    @foreach([['label'=>'Total Featured','value'=>$stats['total'],'color'=>'#4F46E5'],['label'=>'Active','value'=>$stats['active'],'color'=>'#10B981'],['label'=>'Pending','value'=>$stats['pending'],'color'=>'#F59E0B'],['label'=>'Revenue','value'=>'₹'.number_format($stats['revenue']),'color'=>'#FF5722']] as $s)
    <div class="col-6 col-md-3">
        <div class="stat-card text-center py-3">
            <div class="stat-value" style="color:{{ $s['color'] }}">{{ $s['value'] }}</div>
            <div class="stat-label">{{ $s['label'] }}</div>
        </div>
    </div>
    @endforeach
</div>

<div class="card-admin overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 table-admin" style="font-size:.875rem">
            <thead><tr>
                <th class="ps-3 py-3">Product</th>
                <th>Seller</th>
                <th>Package</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Expires</th>
                <th class="text-end pe-3">Actions</th>
            </tr></thead>
            <tbody>
                @forelse($featured as $f)
                <tr>
                    <td class="ps-3">
                        @if($f->product)
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $f->product->primary_image_url }}" width="38" height="38" class="rounded-3 border" style="object-fit:cover">
                            <div class="fw-600 text-dark small">{{ Str::limit($f->product->name,30) }}</div>
                        </div>
                        @else <span class="text-muted">Deleted</span>@endif
                    </td>
                    <td class="small">{{ $f->user?->name }}</td>
                    <td><span class="badge badge-featured rounded-pill">{{ $f->package }}</span></td>
                    <td class="fw-600">₹{{ number_format($f->amount_paid) }}</td>
                    <td><span class="badge badge-{{ $f->status === 'active' ? 'approved' : ($f->status === 'pending' ? 'pending' : 'rejected') }} rounded-pill">{{ $f->status }}</span></td>
                    <td class="text-muted small">{{ $f->expires_at?->format('d M Y') ?? '—' }}</td>
                    <td class="text-end pe-3">
                        <div class="d-flex gap-1 justify-content-end align-items-center">
                            @if($f->status === 'pending')
                            <form action="{{ route('admin.featured.approve', $f) }}" method="POST">@csrf<button class="btn btn-sm btn-success py-1 px-2.5 rounded-pill" style="font-size:.75rem">Approve</button></form>
                            @endif
                            @if($f->status !== 'cancelled')
                            <form action="{{ route('admin.featured.cancel', $f) }}" method="POST" onsubmit="return confirm('Cancel featured listing?')">@csrf<button class="btn btn-sm btn-outline-danger py-1 px-2.5 rounded-pill" style="font-size:.75rem">Cancel</button></form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5 text-muted">No featured listings.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">{{ $featured->links() }}</div>
@endsection
