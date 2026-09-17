@extends('layouts.seller')
@section('title', 'Settings')
@section('page-title', 'Account Settings')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-6">
<div class="card-bm p-4">
    <h6 class="fw-700 mb-4 pb-2 border-bottom">Personal Information</h6>
    <form action="{{ route('seller.settings.update') }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label fw-600 small">Full Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-600 small">Email Address</label>
            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
            <div class="form-text">Email cannot be changed here.</div>
        </div>
        <div class="mb-4">
            <label class="form-label fw-600 small">Phone Number</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
        </div>
        <button type="submit" class="btn btn-primary-bm w-100">Save Changes</button>
    </form>
</div>
</div>
</div>
@endsection
