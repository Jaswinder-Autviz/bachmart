<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Setting;
use App\Services\AnalyticsService;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private AnalyticsService $analytics,
        private PaymentService $paymentService
    ) {}

    public function index()
    {
        $user = auth()->user();
        $shop = $user->shop;

        $stats = [
            'total_products' => Product::where('shop_id', $shop->id)->count(),
            'active_products' => Product::where('shop_id', $shop->id)->where('status', 'approved')->count(),
            'pending_products' => Product::where('shop_id', $shop->id)->where('status', 'pending')->count(),
            'sold_out' => Product::where('shop_id', $shop->id)->where('status', 'sold_out')->count(),
            'featured_products' => Product::where('shop_id', $shop->id)->where('is_featured', true)->count(),
            'total_views' => Product::where('shop_id', $shop->id)->sum('views_count'),
            'total_calls' => Lead::forShop($shop->id)->byType('call')->count(),
            'total_whatsapp' => Lead::forShop($shop->id)->byType('whatsapp')->count(),
            'total_directions' => Lead::forShop($shop->id)->byType('direction')->count(),
        ];

        $recentProducts = Product::where('shop_id', $shop->id)
            ->with(['primaryImage', 'category'])
            ->latest()
            ->take(5)
            ->get();

        $recentLeads = Lead::forShop($shop->id)
            ->with(['product'])
            ->latest()
            ->take(10)
            ->get();

        $listingPrice = (float) Setting::get('product_listing_price', 12);
        $hasUnusedPayment = $this->paymentService->hasUnusedListingPayment($user);
        $totalListingPayments = Payment::where('user_id', $user->id)
            ->where('type', 'product_listing')
            ->where('status', 'completed')
            ->count();

        return view('seller.dashboard', compact(
            'stats',
            'recentProducts',
            'recentLeads',
            'shop',
            'listingPrice',
            'hasUnusedPayment',
            'totalListingPayments'
        ));
    }

    public function leads(Request $request)
    {
        $shop = auth()->user()->shop;

        $query = Lead::forShop($shop->id)->with(['product', 'customer']);

        if ($request->type) {
            $query->byType($request->type);
        }

        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->period === 'today') {
            $query->today();
        } elseif ($request->period === 'week') {
            $query->thisWeek();
        } elseif ($request->period === 'month') {
            $query->thisMonth();
        }

        $leads = $query->latest()->paginate(25)->withQueryString();

        $summary = [
            'total' => Lead::forShop($shop->id)->count(),
            'call' => Lead::forShop($shop->id)->byType('call')->count(),
            'whatsapp' => Lead::forShop($shop->id)->byType('whatsapp')->count(),
            'direction' => Lead::forShop($shop->id)->byType('direction')->count(),
            'view' => Lead::forShop($shop->id)->byType('view')->count(),
        ];

        $products = Product::where('shop_id', $shop->id)->orderBy('name')->get();

        return view('seller.leads', compact('leads', 'summary', 'shop', 'products'));
    }

    public function settings()
    {
        $user = auth()->user();
        return view('seller.settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        auth()->user()->update($validated);

        return back()->with('success', 'Settings updated successfully.');
    }
}
