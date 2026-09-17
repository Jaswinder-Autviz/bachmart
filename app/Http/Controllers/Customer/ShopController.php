<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Shop::active()->with(['products' => fn($q) => $q->approved()->take(3)]);

        if ($request->city) {
            $query->where('city', 'like', "%{$request->city}%");
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%")
                  ->orWhere('city', 'like', "%{$request->search}%");
            });
        }

        $shops = $query->orderByDesc('is_featured')
            ->orderByDesc('rating')
            ->paginate(18)
            ->withQueryString();

        return view('customer.shops', compact('shops'));
    }

    public function show(Shop $shop)
    {
        if ($shop->status !== 'active') {
            abort(404);
        }

        $shop->increment('views_count');

        $shop->load(['user']);

        $categories = Category::whereHas('products', function ($q) use ($shop) {
            $q->where('shop_id', $shop->id)->where('status', 'approved');
        })->get();

        $productsQuery = $shop->approvedProducts()
            ->with(['primaryImage', 'category'])
            ->orderByDesc('is_featured')
            ->latest();

        if (request('category')) {
            $productsQuery->where('category_id', request('category'));
        }

        $products = $productsQuery->paginate(20)->withQueryString();

        $reviews = $shop->approvedReviews()
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('customer.shop', compact('shop', 'products', 'categories', 'reviews'));
    }
}
