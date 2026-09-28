<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $selectedCity = session('selected_city') ?? $request->city;

        // Categories
        $categories = Category::active()
            ->withCount(['approvedProducts'])
            ->orderBy('sort_order')
            ->get();

        // Verified Shops (Prioritize verified shops, then featured/rating)
        $verifiedShops = Shop::active()
            ->where('is_verified', true)
            ->with(['products' => fn($q) => $q->approved()->take(3)])
            ->latest()
            ->take(6)
            ->get();

        if ($verifiedShops->count() < 3) {
            $verifiedShops = Shop::active()
                ->orderByDesc('is_verified')
                ->orderByDesc('is_featured')
                ->orderByDesc('rating')
                ->with(['products' => fn($q) => $q->approved()->take(3)])
                ->take(6)
                ->get();
        }

        // All Products Query (No separate featured/top discounts, all products together)
        $productsQuery = Product::approved()
            ->with(['shop', 'primaryImage', 'category']);

        if ($selectedCity) {
            $productsQuery->inCity($selectedCity);
        }

        $products = $productsQuery->latest()->paginate(16)->withQueryString();

        // Hero Spotlight Deal
        $heroDeal = Product::approved()
            ->orderByDesc('discount_percent')
            ->with(['shop', 'primaryImage', 'category'])
            ->first();

        // List of all active shop cities for location selector
        $availableCities = Shop::active()
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->pluck('city')
            ->sort()
            ->values();

        $featuredShops = $verifiedShops;

        return view('customer.home', compact(
            'products',
            'categories',
            'verifiedShops',
            'featuredShops',
            'heroDeal',
            'availableCities',
            'selectedCity'
        ));
    }

    public function setCity(Request $request)
    {
        $city = $request->get('city');
        if ($city && $city !== 'all') {
            session(['selected_city' => $city]);
        } else {
            session()->forget('selected_city');
        }
        return back();
    }

    public function deals(Request $request)
    {
        $query = Product::approved()->with(['shop', 'primaryImage', 'category']);

        // Filters
        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        if ($request->min_discount) {
            $query->where('discount_percent', '>=', $request->min_discount);
        }

        if ($request->city) {
            $query->inCity($request->city);
        }

        if ($request->featured) {
            $query->featured();
        }

        // Sort
        match($request->sort) {
            'discount' => $query->orderByDesc('discount_percent'),
            'price_low' => $query->orderBy('offer_price'),
            'price_high' => $query->orderByDesc('offer_price'),
            'popular' => $query->orderByDesc('views_count'),
            default => $query->orderByDesc('is_featured')->latest(),
        };

        $products = $query->paginate(20)->withQueryString();
        $categories = Category::active()->get();

        return view('customer.deals', compact('products', 'categories'));
    }

    public function category(Category $category)
    {
        $products = Product::approved()
            ->byCategory($category->id)
            ->with(['shop', 'primaryImage'])
            ->orderByDesc('is_featured')
            ->latest()
            ->paginate(20);

        $categories = Category::active()->get();

        return view('customer.category', compact('category', 'products', 'categories'));
    }
}
