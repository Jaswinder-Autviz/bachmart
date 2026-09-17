<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\Review;
use App\Models\Shop;
use App\Policies\ProductPolicy;
use App\Policies\ReviewPolicy;
use App\Policies\ShopPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Product::class => ProductPolicy::class,
        Shop::class => ShopPolicy::class,
        Review::class => ReviewPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
