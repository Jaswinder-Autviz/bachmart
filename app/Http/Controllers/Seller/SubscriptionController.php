<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Notifications\SubscriptionActivated;
use App\Services\PaymentService;

class SubscriptionController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}

    public function index()
    {
        $user = auth()->user();
        $plans = SubscriptionPlan::active()->get();
        $currentSubscription = $user->activeSubscription;
        $history = $user->subscriptions()->with('subscriptionPlan')->latest()->take(10)->get();

        return view('seller.subscription', compact('plans', 'currentSubscription', 'history', 'user'));
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

        return back()->with('success', "You are now subscribed to the {$plan->name} plan!");
    }
}
