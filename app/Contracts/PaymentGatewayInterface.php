<?php

namespace App\Contracts;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

interface PaymentGatewayInterface
{
    /**
     * Create/Initialize a payment order or transaction.
     *
     * @param User $user The user initiating payment
     * @param float $amount The amount to charge
     * @param array $options Additional options (type, product_id, shop_id, notes, etc.)
     * @return array Contains transaction details or redirect URL/payload
     */
    public function createPayment(User $user, float $amount, array $options = []): array;

    /**
     * Verify payment status from request or webhook.
     *
     * @param Request $request
     * @return bool
     */
    public function verifyPayment(Request $request): bool;

    /**
     * Handle callback/completion and persist the Payment record.
     *
     * @param Request $request
     * @return Payment
     */
    public function handleCallback(Request $request): Payment;
}
