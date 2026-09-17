@extends('layouts.admin')
@section('title', 'Customers')
@section('page-title', 'Customer Management')

@section('content')
<div class="card-admin p-3 mb-4">
    <form method="GET" action="{{ route('admin.customers.index') }}" class="d-flex gap-2">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Name or email..." value="{{ request('search') }}" style="max-width:280px">
        <button type="submit" class="btn btn-sm" style="background:#5a67d8;color:#fff;border:none">Search</button>
    </form>
</div>

<div class="card-admin overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 table-admin" style="font-size:.875rem">
            <thead>
                <tr>
                    <th class="ps-3 py-3">Customer</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Joined</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr>
                    <td class="ps-3">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $customer->avatar_url }}" width="36" height="36" class="rounded-circle" style="object-fit:cover">
                            <div>
                                <div class="fw-600">{{ $customer->name }}</div>
                                <div class="text-muted" style="font-size:.75rem">{{ $customer->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-muted small">{{ $customer->phone ?? '—' }}</td>
                    <td><span class="badge badge-{{ $customer->status === 'active' ? 'approved' : 'rejected' }} rounded-pill">{{ $customer->status }}</span></td>
                    <td class="text-muted small">{{ $customer->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-5 text-muted">No customers found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">{{ $customers->links() }}</div>
@endsection
