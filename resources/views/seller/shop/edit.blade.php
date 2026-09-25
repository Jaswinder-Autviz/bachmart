@extends('layouts.seller')
@section('title', 'Edit Shop')
@section('page-title', 'Edit Shop Profile')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">

{{-- Preview link --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h5 class="fw-700 text-dark mb-1">Shop Profile & Info</h5>
        <p class="text-muted small mb-0">Update your store address, photos, and opening timings.</p>
    </div>
    <a href="{{ route('shop.show', $shop->slug) }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-600">
        <i class="bi bi-eye me-1"></i>Preview Shop
    </a>
</div>

@if($errors->any())
<div class="alert alert-danger mb-4 rounded-3"><ul class="mb-0 ps-3 small">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<form action="{{ route('seller.shop.update') }}" method="POST" enctype="multipart/form-data">
@csrf @method('PUT')

{{-- Basic Info --}}
<div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white" style="border: 1px solid var(--bm-border) !important;">
    <h6 class="fw-700 text-dark mb-4 pb-2 border-bottom">Basic Information</h6>
    <div class="row g-3">
        <div class="col-12">
            <label class="form-label small text-secondary fw-500 mb-1">Shop Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $shop->name) }}" required>
        </div>
        <div class="col-12">
            <label class="form-label small text-secondary fw-500 mb-1">Description</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Briefly describe your shop and what items you specialize in...">{{ old('description', $shop->description) }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label small text-secondary fw-500 mb-1">Shop Logo</label>
            @if($shop->logo)
            <div class="mb-2"><img src="{{ $shop->logo_url }}" width="56" height="56" class="rounded-3 border" style="object-fit:cover"></div>
            @endif
            <input type="file" name="logo" class="form-control" accept="image/*">
        </div>
        <div class="col-md-6">
            <label class="form-label small text-secondary fw-500 mb-1">Cover Image</label>
            @if($shop->cover_image)
            <div class="mb-2"><img src="{{ $shop->cover_url }}" height="56" class="rounded-3 border" style="object-fit:cover;max-width:150px"></div>
            @endif
            <input type="file" name="cover_image" class="form-control" accept="image/*">
        </div>
    </div>
</div>

{{-- Contact --}}
<div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white" style="border: 1px solid var(--bm-border) !important;">
    <h6 class="fw-700 text-dark mb-4 pb-2 border-bottom">Contact Information</h6>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label small text-secondary fw-500 mb-1">Phone <span class="text-danger">*</span></label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone',$shop->phone) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label small text-secondary fw-500 mb-1">WhatsApp</label>
            <input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp',$shop->whatsapp) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label small text-secondary fw-500 mb-1">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email',$shop->email) }}">
        </div>
    </div>
</div>

{{-- Address --}}
<div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white" style="border: 1px solid var(--bm-border) !important;">
    <h6 class="fw-700 text-dark mb-4 pb-2 border-bottom">Address & Location</h6>
    <div class="row g-3">
        <div class="col-12">
            <label class="form-label small text-secondary fw-500 mb-1">Street Address <span class="text-danger">*</span></label>
            <input type="text" name="address" class="form-control" value="{{ old('address',$shop->address) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label small text-secondary fw-500 mb-1">Area / Landmark</label>
            <input type="text" name="area" class="form-control" value="{{ old('area',$shop->area) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label small text-secondary fw-500 mb-1">City <span class="text-danger">*</span></label>
            <input type="text" name="city" class="form-control" value="{{ old('city',$shop->city) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label small text-secondary fw-500 mb-1">State <span class="text-danger">*</span></label>
            <input type="text" name="state" class="form-control" value="{{ old('state',$shop->state) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label small text-secondary fw-500 mb-1">Pincode</label>
            <input type="text" name="pincode" class="form-control" value="{{ old('pincode',$shop->pincode) }}">
        </div>
    </div>
</div>

{{-- Opening Hours --}}
<div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white" style="border: 1px solid var(--bm-border) !important;">
    <h6 class="fw-700 text-dark mb-4 pb-2 border-bottom">Opening Hours</h6>
    @php $days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday']; @endphp
    <div class="row g-2">
        @foreach($days as $day)
        @php $h = $shop->opening_hours[$day] ?? ['open'=>true,'from'=>'10:00','to'=>'21:00']; @endphp
        <div class="col-12">
            <div class="d-flex align-items-center gap-3 py-2 border-bottom flex-wrap">
                <div class="form-check mb-0" style="min-width:120px">
                    <input class="form-check-input" type="checkbox" name="hours_{{ $day }}_open" value="1"
                           id="open_{{ $day }}" {{ $h['open'] ? 'checked' : '' }}>
                    <label class="form-check-label fw-500 small text-capitalize text-dark" for="open_{{ $day }}">{{ $day }}</label>
                </div>
                <input type="time" name="hours_{{ $day }}_from" value="{{ $h['from'] ?? '10:00' }}"
                       class="form-control form-control-sm" style="max-width:130px">
                <span class="text-muted small">to</span>
                <input type="time" name="hours_{{ $day }}_to" value="{{ $h['to'] ?? '21:00' }}"
                       class="form-control form-control-sm" style="max-width:130px">
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Social --}}
<div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white" style="border: 1px solid var(--bm-border) !important;">
    <h6 class="fw-700 text-dark mb-4 pb-2 border-bottom">Social Media & Website (Optional)</h6>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label small text-secondary fw-500 mb-1">Facebook URL</label>
            <input type="url" name="facebook" class="form-control" placeholder="https://facebook.com/..." value="{{ old('facebook',$shop->facebook) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label small text-secondary fw-500 mb-1">Instagram URL</label>
            <input type="url" name="instagram" class="form-control" placeholder="https://instagram.com/..." value="{{ old('instagram',$shop->instagram) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label small text-secondary fw-500 mb-1">Twitter / X URL</label>
            <input type="url" name="twitter" class="form-control" placeholder="https://twitter.com/..." value="{{ old('twitter',$shop->twitter) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label small text-secondary fw-500 mb-1">Website URL</label>
            <input type="url" name="website" class="form-control" placeholder="https://myshop.com" value="{{ old('website',$shop->website) }}">
        </div>
    </div>
</div>

<div class="d-flex gap-3 justify-content-end mb-5">
    <a href="{{ route('seller.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-600">Cancel</a>
    <button type="submit" class="btn btn-primary-bm px-5 rounded-pill fw-600">
        <i class="bi bi-check2-circle me-1"></i>Save Changes
    </button>
</div>
</form>
</div>
</div>
@endsection
