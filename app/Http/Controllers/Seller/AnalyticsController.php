<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\JsonResponse;

class AnalyticsController extends Controller
{
    public function __construct(private AnalyticsService $analytics) {}

    public function index()
    {
        $shop = auth()->user()->shop;
        $stats = $this->analytics->getSellerStats($shop->id);
        return view('seller.analytics', compact('stats', 'shop'));
    }

    public function data(): JsonResponse
    {
        $shop = auth()->user()->shop;
        $stats = $this->analytics->getSellerStats($shop->id);
        return response()->json($stats);
    }
}
