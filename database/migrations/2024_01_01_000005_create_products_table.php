<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('brand')->nullable();
            $table->string('sku')->nullable();
            $table->enum('condition', ['new', 'like_new', 'good', 'fair'])->default('new');
            $table->decimal('original_price', 10, 2);
            $table->decimal('offer_price', 10, 2);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->integer('quantity')->default(1);
            $table->string('unit')->default('piece');
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'expired', 'sold_out', 'inactive'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamp('featured_until')->nullable();
            $table->boolean('is_negotiable')->default(false);
            $table->timestamp('expires_at')->nullable();
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('calls_count')->default(0);
            $table->unsignedInteger('whatsapp_count')->default(0);
            $table->unsignedInteger('directions_count')->default(0);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();

            $table->index(['status', 'is_featured']);
            $table->index(['shop_id', 'status']);
            $table->index(['category_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
