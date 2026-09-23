<?php

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;
use App\Models\FeaturedProduct;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use App\Services\Payment\DummyPaymentGateway;
use App\Services\Payment\RazorpayPaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentService
{
    /**
     * Resolve the active payment gateway.
     */
    public function getGateway(?string $name = null): PaymentGatewayInterface
    {
        $gatewayName = $name ?: (config('services.payment_gateway') ?? Setting::get('active_payment_gateway', 'dummy'));

        return match(strtolower($gatewayName)) {
            'razorpay' => new RazorpayPaymentGateway(),
            default => new DummyPaymentGateway(),
        };
    }

    /**
     * Check if seller has an authorized unused ₹12 listing payment.
     */
    public function hasUnusedListingPayment(User $user): bool
    {
        return Payment::where('user_id', $user->id)
            ->where('type', 'product_listing')
            ->where('status', 'completed')
            ->whereNull('product_id')
            ->whereNull('used_at')
            ->exists();
    }

    /**
     * Get the latest unused listing payment for the seller.
     */
    public function getUnusedListingPayment(User $user, bool $lockForUpdate = false): ?Payment
    {
        $query = Payment::where('user_id', $user->id)
            ->where('type', 'product_listing')
            ->where('status', 'completed')
            ->whereNull('product_id')
            ->whereNull('used_at')
            ->latest();

        if ($lockForUpdate) {
            $query->lockForUpdate();
        }

        return $query->first();
    }

    /**
     * Consume an unused listing payment and associate it with the created product.
     */
    public function consumeListingPayment(User $user, Product $product): ?Payment
    {
        $payment = $this->getUnusedListingPayment($user, true);

        if ($payment) {
            $payment->update([
                'product_id' => $product->id,
                'used_at' => now(),
                'notes' => "Listing fee consumed for product: {$product->name} (#{$product->id})",
            ]);
        }

        return $payment;
    }

    /**
     * Process listing payment using selected gateway.
     */
    public function processListingPayment(Request $request, ?string $gatewayName = null): Payment
    {
        $gateway = $this->getGateway($gatewayName);
        return $gateway->handleCallback($request);
    }

    /**
     * Create a payment record for a featured product.
     */
    public function createFeaturedPayment(User $user, FeaturedProduct $featured, float $amount): Payment
    {
        return Payment::create([
            'user_id' => $user->id,
            'shop_id' => $user->shop?->id,
            'type' => 'featured_product',
            'product_id' => $featured->product_id,
            'payable_type' => FeaturedProduct::class,
            'payable_id' => $featured->id,
            'transaction_id' => $this->generateTransactionId(),
            'gateway' => 'dummy',
            'amount' => $amount,
            'currency' => 'INR',
            'status' => 'pending',
        ]);
    }

    /**
     * Mark a payment as completed.
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
     */
    public function processManualApproval(Payment $payment): bool
    {
        $this->markCompleted($payment);

        if ($payment->type === 'featured_product' && $payment->product_id) {
            $product = Product::find($payment->product_id);
            if ($product) {
                $product->update([
                    'is_featured' => true,
                    'featured_until' => now()->addDays(30),
                ]);
            }
            return true;
        }

        return true;
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
                'price' => (float) Setting::get('featured_price_3days', 49),
                'days' => 3,
                'popular' => false,
            ],
            '7days' => [
                'label' => '7 Days',
                'price' => (float) Setting::get('featured_price_7days', 99),
                'days' => 7,
                'popular' => true,
            ],
            '15days' => [
                'label' => '15 Days',
                'price' => (float) Setting::get('featured_price_15days', 199),
                'days' => 15,
                'popular' => false,
            ],
        ];
    }
}
