@extends('layouts.admin')
@section('title', 'Categories')
@section('page-title', 'Category Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h5 class="fw-800 text-dark mb-1">Product Categories</h5>
        <p class="text-muted small mb-0">Manage marketplace categories and home featured sections.</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-sm px-4 py-2 rounded-pill fw-700 text-white shadow-sm" style="background:#4F46E5;border:none">
        <i class="bi bi-plus-lg me-1"></i>Add New Category
    </a>
</div>

<div class="card-admin overflow-hidden rounded-4 border-0 shadow-sm bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 table-admin" style="font-size:.875rem">
            <thead>
                <tr>
                    <th class="ps-3 py-3">Category</th>
                    <th>Approved Products</th>
                    <th>Sort Order</th>
                    <th>Status</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr>
                    <td class="ps-3">
                        <div class="d-flex align-items-center gap-3">
                            <span style="font-size:1.6rem">{{ $category->icon }}</span>
                            <div>
                                <div class="fw-700 text-dark">{{ $category->name }}</div>
                                <div class="text-muted" style="font-size:.73rem">
                                    {{ $category->slug }}
                                    @if($category->is_featured) · <span class="badge badge-featured rounded-pill">Featured</span>@endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge bg-light text-dark border rounded-pill px-2.5 py-1 fw-700">{{ $category->approved_products_count }} items</span></td>
                    <td class="text-muted fw-600">{{ $category->sort_order }}</td>
                    <td><span class="badge badge-{{ $category->is_active ? 'approved' : 'rejected' }} rounded-pill px-2.5 py-1">{{ $category->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td class="text-end pe-3">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-secondary py-1 px-2.5 rounded-pill" style="font-size:.75rem">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                  onsubmit="return confirm('Delete category? Products must be reassigned first.')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger py-1 px-2.5 rounded-pill" style="font-size:.75rem">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4 d-flex justify-content-center">{{ $categories->links() }}</div>
@endsection
