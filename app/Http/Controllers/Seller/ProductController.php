<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService) {}

    public function index(Request $request)
    {
        $user = auth()->user();
        $shop = $user->shop;

        $query = Product::where('shop_id', $shop->id)
            ->with(['primaryImage', 'category'])
            ->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $products = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => Product::where('shop_id', $shop->id)->count(),
            'approved' => Product::where('shop_id', $shop->id)->where('status', 'approved')->count(),
            'pending' => Product::where('shop_id', $shop->id)->where('status', 'pending')->count(),
            'rejected' => Product::where('shop_id', $shop->id)->where('status', 'rejected')->count(),
            'draft' => Product::where('shop_id', $shop->id)->where('status', 'draft')->count(),
        ];

        $maxProducts = $user->max_products;
        $activeCount = Product::where('shop_id', $shop->id)->whereIn('status', ['approved', 'pending'])->count();

        return view('seller.products.index', compact('products', 'counts', 'maxProducts', 'activeCount', 'shop'));
    }

    public function create()
    {
        $user = auth()->user();
        $shop = $user->shop;
        $maxProducts = $user->max_products;
        $activeCount = Product::where('shop_id', $shop->id)->whereIn('status', ['approved', 'pending'])->count();

        if ($maxProducts !== -1 && $activeCount >= $maxProducts) {
            return redirect()->route('seller.subscription.index')
                ->with('error', "You've reached your product limit ({$maxProducts}). Upgrade your plan to add more products.");
        }

        $categories = Category::active()->orderBy('name')->get();
        return view('seller.products.create', compact('categories', 'shop'));
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $data['is_negotiable'] = $request->boolean('is_negotiable');
        $images = $request->file('images', []);

        $product = $this->productService->create(auth()->user(), $data, $images);

        return redirect()->route('seller.products.index')
            ->with('success', "Surplus Stock '{$product->name}' listed successfully and submitted for approval.");
    }

    public function show(Product $product)
    {
        $this->authorize('update', $product);
        $product->load(['images', 'category', 'leads']);

        $leadStats = [
            'views' => $product->leads()->byType('view')->count(),
            'calls' => $product->leads()->byType('call')->count(),
            'whatsapp' => $product->leads()->byType('whatsapp')->count(),
            'directions' => $product->leads()->byType('direction')->count(),
        ];

        return view('seller.products.show', compact('product', 'leadStats'));
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        $product->load('images');
        $categories = Category::active()->orderBy('name')->get();
        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        $data = $request->validated();
        $data['is_negotiable'] = $request->boolean('is_negotiable');
        $images = $request->file('images', []);

        $this->productService->update($product, $data, $images);

        return redirect()->route('seller.products.index')
            ->with('success', "Surplus Stock '{$product->name}' updated successfully.");
    }

    public function toggleStatus(Product $product)
    {
        $this->authorize('update', $product);

        if ($product->status === 'approved') {
            $product->update(['status' => 'inactive']);
            $msg = "Product '{$product->name}' has been deactivated.";
        } elseif ($product->status === 'inactive' || $product->status === 'draft') {
            $product->update(['status' => 'pending']);
            $msg = "Product '{$product->name}' submitted for approval.";
        } elseif ($product->status === 'sold_out') {
            $product->update(['status' => 'pending']);
            $msg = "Product '{$product->name}' re-listed and submitted for approval.";
        } else {
            $msg = "Product status cannot be toggled in current state.";
        }

        return back()->with('success', $msg);
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        $this->productService->delete($product);
        return redirect()->route('seller.products.index')
            ->with('success', 'Product deleted.');
    }

    public function destroyImage(Product $product, ProductImage $image)
    {
        $this->authorize('update', $product);

        if ($image->product_id !== $product->id) {
            abort(403);
        }

        Storage::disk('public')->delete($image->image_path);
        if ($image->thumbnail_path) {
            Storage::disk('public')->delete($image->thumbnail_path);
        }
        $image->delete();

        // If deleted was primary, set next one as primary
        if ($image->is_primary) {
            $product->images()->first()?->update(['is_primary' => true]);
        }

        return back()->with('success', 'Image removed.');
    }

    public function setPrimaryImage(Product $product, ProductImage $image)
    {
        $this->authorize('update', $product);

        $product->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        return back()->with('success', 'Primary image updated.');
    }
}
