@extends('layouts.admin')
@section('title','Add Category')
@section('page-title','Add Category')

@section('content')
<div class="row justify-content-center"><div class="col-lg-6">
<div class="card-admin p-4">
    @if($errors->any())<div class="alert alert-danger mb-3"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label fw-600 small">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-600 small">Icon (emoji)</label>
                <input type="text" name="icon" class="form-control" placeholder="e.g. 👗" value="{{ old('icon') }}">
            </div>
            <div class="col-12">
                <label class="form-label fw-600 small">Description</label>
                <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-600 small">Parent Category</label>
                <select name="parent_id" class="form-select">
                    <option value="">None (top level)</option>
                    @foreach($parents as $p)<option value="{{ $p->id }}" {{ old('parent_id')==$p->id?'selected':'' }}>{{ $p->icon }} {{ $p->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-600 small">Sort Order</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order',0) }}" min="0">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-600 small">Image</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            <div class="col-md-6 d-flex align-items-end gap-4 pb-1">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="active" {{ old('is_active',1) ? 'checked' : '' }}>
                    <label class="form-check-label small fw-600" for="active">Active</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="featured" {{ old('is_featured') ? 'checked' : '' }}>
                    <label class="form-check-label small fw-600" for="featured">Featured</label>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2 justify-content-end mt-4">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn px-4" style="background:#5a67d8;color:#fff;border:none">Add Category</button>
        </div>
    </form>
</div>
</div></div>
@endsection
