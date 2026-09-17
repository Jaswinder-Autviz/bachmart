<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->enum('billing_cycle', ['monthly', 'yearly'])->default('monthly');
            $table->integer('max_products')->default(5); // -1 = unlimited
            $table->boolean('featured_placement')->default(false);
            $table->boolean('priority_support')->default(false);
            $table->boolean('advanced_analytics')->default(false);
            $table->boolean('premium_profile')->default(false);
            $table->boolean('promotional_placement')->default(false);
            $table->integer('featured_product_discount')->default(0); // % discount on featured listings
            $table->json('features')->nullable(); // JSON array of feature strings
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_popular')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
