@extends('layouts.admin')
@section('title','Categories')
@section('page-title','Category Management')

@section('content')
<div class="d-flex justify-content-end mb-4">
    <a href="{{ route('admin.categories.create') }}" class="btn btn-sm" style="background:#5a67d8;color:#fff;border:none">
        <i class="bi bi-plus-lg me-1"></i>Add Category
    </a>
</div>

<div class="card-admin overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 table-admin" style="font-size:.875rem">
            <thead>
                <tr>
                    <th class="ps-3 py-3">Category</th>
                    <th>Products</th>
                    <th>Order</th>
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
                                <div class="fw-600">{{ $category->name }}</div>
                                <div class="text-muted" style="font-size:.73rem">
                                    {{ $category->slug }}
                                    @if($category->is_featured) · <span style="color:#FF6B35">Featured</span>@endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="fw-600">{{ $category->approved_products_count }}</td>
                    <td class="text-muted">{{ $category->sort_order }}</td>
                    <td><span class="badge badge-{{ $category->is_active ? 'approved' : 'rejected' }} rounded-pill">{{ $category->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td class="text-end pe-3">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-xs btn-outline-secondary py-1 px-2" style="font-size:.75rem"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                  onsubmit="return confirm('Delete category? Products must be reassigned first.')">
                                @csrf @method('DELETE')
                                <button class="btn btn-xs btn-outline-danger py-1 px-2" style="font-size:.75rem"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">{{ $categories->links() }}</div>
@endsection
