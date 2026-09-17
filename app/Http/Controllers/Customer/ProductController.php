<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Product;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        // Only approved products publicly visible
        if ($product->status !== 'approved' && !auth()->check()) {
            abort(404);
        }

        if ($product->status !== 'approved' && !auth()->user()?->isAdmin() && auth()->id() !== $product->user_id) {
            abort(404);
        }

        // Track view
        Lead::create([
            'product_id' => $product->id,
            'shop_id' => $product->shop_id,
            'customer_id' => auth()->id(),
            'type' => 'view',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $product->increment('views_count');

        $product->load(['shop', 'images', 'category']);

        $relatedProducts = Product::approved()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['shop', 'primaryImage'])
            ->inRandomOrder()
            ->take(6)
            ->get();

        $shopProducts = Product::approved()
            ->where('shop_id', $product->shop_id)
            ->where('id', '!=', $product->id)
            ->with(['primaryImage'])
            ->take(4)
            ->get();

        return view('customer.product', compact('product', 'relatedProducts', 'shopProducts'));
    }
}
