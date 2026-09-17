<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('payable_type'); // App\Models\Subscription or FeaturedProduct
            $table->unsignedBigInteger('payable_id');
            $table->string('transaction_id')->nullable()->unique();
            $table->string('gateway')->default('manual'); // razorpay, paytm, manual
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('INR');
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->string('gateway_order_id')->nullable();
            $table->string('gateway_payment_id')->nullable();
            $table->text('notes')->nullable();
            $table->json('gateway_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['payable_type', 'payable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
