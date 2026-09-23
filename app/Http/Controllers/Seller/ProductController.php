<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\PaymentService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private PaymentService $paymentService
    ) {}

    public function index(Request $request)
    {
        $user = auth()->user();
        $shop = $user->shop;

        $query = Product::where('shop_id', $shop->id)
            ->with(['primaryImage', 'category', 'listingPayment'])
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

        $hasUnusedPayment = $this->paymentService->hasUnusedListingPayment($user);

        return view('seller.products.index', compact('products', 'counts', 'shop', 'hasUnusedPayment'));
    }

    public function create()
    {
        $user = auth()->user();
        $shop = $user->shop;

        // Security check: Must have a verified unused ₹12 listing payment
        if (!$this->paymentService->hasUnusedListingPayment($user)) {
            return redirect()->route('seller.products.payment')
                ->with('info', 'Please complete the ₹12 listing payment to create a new product.');
        }

        $categories = Category::active()->orderBy('name')->get();
        return view('seller.products.create', compact('categories', 'shop'));
    }

    public function store(StoreProductRequest $request)
    {
        $user = auth()->user();

        // Server-side security verification: Ensure valid unused payment exists
        if (!$this->paymentService->hasUnusedListingPayment($user)) {
            return redirect()->route('seller.products.payment')
                ->with('error', 'A valid ₹12 listing payment is required before creating a product.');
        }

        $data = $request->validated();
        $data['is_negotiable'] = $request->boolean('is_negotiable');
        $images = $request->file('images', []);

        $product = DB::transaction(function () use ($user, $data, $images) {
            // 1. Create product (edit_count = 0, status = pending)
            $newProduct = $this->productService->create($user, $data, $images);

            // 2. Consume the listing payment authorization for this product
            $this->paymentService->consumeListingPayment($user, $newProduct);

            return $newProduct;
        });

        return redirect()->route('seller.products.index')
            ->with('success', "Surplus Stock '{$product->name}' listed successfully and submitted for Admin approval.");
    }

    public function show(Product $product)
    {
        $this->authorize('update', $product);
        $product->load(['images', 'category', 'leads', 'listingPayment']);

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

        // Enforce 2-Edit limit for shop owners
        if (!$product->canSellerEdit()) {
            return redirect()->route('seller.products.index')
                ->with('error', "Edit limit reached. You have used all 2 edits for this product.");
        }

        $product->load('images');
        $categories = Category::active()->orderBy('name')->get();
        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        // Enforce 2-Edit limit for shop owners
        if (!$product->canSellerEdit()) {
            return redirect()->route('seller.products.index')
                ->with('error', "Edit limit reached. You have used all 2 edits for this product.");
        }

        $data = $request->validated();
        $data['is_negotiable'] = $request->boolean('is_negotiable');
        $images = $request->file('images', []);

        $updatedProduct = $this->productService->update($product, $data, $images);

        $editsUsed = $updatedProduct->edit_count;
        $msg = "Surplus Stock '{$product->name}' updated successfully (Edits used: {$editsUsed}/2). Resubmitted for admin review.";

        return redirect()->route('seller.products.index')->with('success', $msg);
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
