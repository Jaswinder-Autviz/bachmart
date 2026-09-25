@extends('layouts.seller')
@section('title', 'Settings')
@section('page-title', 'Account Settings')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white" style="border: 1px solid var(--bm-border) !important;">
            <div class="mb-4 pb-3 border-bottom">
                <h6 class="fw-700 text-dark mb-1">Personal Information</h6>
                <p class="text-muted small mb-0">Update your profile name and contact number.</p>
            </div>
            <form action="{{ route('seller.settings.update') }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label small text-secondary fw-500 mb-1">Full Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small text-secondary fw-500 mb-1">Email Address</label>
                    <input type="email" class="form-control bg-light" value="{{ $user->email }}" disabled>
                    <div class="form-text small" style="font-size: 0.78rem;">Email address cannot be changed here.</div>
                </div>
                <div class="mb-4">
                    <label class="form-label small text-secondary fw-500 mb-1">Phone Number</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                </div>
                <button type="submit" class="btn btn-primary-bm w-100 py-2.5 rounded-pill fw-600">
                    <i class="bi bi-check2-circle me-1"></i>Save Changes
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
