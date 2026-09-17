<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}

    public function index(Request $request)
    {
        $query = Subscription::with(['user', 'subscriptionPlan', 'payment'])->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$request->search}%")
                                                   ->orWhere('email', 'like', "%{$request->search}%"));
        }

        $subscriptions = $query->paginate(20)->withQueryString();

        $plans = SubscriptionPlan::active()->get();

        $stats = [
            'active' => Subscription::where('status', 'active')->count(),
            'revenue' => Subscription::whereHas('payment', fn($q) => $q->where('status', 'completed'))->sum('amount_paid'),
        ];

        return view('admin.subscriptions.index', compact('subscriptions', 'plans', 'stats'));
    }

    public function create()
    {
        return view('admin.subscriptions.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'price' => ['required', 'numeric', 'min:0'],
            'max_products' => ['required', 'integer', 'min:-1'],
            'billing_cycle' => ['required', 'in:monthly,yearly'],
            'featured_placement' => ['sometimes', 'boolean'],
            'priority_support' => ['sometimes', 'boolean'],
            'advanced_analytics' => ['sometimes', 'boolean'],
            'premium_profile' => ['sometimes', 'boolean'],
            'promotional_placement' => ['sometimes', 'boolean'],
            'featured_product_discount' => ['nullable', 'integer', 'min:0', 'max:100'],
            'features' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
            'is_popular' => ['sometimes', 'boolean'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['features'] = $validated['features']
            ? array_filter(array_map('trim', explode("\n", $validated['features'])))
            : [];

        foreach (['featured_placement','priority_support','advanced_analytics','premium_profile','promotional_placement','is_active','is_popular'] as $bool) {
            $validated[$bool] = $request->boolean($bool);
        }

        SubscriptionPlan::create($validated);

        return redirect()->route('admin.subscription-plans.index')->with('success', 'Plan created.');
    }

    public function edit(SubscriptionPlan $subscriptionPlan)
    {
        return view('admin.subscriptions.plans.edit', compact('subscriptionPlan'));
    }

    public function update(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'price' => ['required', 'numeric', 'min:0'],
            'max_products' => ['required', 'integer', 'min:-1'],
            'billing_cycle' => ['required', 'in:monthly,yearly'],
            'features' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $validated['features'] = $validated['features']
            ? array_filter(array_map('trim', explode("\n", $validated['features'])))
            : [];

        foreach (['featured_placement','priority_support','advanced_analytics','premium_profile','promotional_placement','is_active','is_popular'] as $bool) {
            $validated[$bool] = $request->boolean($bool);
        }

        $subscriptionPlan->update($validated);

        return redirect()->route('admin.subscriptions.index')->with('success', 'Plan updated.');
    }

    public function destroy(SubscriptionPlan $subscriptionPlan)
    {
        if ($subscriptionPlan->subscriptions()->where('status', 'active')->exists()) {
            return back()->with('error', 'Cannot delete plan with active subscriptions.');
        }
        $subscriptionPlan->delete();
        return redirect()->route('admin.subscriptions.index')->with('success', 'Plan deleted.');
    }

    public function activate(Subscription $subscription)
    {
        $payment = $subscription->payment;
        if ($payment) {
            $this->paymentService->processManualApproval($payment);
        } else {
            $subscription->update([
                'status' => 'active',
                'starts_at' => now(),
                'expires_at' => now()->addMonth(),
            ]);
        }
        return back()->with('success', 'Subscription activated.');
    }
}
