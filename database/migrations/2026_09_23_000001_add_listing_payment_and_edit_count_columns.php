<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add edit_count to products table
        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'edit_count')) {
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedSmallInteger('edit_count')->default(0)->after('status');
            });
        }

        // 2. Add listing payment columns to payments table
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                if (!Schema::hasColumn('payments', 'shop_id')) {
                    $table->foreignId('shop_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
                }
                if (!Schema::hasColumn('payments', 'type')) {
                    $table->string('type')->default('product_listing')->after('shop_id');
                }
                if (!Schema::hasColumn('payments', 'product_id')) {
                    $table->foreignId('product_id')->nullable()->after('type')->constrained()->nullOnDelete();
                }
                if (!Schema::hasColumn('payments', 'used_at')) {
                    $table->timestamp('used_at')->nullable()->after('paid_at');
                }
                if (!Schema::hasColumn('payments', 'metadata')) {
                    $table->json('metadata')->nullable()->after('used_at');
                }
                $table->string('payable_type')->nullable()->change();
                $table->unsignedBigInteger('payable_id')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'edit_count')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('edit_count');
            });
        }

        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                if (Schema::hasColumn('payments', 'product_id')) {
                    $table->dropForeign(['product_id']);
                    $table->dropColumn('product_id');
                }
                if (Schema::hasColumn('payments', 'shop_id')) {
                    $table->dropForeign(['shop_id']);
                    $table->dropColumn('shop_id');
                }
                if (Schema::hasColumn('payments', 'type')) {
                    $table->dropColumn('type');
                }
                if (Schema::hasColumn('payments', 'used_at')) {
                    $table->dropColumn('used_at');
                }
                if (Schema::hasColumn('payments', 'metadata')) {
                    $table->dropColumn('metadata');
                }
            });
        }
    }
};
