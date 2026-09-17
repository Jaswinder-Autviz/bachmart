<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->tinyInteger('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->text('admin_note')->nullable();
            $table->timestamps();

            $table->unique(['shop_id', 'user_id']); // one review per shop per user
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
