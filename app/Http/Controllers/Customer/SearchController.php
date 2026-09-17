<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));
        $productQuery = Product::approved()->with(['shop', 'primaryImage', 'category']);

        // Search term
        if (!empty($q)) {
            $productQuery->where(function ($query) use ($q) {
                $query->where('products.name', 'like', "%{$q}%")
                      ->orWhere('products.description', 'like', "%{$q}%")
                      ->orWhere('products.brand', 'like', "%{$q}%")
                      ->orWhereHas('shop', fn($s) => $s->where('name', 'like', "%{$q}%")
                                                        ->orWhere('city', 'like', "%{$q}%")
                                                        ->orWhere('area', 'like', "%{$q}%"))
                      ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%{$q}%"));
            });
        }

        // Filters: Category
        if ($request->category) {
            $productQuery->where('category_id', $request->category);
        }

        // Filters: Price
        if ($request->min_price) {
            $productQuery->where('offer_price', '>=', $request->min_price);
        }
        if ($request->max_price) {
            $productQuery->where('offer_price', '<=', $request->max_price);
        }

        // Filters: Location (City / Area)
        if ($request->city) {
            $productQuery->inCity($request->city);
        }
        if ($request->area) {
            $productQuery->whereHas('shop', fn($s) => $s->where('area', 'like', "%{$request->area}%"));
        }

        // Filters: Shop
        if ($request->shop) {
            $productQuery->where('shop_id', $request->shop);
        }

        // Filters: Min Discount %
        if ($request->min_discount) {
            $productQuery->where('discount_percent', '>=', $request->min_discount);
        }

        // Filters: Availability (in stock)
        if ($request->available) {
            $productQuery->where('quantity', '>', 0);
        }

        // Filters: Featured
        if ($request->featured) {
            $productQuery->featured();
        }

        // Sorting: Newest, Lowest Price, Highest Discount
        match($request->sort) {
            'discount' => $productQuery->orderByDesc('discount_percent'),
            'price_low' => $productQuery->orderBy('offer_price'),
            'price_high' => $productQuery->orderByDesc('offer_price'),
            'newest' => $productQuery->latest(),
            'popular' => $productQuery->orderByDesc('views_count'),
            default => $productQuery->orderByDesc('is_featured')->latest(),
        };

        $products = $productQuery->paginate(20)->withQueryString();

        // Shop search matches
        $shops = collect();
        if (!empty($q)) {
            $shops = Shop::active()
                ->where(function ($query) use ($q) {
                    $query->where('name', 'like', "%{$q}%")
                          ->orWhere('city', 'like', "%{$q}%")
                          ->orWhere('area', 'like', "%{$q}%")
                          ->orWhere('brand', 'like', "%{$q}%");
                })
                ->withCount(['approvedProducts'])
                ->take(6)
                ->get();
        }

        $categories = Category::active()->withCount(['approvedProducts'])->get();
        $availableCities = Shop::active()->whereNotNull('city')->where('city', '!=', '')->distinct()->pluck('city')->sort()->values();
        $shopsList = Shop::active()->orderBy('name')->get();

        return view('customer.search', compact('products', 'shops', 'categories', 'availableCities', 'shopsList', 'q'));
    }
}
