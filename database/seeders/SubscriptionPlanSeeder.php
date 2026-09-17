<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'FREE',
                'slug' => 'free',
                'description' => 'Get started with basic listing features.',
                'price' => 0,
                'billing_cycle' => 'monthly',
                'max_products' => 5,
                'featured_placement' => false,
                'priority_support' => false,
                'advanced_analytics' => false,
                'premium_profile' => false,
                'promotional_placement' => false,
                'featured_product_discount' => 0,
                'features' => ['5 active products', 'Basic profile', 'Customer leads'],
                'sort_order' => 1,
                'is_active' => true,
                'is_popular' => false,
            ],
            [
                'name' => 'BASIC',
                'slug' => 'basic',
                'description' => 'For growing shops that need more visibility.',
                'price' => 299,
                'billing_cycle' => 'monthly',
                'max_products' => 50,
                'featured_placement' => false,
                'priority_support' => false,
                'advanced_analytics' => true,
                'premium_profile' => false,
                'promotional_placement' => false,
                'featured_product_discount' => 0,
                'features' => ['50 active products', 'Basic analytics', 'Customer leads', 'Email support'],
                'sort_order' => 2,
                'is_active' => true,
                'is_popular' => false,
            ],
            [
                'name' => 'PRO',
                'slug' => 'pro',
                'description' => 'For established shops that want maximum reach.',
                'price' => 599,
                'billing_cycle' => 'monthly',
                'max_products' => -1, // unlimited
                'featured_placement' => true,
                'priority_support' => true,
                'advanced_analytics' => true,
                'premium_profile' => false,
                'promotional_placement' => false,
                'featured_product_discount' => 10,
                'features' => ['Unlimited products', 'Advanced analytics', 'Featured placement', '10% off featured listings', 'Priority support'],
                'sort_order' => 3,
                'is_active' => true,
                'is_popular' => true,
            ],
            [
                'name' => 'BUSINESS',
                'slug' => 'business',
                'description' => 'Premium package for high-volume sellers.',
                'price' => 999,
                'billing_cycle' => 'monthly',
                'max_products' => -1,
                'featured_placement' => true,
                'priority_support' => true,
                'advanced_analytics' => true,
                'premium_profile' => true,
                'promotional_placement' => true,
                'featured_product_discount' => 25,
                'features' => ['Unlimited products', 'Premium shop profile', 'Promotional placement', '25% off featured listings', 'Dedicated support', 'Advanced analytics'],
                'sort_order' => 4,
                'is_active' => true,
                'is_popular' => false,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
