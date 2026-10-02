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

    public function category(Request $request, Category $category)
    {
        $selectedCity = session('selected_city') ?? $request->city;

        $query = Product::approved()
            ->byCategory($category->id)
            ->with(['shop', 'primaryImage', 'category']);

        // Search within category
        if ($request->filled('q')) {
            $query->search($request->q);
        }

        // City filter
        if ($request->filled('city')) {
            $query->inCity($request->city);
        } elseif ($selectedCity) {
            $query->inCity($selectedCity);
        }

        // Min Discount filter
        if ($request->filled('min_discount')) {
            $query->where('discount_percent', '>=', (float) $request->min_discount);
        }

        // Price range presets
        if ($request->filled('price_range')) {
            match($request->price_range) {
                'under_500' => $query->where('offer_price', '<', 500),
                '500_1000' => $query->whereBetween('offer_price', [500, 1000]),
                '1000_2500' => $query->whereBetween('offer_price', [1000, 2500]),
                'above_2500' => $query->where('offer_price', '>', 2500),
                default => null,
            };
        }

        // Custom min/max price
        if ($request->filled('min_price')) {
            $query->where('offer_price', '>=', (float) $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('offer_price', '<=', (float) $request->max_price);
        }

        // Condition filter
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        // Brand filter
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        // Featured only
        if ($request->boolean('featured')) {
            $query->featured();
        }

        // Negotiable only
        if ($request->boolean('negotiable')) {
            $query->where('is_negotiable', true);
        }

        // Verified shops only
        if ($request->boolean('verified_shop')) {
            $query->whereHas('shop', fn($q) => $q->where('is_verified', true));
        }

        // Sort options
        match($request->sort) {
            'discount' => $query->orderByDesc('discount_percent'),
            'price_low' => $query->orderBy('offer_price'),
            'price_high' => $query->orderByDesc('offer_price'),
            'popular' => $query->orderByDesc('views_count'),
            'newest' => $query->latest(),
            default => $query->orderByDesc('is_featured')->latest(),
        };

        $products = $query->paginate(20)->withQueryString();

        // Spotlight / Hero deal in this category
        $spotlightDeal = Product::approved()
            ->byCategory($category->id)
            ->with(['shop', 'primaryImage', 'category'])
            ->orderByDesc('discount_percent')
            ->first();

        // Categories with approved products count
        $categories = Category::active()
            ->withCount(['approvedProducts'])
            ->orderBy('sort_order')
            ->get();

        // Available cities with items in this category
        $availableCities = Shop::active()
            ->whereHas('products', fn($q) => $q->approved()->where('category_id', $category->id))
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->pluck('city')
            ->sort()
            ->values();

        // Top brands in this category
        $availableBrands = Product::approved()
            ->byCategory($category->id)
            ->whereNotNull('brand')
            ->where('brand', '!=', '')
            ->select('brand')
            ->distinct()
            ->pluck('brand')
            ->take(12);

        // Top verified local shops selling items in this category
        $categoryShops = Shop::active()
            ->whereHas('products', fn($q) => $q->approved()->where('category_id', $category->id))
            ->withCount(['products' => fn($q) => $q->approved()->where('category_id', $category->id)])
            ->orderByDesc('is_verified')
            ->orderByDesc('rating')
            ->take(4)
            ->get();

        // Price statistics
        $stats = Product::approved()
            ->byCategory($category->id)
            ->selectRaw('MIN(offer_price) as min_price, MAX(offer_price) as max_price, MAX(discount_percent) as max_discount, COUNT(*) as total_count')
            ->first();

        return view('customer.category', compact(
            'category',
            'products',
            'categories',
            'spotlightDeal',
            'availableCities',
            'availableBrands',
            'categoryShops',
            'stats',
            'selectedCity'
        ));
    }
}
