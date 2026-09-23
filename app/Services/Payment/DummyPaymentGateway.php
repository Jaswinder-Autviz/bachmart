<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DummyPaymentGateway implements PaymentGatewayInterface
{
    /**
     * Create a dummy payment transaction.
     */
    public function createPayment(User $user, float $amount, array $options = []): array
    {
        $transactionId = 'BM-DUMMY-' . strtoupper(Str::random(10));

        return [
            'status' => 'initiated',
            'gateway' => 'dummy',
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'currency' => $options['currency'] ?? 'INR',
            'type' => $options['type'] ?? 'product_listing',
            'shop_id' => $options['shop_id'] ?? $user->shop?->id,
            'user_id' => $user->id,
            'notes' => $options['notes'] ?? 'Product listing authorization payment',
        ];
    }

    /**
     * Verify payment status. In dummy gateway, validity check is based on user ownership.
     */
    public function verifyPayment(Request $request): bool
    {
        $user = auth()->user() ?? $request->user();
        return $user !== null;
    }

    /**
     * Process dummy payment callback and persist verified completed payment in DB.
     */
    public function handleCallback(Request $request): Payment
    {
        $user = auth()->user() ?? $request->user();
        if (!$user) {
            throw new \InvalidArgumentException("Cannot process payment without an authenticated user.");
        }
        $amount = (float) ($request->input('amount') ?? \App\Models\Setting::get('product_listing_price', 12));
        $type = $request->input('type', 'product_listing');
        $transactionId = $request->input('transaction_id') ?: ('BM-DUMMY-' . strtoupper(Str::random(10)));
        $gatewayPaymentId = 'DUMMY-PAY-' . strtoupper(Str::random(12));

        return Payment::create([
            'user_id' => $user->id,
            'shop_id' => $user->shop?->id,
            'type' => $type,
            'product_id' => null, // Will be linked and consumed when product is created
            'payable_type' => null,
            'payable_id' => null,
            'amount' => $amount,
            'currency' => 'INR',
            'gateway' => 'dummy',
            'transaction_id' => $transactionId,
            'gateway_payment_id' => $gatewayPaymentId,
            'status' => 'completed',
            'paid_at' => now(),
            'notes' => $request->input('notes', 'Product listing authorization payment (Dummy Gateway)'),
            'metadata' => [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'simulated_at' => now()->toIso8601String(),
            ],
        ]);
    }
}
