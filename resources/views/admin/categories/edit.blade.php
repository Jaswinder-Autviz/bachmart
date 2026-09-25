@extends('layouts.admin')
@section('title', 'Edit Category')
@section('page-title', 'Edit Category')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7 col-xl-6">
        <div class="card-admin p-4 border-0 shadow-sm rounded-3 bg-white">
            <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                <div>
                    <h6 class="fw-600 text-dark mb-0 fs-6">Edit Category</h6>
                    <p class="text-muted small mb-0" style="font-size: 0.8rem;">Update category details, emoji icon and home visibility.</p>
                </div>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1" style="font-size: 0.8rem;">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>

            @if($errors->any())
            <div class="alert alert-danger mb-3 rounded-2 py-2 px-3">
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
                        <label class="form-label small text-secondary fw-500 mb-1">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name', $category->name) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-secondary fw-500 mb-1">Emoji Icon</label>
                        <input type="text" name="icon" class="form-control form-control-sm" value="{{ old('icon', $category->icon) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label small text-secondary fw-500 mb-1">Description</label>
                        <textarea name="description" class="form-control form-control-sm" rows="2">{{ old('description', $category->description) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-secondary fw-500 mb-1">Parent Category</label>
                        <select name="parent_id" class="form-select form-select-sm">
                            <option value="">None (Top Level Root)</option>
                            @foreach($parents as $p)
                            <option value="{{ $p->id }}" {{ old('parent_id', $category->parent_id)==$p->id?'selected':'' }}>{{ $p->icon }} {{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-secondary fw-500 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control form-control-sm" value="{{ old('sort_order', $category->sort_order) }}" min="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-secondary fw-500 mb-1">Image / Banner</label>
                        @if($category->image_url)
                        <div class="mb-2">
                            <img src="{{ $category->image_url }}" height="40" class="rounded-2 border" style="object-fit:cover">
                        </div>
                        @endif
                        <input type="file" name="image" class="form-control form-control-sm" accept="image/*">
                    </div>
                    <div class="col-md-6 d-flex align-items-end gap-3 pb-1">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="active" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label small text-secondary fw-500" for="active">Active</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="featured" {{ old('is_featured', $category->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label small text-secondary fw-500" for="featured">Featured On Home</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-end mt-4 pt-3 border-top">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-1.5" style="font-size: 0.84rem;">Cancel</a>
                    <button type="submit" class="btn btn-sm px-4 py-1.5 text-white fw-500 rounded-pill" style="background:#4F46E5;border:none;font-size: 0.84rem;">
                        <i class="bi bi-check2-circle me-1"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
