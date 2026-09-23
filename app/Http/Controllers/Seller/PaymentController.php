<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Setting;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}

    /**
     * Show the ₹12 Product Listing Payment Checkout page.
     */
    public function showListingPayment(Request $request)
    {
        $user = auth()->user();

        // If seller already has an unused payment, redirect directly to create form
        if ($this->paymentService->hasUnusedListingPayment($user)) {
            return redirect()->route('seller.products.create')
                ->with('info', 'You already have an active ₹12 listing payment authorization. Please create your product.');
        }

        $listingPrice = (float) Setting::get('product_listing_price', 12);
        $shop = $user->shop;

        return view('seller.products.payment', compact('listingPrice', 'shop', 'user'));
    }

    /**
     * Process listing payment (Dummy Gateway / Razorpay ready).
     */
    public function processListingPayment(Request $request)
    {
        $user = auth()->user();
        $listingPrice = (float) Setting::get('product_listing_price', 12);
        $gatewayName = $request->input('gateway', 'dummy');

        // Process payment via service & active gateway
        $payment = $this->paymentService->processListingPayment($request, $gatewayName);

        return redirect()->route('seller.products.create')
            ->with('success', "₹{$listingPrice} payment successful. You can now create your product.");
    }

    /**
     * Show seller's payment transaction history.
     */
    public function history(Request $request)
    {
        $user = auth()->user();
        $payments = Payment::where('user_id', $user->id)
            ->with(['product'])
            ->latest()
            ->paginate(15);

        $stats = [
            'total_spent' => Payment::where('user_id', $user->id)->completed()->sum('amount'),
            'total_listings' => Payment::where('user_id', $user->id)->listing()->completed()->count(),
            'unused_credits' => Payment::where('user_id', $user->id)->unusedListing()->count(),
        ];

        return view('seller.payments.index', compact('payments', 'stats', 'user'));
    }
}
