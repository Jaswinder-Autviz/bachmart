<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'BachatMart', 'type' => 'string', 'group' => 'general', 'label' => 'Site Name', 'is_public' => true],
            ['key' => 'site_tagline', 'value' => 'Great Deals From Local Shops', 'type' => 'string', 'group' => 'general', 'label' => 'Tagline', 'is_public' => true],
            ['key' => 'site_phone', 'value' => '+91 9876543210', 'type' => 'string', 'group' => 'general', 'label' => 'Phone', 'is_public' => true],
            ['key' => 'site_email', 'value' => 'hello@bachatmart.com', 'type' => 'string', 'group' => 'general', 'label' => 'Email', 'is_public' => true],
            ['key' => 'currency', 'value' => 'INR', 'type' => 'string', 'group' => 'general', 'label' => 'Currency', 'is_public' => true],
            ['key' => 'currency_symbol', 'value' => '₹', 'type' => 'string', 'group' => 'general', 'label' => 'Currency Symbol', 'is_public' => true],
            ['key' => 'contact_address', 'value' => '123 Market Street, Bazaar Road', 'type' => 'string', 'group' => 'general', 'label' => 'Address', 'is_public' => true],
            ['key' => 'contact_city', 'value' => 'Mumbai, Maharashtra 400001', 'type' => 'string', 'group' => 'general', 'label' => 'City', 'is_public' => true],
            // Pricing & Listing
            ['key' => 'product_listing_price', 'value' => '12', 'type' => 'integer', 'group' => 'pricing', 'label' => 'Product Listing Price (₹)', 'is_public' => true],
            // Featured prices
            ['key' => 'featured_price_3days', 'value' => '49', 'type' => 'integer', 'group' => 'featured', 'label' => 'Featured 3 Days Price (₹)', 'is_public' => true],
            ['key' => 'featured_price_7days', 'value' => '99', 'type' => 'integer', 'group' => 'featured', 'label' => 'Featured 7 Days Price (₹)', 'is_public' => true],
            ['key' => 'featured_price_15days', 'value' => '199', 'type' => 'integer', 'group' => 'featured', 'label' => 'Featured 15 Days Price (₹)', 'is_public' => true],
            // Social
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/bachatmart', 'type' => 'string', 'group' => 'social', 'label' => 'Facebook URL', 'is_public' => true],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/bachatmart', 'type' => 'string', 'group' => 'social', 'label' => 'Instagram URL', 'is_public' => true],
            ['key' => 'twitter_url', 'value' => 'https://twitter.com/bachatmart', 'type' => 'string', 'group' => 'social', 'label' => 'Twitter URL', 'is_public' => true],
            // SEO
            ['key' => 'meta_title', 'value' => 'BachatMart - Local Deals & Discounts Near You', 'type' => 'string', 'group' => 'seo', 'label' => 'Meta Title', 'is_public' => true],
            ['key' => 'meta_description', 'value' => 'Discover amazing deals and discounted products from local shops near you. Save big on fashion, electronics, furniture, and more!', 'type' => 'string', 'group' => 'seo', 'label' => 'Meta Description', 'is_public' => true],
            ['key' => 'meta_keywords', 'value' => 'local deals, discounts, clearance sale, local shops, nearby deals, offers', 'type' => 'string', 'group' => 'seo', 'label' => 'Meta Keywords', 'is_public' => true],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
