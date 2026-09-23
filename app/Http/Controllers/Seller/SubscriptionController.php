<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Notifications\SubscriptionActivated;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}

    public function index()
    {
        $user = auth()->user();
        $shop = $user->shop;
        $currentSubscription = $user->activeSubscription;
        $history = $user->subscriptions()->with('subscriptionPlan')->latest()->take(10)->get();

        $totalListedProducts = Product::where('shop_id', $shop->id)->whereIn('status', ['approved', 'pending'])->count();
        $maxAllowed = $user->max_products;
        $remainingCredits = $maxAllowed == -1 ? 'Unlimited' : max(0, $maxAllowed - $totalListedProducts);

        return view('seller.subscription', compact('currentSubscription', 'history', 'user', 'totalListedProducts', 'maxAllowed', 'remainingCredits'));
    }

    public function buyCredits(Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:500',
        ]);

        $user = auth()->user();
        $quantity = (int) $request->input('quantity', 1);
        $rate = 12; // Flat ₹12 per surplus product
        $totalAmount = $quantity * $rate;

        // Current max products
        $activeSub = $user->activeSubscription;
        $currentMax = $activeSub ? ($activeSub->subscriptionPlan->max_products == -1 ? -1 : $activeSub->subscriptionPlan->max_products) : 0;
        $newMax = $currentMax == -1 ? -1 : ($currentMax + $quantity);

        // Cancel previous subscription
        $user->subscriptions()->where('status', 'active')->update(['status' => 'cancelled']);

        // Create customized plan record for this transaction
        $plan = SubscriptionPlan::create([
            'name' => "{$quantity} Products Listing",
            'slug' => 'listing-' . time() . '-' . $quantity,
            'description' => "{$quantity} Surplus Product Listing Credits @ ₹12/each.",
            'price' => $totalAmount,
            'billing_cycle' => 'one_time',
            'max_products' => $newMax > 0 ? $newMax : $quantity,
            'is_active' => true,
        ]);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'amount_paid' => $totalAmount,
            'status' => 'active',
            'starts_at' => now(),
            'expires_at' => now()->addYears(10), // never expires
        ]);

        $payment = $this->paymentService->createSubscriptionPayment($user, $subscription, $totalAmount);
        $this->paymentService->processManualApproval($payment);
        $subscription->refresh();

        $user->notify(new SubscriptionActivated($subscription));

        return back()->with('success', "Payment of ₹{$totalAmount} received! Successfully added {$quantity} product listing credits (@ ₹12/product). You can now list your surplus items.");
    }

    public function subscribe(SubscriptionPlan $plan)
    {
        $user = auth()->user();

        if ($plan->isFree()) {
            return back()->with('info', 'You are on the free plan.');
        }

        // Cancel current active subscription
        $user->subscriptions()->where('status', 'active')->update(['status' => 'cancelled']);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'amount_paid' => $plan->price,
            'status' => 'pending',
        ]);

        $payment = $this->paymentService->createSubscriptionPayment($user, $subscription, $plan->price);

        // MVP: auto-approve
        $this->paymentService->processManualApproval($payment);
        $subscription->refresh();

        $user->notify(new SubscriptionActivated($subscription));

        return back()->with('success', "You are now subscribed to {$plan->name}!");
    }
}

