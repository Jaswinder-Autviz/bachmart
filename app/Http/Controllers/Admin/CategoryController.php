<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount(['approvedProducts'])->orderBy('sort_order')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::whereNull('parent_id')->active()->orderBy('name')->get();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'icon' => ['nullable', 'string', 'max:50'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
            'is_featured' => ['sometimes', 'boolean'],
        ]);

        $slug = Str::slug($validated['name']);
        $i = 1;
        $baseSlug = $slug;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i++;
        }
        $validated['slug'] = $slug;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured', false);

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category created.');
    }

    public function edit(Category $category)
    {
        $parents = Category::whereNull('parent_id')->where('id', '!=', $category->id)->active()->orderBy('name')->get();
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'icon' => ['nullable', 'string', 'max:50'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
            'is_featured' => ['sometimes', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            if ($category->image) Storage::disk('public')->delete($category->image);
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured', false);

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated.');
    }

    public function toggleStatus(Category $category)
    {
        $category->update(['is_active' => !$category->is_active]);
        $status = $category->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Category '{$category->name}' {$status}.");
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete category with products. Reassign products first.');
        }
        if ($category->image) Storage::disk('public')->delete($category->image);
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted.');
    }
}
