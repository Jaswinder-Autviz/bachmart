@extends('layouts.admin')
@section('title','Edit Category')
@section('page-title','Edit Category')

@section('content')
<div class="row justify-content-center"><div class="col-lg-6">
<div class="card-admin p-4">
    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label fw-600 small">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name',$category->name) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-600 small">Icon</label>
                <input type="text" name="icon" class="form-control" value="{{ old('icon',$category->icon) }}">
            </div>
            <div class="col-12">
                <label class="form-label fw-600 small">Description</label>
                <textarea name="description" class="form-control" rows="2">{{ old('description',$category->description) }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-600 small">Parent Category</label>
                <select name="parent_id" class="form-select">
                    <option value="">None</option>
                    @foreach($parents as $p)<option value="{{ $p->id }}" {{ old('parent_id',$category->parent_id)==$p->id?'selected':'' }}>{{ $p->icon }} {{ $p->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-600 small">Sort Order</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order',$category->sort_order) }}" min="0">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-600 small">Image</label>
                @if($category->image_url)<div class="mb-1"><img src="{{ $category->image_url }}" height="40" class="rounded"></div>@endif
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            <div class="col-md-6 d-flex align-items-end gap-4 pb-1">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="active" {{ old('is_active',$category->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label small fw-600" for="active">Active</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="featured" {{ old('is_featured',$category->is_featured) ? 'checked' : '' }}>
                    <label class="form-check-label small fw-600" for="featured">Featured</label>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2 justify-content-end mt-4">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn px-4" style="background:#5a67d8;color:#fff;border:none">Save Changes</button>
        </div>
    </form>
</div>
</div></div>
@endsection
