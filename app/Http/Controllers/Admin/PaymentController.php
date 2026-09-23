<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}

    public function index(Request $request)
    {
        $query = Payment::with(['user', 'payable'])->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('transaction_id', 'like', "%{$request->search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$request->search}%")
                                                     ->orWhere('email', 'like', "%{$request->search}%"));
            });
        }

        $payments = $query->paginate(25)->withQueryString();

        $stats = [
            'total_revenue' => Payment::completed()->sum('amount'),
            'monthly_revenue' => Payment::completed()->thisMonth()->sum('amount'),
            'pending_count' => Payment::where('status', 'pending')->count(),
            'listing_revenue' => Payment::completed()
                ->where('type', 'product_listing')
                ->sum('amount'),
            'featured_revenue' => Payment::completed()
                ->where('payable_type', \App\Models\FeaturedProduct::class)
                ->orWhere('type', 'featured')
                ->sum('amount'),
        ];

        return view('admin.payments.index', compact('payments', 'stats'));
    }

    public function approve(Payment $payment)
    {
        $this->paymentService->processManualApproval($payment);
        return back()->with('success', 'Payment approved and subscription/feature activated.');
    }
}
