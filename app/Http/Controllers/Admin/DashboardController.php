<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Subscription;
use App\Models\User;
use App\Services\AnalyticsService;

class DashboardController extends Controller
{
    public function __construct(private AnalyticsService $analytics) {}

    public function index()
    {
        $stats = $this->analytics->getAdminStats();

        $recentSellers = User::where('role', 'seller')
            ->with('shop')
            ->latest()
            ->take(8)
            ->get();

        $recentProducts = Product::where('status', 'pending')
            ->with(['shop', 'primaryImage', 'user'])
            ->latest()
            ->take(8)
            ->get();

        $recentLeads = Lead::with(['product', 'shop'])
            ->latest()
            ->take(10)
            ->get();

        $recentPayments = Payment::with('user')
            ->latest()
            ->take(8)
            ->get();

        // Revenue chart data (last 6 months)
        $revenueData = Payment::completed()
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(amount) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        return view('admin.dashboard', compact(
            'stats',
            'recentSellers',
            'recentProducts',
            'recentLeads',
            'recentPayments',
            'revenueData'
        ));
    }
}
