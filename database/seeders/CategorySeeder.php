<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Fashion', 'icon' => '👗', 'sort_order' => 1, 'is_featured' => true],
            ['name' => 'Shoes', 'icon' => '👟', 'sort_order' => 2, 'is_featured' => true],
            ['name' => 'Electronics', 'icon' => '📱', 'sort_order' => 3, 'is_featured' => true],
            ['name' => 'Home & Decor', 'icon' => '🏠', 'sort_order' => 4, 'is_featured' => true],
            ['name' => 'Furniture', 'icon' => '🛋️', 'sort_order' => 5, 'is_featured' => false],
            ['name' => 'Toys', 'icon' => '🧸', 'sort_order' => 6, 'is_featured' => false],
            ['name' => 'Mobile Accessories', 'icon' => '🔌', 'sort_order' => 7, 'is_featured' => true],
            ['name' => 'Beauty', 'icon' => '💄', 'sort_order' => 8, 'is_featured' => true],
            ['name' => 'Hardware', 'icon' => '🔧', 'sort_order' => 9, 'is_featured' => false],
            ['name' => 'Grocery', 'icon' => '🛒', 'sort_order' => 10, 'is_featured' => false],
            ['name' => 'Sports', 'icon' => '⚽', 'sort_order' => 11, 'is_featured' => false],
            ['name' => 'Bags', 'icon' => '👜', 'sort_order' => 12, 'is_featured' => false],
            ['name' => 'Watches', 'icon' => '⌚', 'sort_order' => 13, 'is_featured' => false],
            ['name' => 'Other', 'icon' => '📦', 'sort_order' => 14, 'is_featured' => false],
        ];

        foreach ($categories as $cat) {
            $slug = \Illuminate\Support\Str::slug($cat['name']);
            Category::updateOrCreate(['slug' => $slug], array_merge($cat, ['slug' => $slug, 'is_active' => true]));
        }
    }
}
