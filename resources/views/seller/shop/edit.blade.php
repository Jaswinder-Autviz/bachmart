@extends('layouts.seller')
@section('title', 'Edit Shop')
@section('page-title', 'Edit Shop Profile')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">

{{-- Preview link --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted small mb-0">Update your shop information. Changes are visible immediately.</p>
    <a href="{{ route('shop.show', $shop->slug) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-eye me-1"></i>Preview Shop
    </a>
</div>

@if($errors->any())
<div class="alert alert-danger mb-4"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<form action="{{ route('seller.shop.update') }}" method="POST" enctype="multipart/form-data">
@csrf @method('PUT')

{{-- Basic Info --}}
<div class="card-bm p-4 mb-4">
    <h6 class="fw-700 mb-4 pb-2 border-bottom">Basic Information</h6>
    <div class="row g-3">
        <div class="col-12">
            <label class="form-label fw-600 small">Shop Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $shop->name) }}" required>
        </div>
        <div class="col-12">
            <label class="form-label fw-600 small">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $shop->description) }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-600 small">Shop Logo</label>
            @if($shop->logo)
            <div class="mb-2"><img src="{{ $shop->logo_url }}" width="60" height="60" class="rounded-2" style="object-fit:cover"></div>
            @endif
            <input type="file" name="logo" class="form-control" accept="image/*">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-600 small">Cover Image</label>
            @if($shop->cover_image)
            <div class="mb-2"><img src="{{ $shop->cover_url }}" height="50" class="rounded-2" style="object-fit:cover;max-width:150px"></div>
            @endif
            <input type="file" name="cover_image" class="form-control" accept="image/*">
        </div>
    </div>
</div>

{{-- Contact --}}
<div class="card-bm p-4 mb-4">
    <h6 class="fw-700 mb-4 pb-2 border-bottom">Contact Information</h6>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-600 small">Phone <span class="text-danger">*</span></label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone',$shop->phone) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-600 small">WhatsApp</label>
            <input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp',$shop->whatsapp) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-600 small">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email',$shop->email) }}">
        </div>
    </div>
</div>

{{-- Address --}}
<div class="card-bm p-4 mb-4">
    <h6 class="fw-700 mb-4 pb-2 border-bottom">Address</h6>
    <div class="row g-3">
        <div class="col-12">
            <label class="form-label fw-600 small">Street Address <span class="text-danger">*</span></label>
            <input type="text" name="address" class="form-control" value="{{ old('address',$shop->address) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-600 small">Area</label>
            <input type="text" name="area" class="form-control" value="{{ old('area',$shop->area) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-600 small">City <span class="text-danger">*</span></label>
            <input type="text" name="city" class="form-control" value="{{ old('city',$shop->city) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-600 small">State <span class="text-danger">*</span></label>
            <input type="text" name="state" class="form-control" value="{{ old('state',$shop->state) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-600 small">Pincode</label>
            <input type="text" name="pincode" class="form-control" value="{{ old('pincode',$shop->pincode) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-600 small">Latitude</label>
            <input type="number" step="any" name="latitude" class="form-control" value="{{ old('latitude',$shop->latitude) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-600 small">Longitude</label>
            <input type="number" step="any" name="longitude" class="form-control" value="{{ old('longitude',$shop->longitude) }}">
        </div>
    </div>
</div>

{{-- Opening Hours --}}
<div class="card-bm p-4 mb-4">
    <h6 class="fw-700 mb-4 pb-2 border-bottom">Opening Hours</h6>
    @php $days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday']; @endphp
    <div class="row g-2">
        @foreach($days as $day)
        @php $h = $shop->opening_hours[$day] ?? ['open'=>true,'from'=>'10:00','to'=>'21:00']; @endphp
        <div class="col-12">
            <div class="d-flex align-items-center gap-3 py-2 border-bottom">
                <div class="form-check mb-0" style="min-width:120px">
                    <input class="form-check-input" type="checkbox" name="hours_{{ $day }}_open" value="1"
                           id="open_{{ $day }}" {{ $h['open'] ? 'checked' : '' }}>
                    <label class="form-check-label fw-600 small text-capitalize" for="open_{{ $day }}">{{ $day }}</label>
                </div>
                <input type="time" name="hours_{{ $day }}_from" value="{{ $h['from'] ?? '10:00' }}"
                       class="form-control form-control-sm" style="max-width:120px">
                <span class="text-muted small">to</span>
                <input type="time" name="hours_{{ $day }}_to" value="{{ $h['to'] ?? '21:00' }}"
                       class="form-control form-control-sm" style="max-width:120px">
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Social --}}
<div class="card-bm p-4 mb-4">
    <h6 class="fw-700 mb-4 pb-2 border-bottom">Social Media & Website</h6>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-600 small">Facebook</label>
            <input type="url" name="facebook" class="form-control" value="{{ old('facebook',$shop->facebook) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-600 small">Instagram</label>
            <input type="url" name="instagram" class="form-control" value="{{ old('instagram',$shop->instagram) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-600 small">Twitter / X</label>
            <input type="url" name="twitter" class="form-control" value="{{ old('twitter',$shop->twitter) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-600 small">Website</label>
            <input type="url" name="website" class="form-control" value="{{ old('website',$shop->website) }}">
        </div>
    </div>
</div>

<div class="d-flex gap-3 justify-content-end">
    <a href="{{ route('seller.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary-bm px-5">
        <i class="bi bi-check2-circle me-2"></i>Save Changes
    </button>
</div>
</form>
</div>
</div>
@endsection
