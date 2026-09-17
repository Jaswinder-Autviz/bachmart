@extends('layouts.seller')
@section('title', 'Create Shop')
@section('page-title', 'Create Your Shop')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">

<div class="alert alert-info d-flex align-items-center gap-2 mb-4" style="border-radius:10px">
    <i class="bi bi-info-circle-fill fs-5"></i>
    <div>Set up your shop profile. Customers will see this information when they discover your products.</div>
</div>

<form action="{{ route('seller.shop.store') }}" method="POST" enctype="multipart/form-data">
@csrf

{{-- Basic Info --}}
<div class="card-bm p-4 mb-4">
    <h6 class="fw-700 mb-4 pb-2 border-bottom">Basic Information</h6>
    <div class="row g-3">
        <div class="col-md-12">
            <label class="form-label fw-600 small">Shop Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   placeholder="e.g. Fashion Hub Delhi" value="{{ old('name') }}" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-12">
            <label class="form-label fw-600 small">Description</label>
            <textarea name="description" class="form-control" rows="3"
                      placeholder="Tell customers about your shop, what you sell, specialties...">{{ old('description') }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-600 small">Shop Logo</label>
            <input type="file" name="logo" class="form-control" accept="image/jpeg,image/png,image/webp">
            <div class="form-text">Recommended: 200×200 px, max 2MB</div>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-600 small">Cover Image</label>
            <input type="file" name="cover_image" class="form-control" accept="image/jpeg,image/png,image/webp">
            <div class="form-text">Recommended: 1200×300 px, max 3MB</div>
        </div>
    </div>
</div>

{{-- Contact --}}
<div class="card-bm p-4 mb-4">
    <h6 class="fw-700 mb-4 pb-2 border-bottom">Contact Information</h6>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-600 small">Phone Number <span class="text-danger">*</span></label>
            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                   placeholder="+91 98765 43210" value="{{ old('phone', auth()->user()->phone) }}" required>
            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-600 small">WhatsApp Number</label>
            <input type="text" name="whatsapp" class="form-control" placeholder="9876543210 (without +91)"
                   value="{{ old('whatsapp') }}">
        </div>
        <div class="col-md-12">
            <label class="form-label fw-600 small">Business Email</label>
            <input type="email" name="email" class="form-control" placeholder="shop@example.com"
                   value="{{ old('email') }}">
        </div>
    </div>
</div>

{{-- Address --}}
<div class="card-bm p-4 mb-4">
    <h6 class="fw-700 mb-4 pb-2 border-bottom">Shop Address</h6>
    <div class="row g-3">
        <div class="col-12">
            <label class="form-label fw-600 small">Street Address <span class="text-danger">*</span></label>
            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                   placeholder="Shop No., Street, Landmark" value="{{ old('address') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-600 small">Area / Locality</label>
            <input type="text" name="area" class="form-control" placeholder="e.g. Lajpat Nagar" value="{{ old('area') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-600 small">City <span class="text-danger">*</span></label>
            <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                   placeholder="e.g. Mumbai" value="{{ old('city') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-600 small">State <span class="text-danger">*</span></label>
            <input type="text" name="state" class="form-control @error('state') is-invalid @enderror"
                   placeholder="e.g. Maharashtra" value="{{ old('state') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-600 small">Pincode</label>
            <input type="text" name="pincode" class="form-control" placeholder="400001" value="{{ old('pincode') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-600 small">Latitude <span class="text-muted">(for directions)</span></label>
            <input type="number" step="any" name="latitude" class="form-control" placeholder="e.g. 19.0760" value="{{ old('latitude') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-600 small">Longitude</label>
            <input type="number" step="any" name="longitude" class="form-control" placeholder="e.g. 72.8777" value="{{ old('longitude') }}">
        </div>
        <div class="col-12">
            <div class="alert alert-light py-2 small mb-0">
                <i class="bi bi-lightbulb me-1"></i>
                To find your coordinates: Open <a href="https://maps.google.com" target="_blank">Google Maps</a>, right-click your shop location → "What's here?" to see lat/long.
            </div>
        </div>
    </div>
</div>

{{-- Opening Hours --}}
<div class="card-bm p-4 mb-4">
    <h6 class="fw-700 mb-4 pb-2 border-bottom">Opening Hours</h6>
    @php $days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday']; @endphp
    <div class="row g-2">
        @foreach($days as $day)
        <div class="col-12">
            <div class="d-flex align-items-center gap-3 py-2 border-bottom">
                <div class="form-check mb-0" style="min-width:120px">
                    <input class="form-check-input" type="checkbox" name="hours_{{ $day }}_open" value="1"
                           id="open_{{ $day }}" {{ in_array($day,['monday','tuesday','wednesday','thursday','friday','saturday']) ? 'checked' : '' }}>
                    <label class="form-check-label fw-600 small text-capitalize" for="open_{{ $day }}">{{ $day }}</label>
                </div>
                <input type="time" name="hours_{{ $day }}_from" value="10:00"
                       class="form-control form-control-sm" style="max-width:120px">
                <span class="text-muted small">to</span>
                <input type="time" name="hours_{{ $day }}_to" value="21:00"
                       class="form-control form-control-sm" style="max-width:120px">
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Social --}}
<div class="card-bm p-4 mb-4">
    <h6 class="fw-700 mb-4 pb-2 border-bottom">Social Media & Website <span class="text-muted fw-400 small">(optional)</span></h6>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-600 small"><i class="bi bi-facebook text-primary me-1"></i>Facebook URL</label>
            <input type="url" name="facebook" class="form-control" placeholder="https://facebook.com/yourpage" value="{{ old('facebook') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-600 small"><i class="bi bi-instagram me-1" style="color:#e1306c"></i>Instagram URL</label>
            <input type="url" name="instagram" class="form-control" placeholder="https://instagram.com/yourpage" value="{{ old('instagram') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-600 small"><i class="bi bi-globe me-1"></i>Website</label>
            <input type="url" name="website" class="form-control" placeholder="https://yourwebsite.com" value="{{ old('website') }}">
        </div>
    </div>
</div>

<div class="d-flex gap-3 justify-content-end">
    <button type="submit" class="btn btn-primary-bm px-5">
        <i class="bi bi-check2-circle me-2"></i>Create Shop & Continue
    </button>
</div>
</form>
</div>
</div>
@endsection
