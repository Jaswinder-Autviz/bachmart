<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RazorpayPaymentGateway implements PaymentGatewayInterface
{
    protected string $keyId;
    protected string $keySecret;

    public function __construct()
    {
        $this->keyId = (string) (config('services.razorpay.key') ?? \App\Models\Setting::get('razorpay_key_id', ''));
        $this->keySecret = (string) (config('services.razorpay.secret') ?? \App\Models\Setting::get('razorpay_key_secret', ''));
    }

    /**
     * Create Razorpay Order.
     */
    public function createPayment(User $user, float $amount, array $options = []): array
    {
        // When Razorpay SDK is installed in production:
        // $api = new \Razorpay\Api\Api($this->keyId, $this->keySecret);
        // $order = $api->order->create([...]);
        
        $orderId = 'order_' . Str::random(14);
        $transactionId = 'BM-RZP-' . strtoupper(Str::random(10));

        return [
            'status' => 'initiated',
            'gateway' => 'razorpay',
            'key_id' => $this->keyId,
            'order_id' => $orderId,
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'amount_in_paise' => (int) round($amount * 100),
            'currency' => 'INR',
            'type' => $options['type'] ?? 'product_listing',
            'shop_id' => $options['shop_id'] ?? $user->shop?->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_phone' => $user->phone ?? '',
        ];
    }

    /**
     * Verify Razorpay payment signature.
     */
    public function verifyPayment(Request $request): bool
    {
        $razorpayPaymentId = $request->input('razorpay_payment_id');
        $razorpayOrderId = $request->input('razorpay_order_id');
        $razorpaySignature = $request->input('razorpay_signature');

        if (!$razorpayPaymentId || !$razorpayOrderId || !$razorpaySignature) {
            return false;
        }

        $expectedSignature = hash_hmac('sha256', $razorpayOrderId . '|' . $razorpayPaymentId, $this->keySecret);
        return hash_equals($expectedSignature, $razorpaySignature);
    }

    /**
     * Handle Razorpay completion.
     */
    public function handleCallback(Request $request): Payment
    {
        $user = auth()->user();
        $amount = (float) ($request->input('amount') ?? \App\Models\Setting::get('product_listing_price', 12));
        $type = $request->input('type', 'product_listing');
        $transactionId = $request->input('transaction_id') ?: ('BM-RZP-' . strtoupper(Str::random(10)));
        $gatewayPaymentId = $request->input('razorpay_payment_id');

        return Payment::create([
            'user_id' => $user->id,
            'shop_id' => $user->shop?->id,
            'type' => $type,
            'product_id' => null,
            'payable_type' => null,
            'payable_id' => null,
            'amount' => $amount,
            'currency' => 'INR',
            'gateway' => 'razorpay',
            'transaction_id' => $transactionId,
            'gateway_order_id' => $request->input('razorpay_order_id'),
            'gateway_payment_id' => $gatewayPaymentId,
            'status' => 'completed',
            'paid_at' => now(),
            'notes' => 'Product listing payment via Razorpay',
            'gateway_response' => $request->all(),
        ]);
    }
}
