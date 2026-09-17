<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService) {}

    public function index(Request $request)
    {
        $query = Product::with(['shop', 'user', 'primaryImage', 'category'])->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('brand', 'like', "%{$request->search}%")
                  ->orWhereHas('shop', fn($s) => $s->where('name', 'like', "%{$request->search}%"));
            });
        }

        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        if ($request->featured) {
            $query->where('is_featured', true);
        }

        $products = $query->paginate(20)->withQueryString();

        $counts = [
            'all' => Product::count(),
            'pending' => Product::where('status', 'pending')->count(),
            'approved' => Product::where('status', 'approved')->count(),
            'rejected' => Product::where('status', 'rejected')->count(),
            'sold_out' => Product::where('status', 'sold_out')->count(),
            'inactive' => Product::where('status', 'inactive')->count(),
        ];

        $categories = Category::active()->get();

        return view('admin.products.index', compact('products', 'counts', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load(['shop', 'user', 'images', 'category', 'leads' => fn($q) => $q->latest()->take(20)]);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $product->load(['shop', 'images']);
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:100',
            'condition' => 'required|in:new,like_new,good,fair',
            'original_price' => 'required|numeric|min:1',
            'offer_price' => 'required|numeric|min:0.01',
            'quantity' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:50',
            'status' => 'required|in:draft,pending,approved,rejected,expired,sold_out,inactive',
            'description' => 'required|string|min:10',
            'is_negotiable' => 'nullable|boolean',
        ]);

        $validated['is_negotiable'] = $request->has('is_negotiable');

        $product->update($validated);

        return redirect()->route('admin.products.show', $product)
            ->with('success', "Deal '{$product->name}' updated successfully.");
    }

    public function approve(Product $product)
    {
        $this->productService->approve($product);
        return back()->with('success', "Deal '{$product->name}' approved and is now publicly LIVE on the marketplace.");
    }

    public function reject(Request $request, Product $product)
    {
        $request->validate(['reason' => 'required|string|max:500']);
        $this->productService->reject($product, $request->reason);
        return back()->with('success', "Deal has been rejected with the provided reason.");
    }

    public function feature(Product $product)
    {
        $product->update([
            'is_featured' => true,
            'featured_until' => now()->addDays(30),
        ]);
        return back()->with('success', 'Deal featured on homepage successfully.');
    }

    public function unfeature(Product $product)
    {
        $product->update(['is_featured' => false, 'featured_until' => null]);
        return back()->with('success', 'Deal unfeatured.');
    }

    public function markSoldOut(Product $product)
    {
        $product->update(['status' => 'sold_out']);
        return back()->with('success', "Deal '{$product->name}' marked as Sold Out.");
    }

    public function deactivate(Product $product)
    {
        $product->update(['status' => 'inactive']);
        return back()->with('success', "Deal '{$product->name}' deactivated.");
    }

    public function destroy(Product $product)
    {
        $this->productService->delete($product);
        return redirect()->route('admin.products.index')->with('success', 'Product deleted permanently.');
    }
}
