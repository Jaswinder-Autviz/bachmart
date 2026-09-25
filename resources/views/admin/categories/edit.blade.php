@extends('layouts.admin')
@section('title', 'Edit Category')
@section('page-title', 'Edit Category')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card-admin p-4 p-md-5 border-0 shadow-sm rounded-4 bg-white">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                <div>
                    <h5 class="fw-800 text-dark mb-1">Edit Category</h5>
                    <p class="text-muted small mb-0">Update category details and placement.</p>
                </div>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-600">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>

            @if($errors->any())
            <div class="alert alert-danger mb-4 rounded-3">
                <ul class="mb-0 ps-3 small">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-700 small text-dark">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-700 small text-dark">Emoji Icon</label>
                        <input type="text" name="icon" class="form-control" value="{{ old('icon', $category->icon) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-700 small text-dark">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-700 small text-dark">Parent Category</label>
                        <select name="parent_id" class="form-select">
                            <option value="">None (Top Level Root)</option>
                            @foreach($parents as $p)
                            <option value="{{ $p->id }}" {{ old('parent_id', $category->parent_id)==$p->id?'selected':'' }}>{{ $p->icon }} {{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-700 small text-dark">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $category->sort_order) }}" min="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-700 small text-dark">Image / Banner</label>
                        @if($category->image_url)
                        <div class="mb-2">
                            <img src="{{ $category->image_url }}" height="48" class="rounded-3 border" style="object-fit:cover">
                        </div>
                        @endif
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-6 d-flex align-items-end gap-4 pb-1">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="active" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label small fw-700 text-dark" for="active">Active & Visible</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="featured" {{ old('is_featured', $category->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label small fw-700 text-dark" for="featured">Featured On Home</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3 justify-content-end mt-4 pt-3 border-top">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-600">Cancel</a>
                    <button type="submit" class="btn px-4 py-2 rounded-pill fw-700 text-white" style="background:#4F46E5;border:none">
                        <i class="bi bi-check2-circle me-1"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
