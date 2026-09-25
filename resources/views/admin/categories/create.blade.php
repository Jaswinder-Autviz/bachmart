@extends('layouts.admin')
@section('title', 'Add Category')
@section('page-title', 'Add New Category')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7 col-xl-6">
        <div class="card-admin p-4 border-0 shadow-sm rounded-3 bg-white">
            <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                <div>
                    <h6 class="fw-600 text-dark mb-0 fs-6">Create New Category</h6>
                    <p class="text-muted small mb-0" style="font-size: 0.8rem;">Add a product category for local surplus clearance deals.</p>
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

            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label small text-secondary fw-500 mb-1">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="e.g. Men's Fashion, Electronics" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-secondary fw-500 mb-1">Emoji Icon</label>
                        <input type="text" name="icon" class="form-control form-control-sm" placeholder="e.g. 👗, 📱, 👟" value="{{ old('icon') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label small text-secondary fw-500 mb-1">Description</label>
                        <textarea name="description" class="form-control form-control-sm" rows="2" placeholder="Brief details about what items belong to this category...">{{ old('description') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-secondary fw-500 mb-1">Parent Category</label>
                        <select name="parent_id" class="form-select form-select-sm">
                            <option value="">None (Top Level Root)</option>
                            @foreach($parents as $p)
                            <option value="{{ $p->id }}" {{ old('parent_id')==$p->id?'selected':'' }}>{{ $p->icon }} {{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-secondary fw-500 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control form-control-sm" value="{{ old('sort_order', 0) }}" min="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-secondary fw-500 mb-1">Category Image / Banner</label>
                        <input type="file" name="image" class="form-control form-control-sm" accept="image/*">
                    </div>
                    <div class="col-md-6 d-flex align-items-end gap-3 pb-1">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="active" {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label class="form-check-label small text-secondary fw-500" for="active">Active</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="featured" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label small text-secondary fw-500" for="featured">Featured On Home</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-end mt-4 pt-3 border-top">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-1.5" style="font-size: 0.84rem;">Cancel</a>
                    <button type="submit" class="btn btn-sm px-4 py-1.5 text-white fw-500 rounded-pill" style="background:#4F46E5;border:none;font-size: 0.84rem;">
                        <i class="bi bi-plus-lg me-1"></i>Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
