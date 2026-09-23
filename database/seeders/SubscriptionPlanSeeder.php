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
                'name' => 'SINGLE LISTING',
                'slug' => 'single',
                'description' => 'List 1 surplus product. Pay-as-you-go.',
                'price' => 12,
                'billing_cycle' => 'one_time',
                'max_products' => 1,
                'featured_placement' => false,
                'priority_support' => false,
                'advanced_analytics' => false,
                'premium_profile' => false,
                'promotional_placement' => false,
                'featured_product_discount' => 0,
                'features' => ['1 Active Product Listing', '₹12 per product', 'WhatsApp & Call Leads', 'Zero Commission on Sales'],
                'sort_order' => 1,
                'is_active' => true,
                'is_popular' => false,
            ],
            [
                'name' => 'STARTER LOT (5 Items)',
                'slug' => 'starter-5',
                'description' => 'List up to 5 surplus products @ ₹12/item.',
                'price' => 60,
                'billing_cycle' => 'one_time',
                'max_products' => 5,
                'featured_placement' => false,
                'priority_support' => false,
                'advanced_analytics' => false,
                'premium_profile' => false,
                'promotional_placement' => false,
                'featured_product_discount' => 0,
                'features' => ['5 Active Product Listings', '₹12 per product (₹60 total)', 'Direct WhatsApp & Call Leads', 'In-Store Shopper Footfall', 'Zero Commission'],
                'sort_order' => 2,
                'is_active' => true,
                'is_popular' => true,
            ],
            [
                'name' => 'STANDARD LOT (10 Items)',
                'slug' => 'standard-10',
                'description' => 'List up to 10 surplus products @ ₹12/item.',
                'price' => 120,
                'billing_cycle' => 'one_time',
                'max_products' => 10,
                'featured_placement' => false,
                'priority_support' => false,
                'advanced_analytics' => true,
                'premium_profile' => false,
                'promotional_placement' => false,
                'featured_product_discount' => 0,
                'features' => ['10 Active Product Listings', '₹12 per product (₹120 total)', 'Performance Analytics', 'WhatsApp Lead Alerts', 'Zero Commission'],
                'sort_order' => 3,
                'is_active' => true,
                'is_popular' => false,
            ],
            [
                'name' => 'BULK CLEARANCE (25 Items)',
                'slug' => 'bulk-25',
                'description' => 'For clearing seasonal batches & dead stock.',
                'price' => 300,
                'billing_cycle' => 'one_time',
                'max_products' => 25,
                'featured_placement' => true,
                'priority_support' => false,
                'advanced_analytics' => true,
                'premium_profile' => true,
                'promotional_placement' => false,
                'featured_product_discount' => 10,
                'features' => ['25 Active Product Listings', '₹12 per product (₹300 total)', 'Featured Store Placement', '10% Off Featured Ads', 'Zero Commission'],
                'sort_order' => 4,
                'is_active' => true,
                'is_popular' => false,
            ],
            [
                'name' => 'UNLIMITED PRO PASS',
                'slug' => 'unlimited-pro',
                'description' => 'For high-volume wholesalers & multi-store retailers.',
                'price' => 599,
                'billing_cycle' => 'monthly',
                'max_products' => -1, // unlimited
                'featured_placement' => true,
                'priority_support' => true,
                'advanced_analytics' => true,
                'premium_profile' => true,
                'promotional_placement' => true,
                'featured_product_discount' => 25,
                'features' => ['Unlimited Product Listings', 'Promotional Homepage Placement', '25% Off Featured Listings', 'Dedicated Priority Support', 'Advanced Visitor Analytics'],
                'sort_order' => 5,
                'is_active' => true,
                'is_popular' => false,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
