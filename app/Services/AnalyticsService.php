<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function getSellerStats(int $shopId): array
    {
        $shop = Shop::findOrFail($shopId);

        $leads = Lead::forShop($shopId);

        $totalLeads = (clone $leads)->count();
        $callLeads = (clone $leads)->byType('call')->count();
        $whatsappLeads = (clone $leads)->byType('whatsapp')->count();
        $directionLeads = (clone $leads)->byType('direction')->count();
        $viewLeads = (clone $leads)->byType('view')->count();

        $monthlyLeads = $this->getMonthlyLeads($shopId);
        $weeklyLeads = $this->getWeeklyLeads($shopId);

        $topProducts = Product::where('shop_id', $shopId)
            ->where('status', 'approved')
            ->withCount([
                'leads as calls_count' => fn($q) => $q->byType('call'),
                'leads as whatsapp_count' => fn($q) => $q->byType('whatsapp'),
                'leads as directions_count' => fn($q) => $q->byType('direction'),
            ])
            ->orderByDesc('views_count')
            ->take(5)
            ->with(['primaryImage', 'category'])
            ->get();

        $mostViewedProduct = Product::where('shop_id', $shopId)->where('status', 'approved')->orderByDesc('views_count')->first();
        $mostContactedProduct = Product::where('shop_id', $shopId)
            ->where('status', 'approved')
            ->withCount('leads')
            ->orderByDesc('leads_count')
            ->first();

        return [
            'total_leads' => $totalLeads,
            'call_leads' => $callLeads,
            'whatsapp_leads' => $whatsappLeads,
            'direction_leads' => $directionLeads,
            'view_leads' => $viewLeads,
            'monthly_leads' => $monthlyLeads,
            'weekly_leads' => $weeklyLeads,
            'top_products' => $topProducts,
            'most_viewed_product' => $mostViewedProduct,
            'most_contacted_product' => $mostContactedProduct,
            'shop_views' => $shop->views_count,
        ];
    }

    public function getMonthlyLeads(int $shopId): array
    {
        $data = Lead::forShop($shopId)
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, type, COUNT(*) as count")
            ->groupBy('month', 'type')
            ->orderBy('month')
            ->get();

        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $months[] = now()->subMonths($i)->format('Y-m');
        }

        $result = [];
        foreach ($months as $month) {
            $result[$month] = [
                'month' => now()->createFromFormat('Y-m', $month)->format('M Y'),
                'call' => 0,
                'whatsapp' => 0,
                'direction' => 0,
                'view' => 0,
            ];
        }

        foreach ($data as $row) {
            if (isset($result[$row->month])) {
                $result[$row->month][$row->type] = $row->count;
            }
        }

        return array_values($result);
    }

    public function getWeeklyLeads(int $shopId): array
    {
        $data = Lead::forShop($shopId)
            ->where('created_at', '>=', now()->subDays(28))
            ->selectRaw("DATE(created_at) as date, type, COUNT(*) as count")
            ->groupBy('date', 'type')
            ->orderBy('date')
            ->get();

        $days = [];
        for ($i = 27; $i >= 0; $i--) {
            $days[] = now()->subDays($i)->format('Y-m-d');
        }

        $result = [];
        foreach ($days as $day) {
            $result[$day] = [
                'date' => $day,
                'label' => date('d M', strtotime($day)),
                'total' => 0,
            ];
        }

        foreach ($data as $row) {
            if (isset($result[$row->date])) {
                $result[$row->date]['total'] += $row->count;
            }
        }

        return array_values($result);
    }

    public function getAdminStats(): array
    {
        return [
            'total_sellers' => \App\Models\User::where('role', 'seller')->count(),
            'total_customers' => \App\Models\User::where('role', 'customer')->count(),
            'total_shops' => Shop::count(),
            'active_shops' => Shop::where('status', 'active')->count(),
            'total_products' => Product::count(),
            'pending_products' => Product::where('status', 'pending')->count(),
            'approved_products' => Product::where('status', 'approved')->count(),
            'featured_products' => Product::where('is_featured', true)->count(),
            'total_leads' => Lead::count(),
            'leads_today' => Lead::today()->count(),
            'monthly_revenue' => \App\Models\Payment::completed()->thisMonth()->sum('amount'),
        ];
    }
}
