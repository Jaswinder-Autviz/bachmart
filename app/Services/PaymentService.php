<?php

namespace App\Services;

use App\Models\FeaturedProduct;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * PaymentService
 *
 * Abstraction layer for payment processing.
 * Currently implements manual/offline payment.
 * Replace processPayment() internals to integrate Razorpay, Paytm, etc.
 */
class PaymentService
{
    /**
     * Create a payment record for a subscription.
     */
    public function createSubscriptionPayment(User $user, Subscription $subscription, float $amount): Payment
    {
        return Payment::create([
            'user_id' => $user->id,
            'payable_type' => Subscription::class,
            'payable_id' => $subscription->id,
            'transaction_id' => $this->generateTransactionId(),
            'gateway' => 'manual',
            'amount' => $amount,
            'currency' => 'INR',
            'status' => 'pending',
        ]);
    }

    /**
     * Create a payment record for a featured product.
     */
    public function createFeaturedPayment(User $user, FeaturedProduct $featured, float $amount): Payment
    {
        return Payment::create([
            'user_id' => $user->id,
            'payable_type' => FeaturedProduct::class,
            'payable_id' => $featured->id,
            'transaction_id' => $this->generateTransactionId(),
            'gateway' => 'manual',
            'amount' => $amount,
            'currency' => 'INR',
            'status' => 'pending',
        ]);
    }

    /**
     * Mark a payment as completed (called by webhook or manual confirmation).
     */
    public function markCompleted(Payment $payment, ?string $gatewayPaymentId = null): Payment
    {
        $payment->update([
            'status' => 'completed',
            'gateway_payment_id' => $gatewayPaymentId,
            'paid_at' => now(),
        ]);

        return $payment;
    }

    /**
     * Mark a payment as failed.
     */
    public function markFailed(Payment $payment): Payment
    {
        $payment->update(['status' => 'failed']);
        return $payment;
    }

    /**
     * Process a manual (admin-approved) payment.
     * Activates the subscription or featured product.
     */
    public function processManualApproval(Payment $payment): bool
    {
        $this->markCompleted($payment);
        $payable = $payment->payable;

        if ($payable instanceof Subscription) {
            $payable->update([
                'status' => 'active',
                'starts_at' => now(),
                'expires_at' => now()->addMonth(),
            ]);
            return true;
        }

        if ($payable instanceof FeaturedProduct) {
            $days = FeaturedProduct::getDaysFromPackage($payable->package);
            $payable->update([
                'status' => 'active',
                'starts_at' => now(),
                'expires_at' => now()->addDays($days),
            ]);

            // Update the product itself
            $payable->product->update([
                'is_featured' => true,
                'featured_until' => now()->addDays($days),
            ]);
            return true;
        }

        return false;
    }

    public function generateTransactionId(): string
    {
        return 'BM-' . strtoupper(Str::random(12));
    }

    /**
     * Get featured package prices from settings.
     */
    public static function getFeaturedPackages(): array
    {
        return [
            '3days' => [
                'label' => '3 Days',
                'price' => (float) \App\Models\Setting::get('featured_price_3days', 49),
                'days' => 3,
                'popular' => false,
            ],
            '7days' => [
                'label' => '7 Days',
                'price' => (float) \App\Models\Setting::get('featured_price_7days', 99),
                'days' => 7,
                'popular' => true,
            ],
            '15days' => [
                'label' => '15 Days',
                'price' => (float) \App\Models\Setting::get('featured_price_15days', 199),
                'days' => 15,
                'popular' => false,
            ],
        ];
    }
}
