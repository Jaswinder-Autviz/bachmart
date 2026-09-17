<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    public function run(): void
    {
        $shops = Shop::all();
        $products = Product::where('status', 'approved')->get();
        $customers = User::where('role', 'customer')->get();
        $types = ['call', 'whatsapp', 'direction', 'view'];

        foreach ($shops as $shop) {
            $shopProducts = $products->where('shop_id', $shop->id);

            for ($i = 0; $i < rand(20, 80); $i++) {
                $product = $shopProducts->count() > 0 && rand(0, 1)
                    ? $shopProducts->random()
                    : null;

                Lead::create([
                    'shop_id' => $shop->id,
                    'product_id' => $product?->id,
                    'customer_id' => $customers->count() > 0 && rand(0, 1) ? $customers->random()->id : null,
                    'type' => $types[array_rand($types)],
                    'ip_address' => '192.168.1.' . rand(1, 254),
                    'user_agent' => 'Mozilla/5.0 (compatible; BachatMartBot/1.0)',
                    'created_at' => now()->subDays(rand(0, 90)),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
