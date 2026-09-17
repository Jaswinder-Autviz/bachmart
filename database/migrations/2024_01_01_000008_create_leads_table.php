<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('type', ['call', 'whatsapp', 'direction', 'view']);
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('city')->nullable();
            $table->string('source')->nullable(); // page they came from
            $table->timestamps();

            $table->index(['shop_id', 'type']);
            $table->index(['product_id', 'type']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
