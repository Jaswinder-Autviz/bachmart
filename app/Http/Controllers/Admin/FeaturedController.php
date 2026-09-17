<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeaturedProduct;
use App\Services\PaymentService;

class FeaturedController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}

    public function index()
    {
        $featured = FeaturedProduct::with(['product.shop', 'user', 'payment'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total' => FeaturedProduct::count(),
            'active' => FeaturedProduct::where('status', 'active')->count(),
            'pending' => FeaturedProduct::where('status', 'pending')->count(),
            'revenue' => FeaturedProduct::whereHas('payment', fn($q) => $q->where('status', 'completed'))->sum('amount_paid'),
        ];

        return view('admin.featured.index', compact('featured', 'stats'));
    }

    public function approve(FeaturedProduct $featured)
    {
        $payment = $featured->payment;
        if ($payment) {
            $this->paymentService->processManualApproval($payment);
        } else {
            $days = FeaturedProduct::getDaysFromPackage($featured->package);
            $featured->update([
                'status' => 'active',
                'starts_at' => now(),
                'expires_at' => now()->addDays($days),
            ]);
            $featured->product->update([
                'is_featured' => true,
                'featured_until' => now()->addDays($days),
            ]);
        }
        return back()->with('success', 'Featured listing approved.');
    }

    public function cancel(FeaturedProduct $featured)
    {
        $featured->update(['status' => 'cancelled']);
        $featured->product->update(['is_featured' => false, 'featured_until' => null]);
        return back()->with('success', 'Featured listing cancelled.');
    }
}
