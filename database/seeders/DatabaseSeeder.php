<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SettingsSeeder::class,
            CategorySeeder::class,
            SubscriptionPlanSeeder::class,
            UserSeeder::class,
            ShopSeeder::class,
            ProductSeeder::class,
            LeadSeeder::class,
        ]);
    }
}
