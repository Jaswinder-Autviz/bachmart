<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Shop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Fashion Hub Delhi
            ['shop' => 'Fashion Hub Delhi', 'category' => 'Fashion', 'name' => 'Men\'s Casual Cotton Shirt (Pack of 3)', 'original_price' => 1800, 'offer_price' => 899, 'brand' => 'Arrow', 'condition' => 'new', 'quantity' => 25, 'description' => 'Pack of 3 premium cotton shirts in assorted colors. Perfect for casual and semi-formal occasions. Size M to XL available.', 'is_featured' => true],
            ['shop' => 'Fashion Hub Delhi', 'category' => 'Fashion', 'name' => 'Women\'s Designer Kurti Collection', 'original_price' => 2500, 'offer_price' => 999, 'brand' => 'Fabindia', 'condition' => 'new', 'quantity' => 15, 'description' => 'Beautiful printed kurtis in pure cotton. Ideal for daily wear. Available in multiple colors and sizes S to XXL.', 'is_featured' => false],
            ['shop' => 'Fashion Hub Delhi', 'category' => 'Fashion', 'name' => 'Kids Festive Wear Combo', 'original_price' => 1500, 'offer_price' => 699, 'brand' => 'Allen Solly Junior', 'condition' => 'new', 'quantity' => 20, 'description' => 'Set of shirt and trouser for kids aged 5-12. Perfect for festivals, birthdays, and special occasions.', 'is_featured' => false],
            ['shop' => 'Fashion Hub Delhi', 'category' => 'Fashion', 'name' => 'Ladies Palazzo Set', 'original_price' => 3000, 'offer_price' => 1299, 'brand' => 'W for Woman', 'condition' => 'new', 'quantity' => 30, 'description' => 'Elegant palazzo sets with kurtis in georgette material. Multiple patterns and colors available.', 'is_featured' => true],
            ['shop' => 'Fashion Hub Delhi', 'category' => 'Bags', 'name' => 'Leather Handbag Combo Set', 'original_price' => 4500, 'offer_price' => 1799, 'brand' => 'Caprese', 'condition' => 'new', 'quantity' => 10, 'description' => 'Set of 2 genuine leather handbags. One tote and one sling bag. Multiple colors available.', 'is_featured' => false],

            // Mumbai Shoe Palace
            ['shop' => 'Mumbai Shoe Palace', 'category' => 'Shoes', 'name' => 'Nike Sports Running Shoes', 'original_price' => 5999, 'offer_price' => 2999, 'brand' => 'Nike', 'condition' => 'new', 'quantity' => 18, 'description' => 'Original Nike running shoes with air cushion sole. Sizes 6-12 available. Perfect for gym and outdoor running.', 'is_featured' => true],
            ['shop' => 'Mumbai Shoe Palace', 'category' => 'Shoes', 'name' => 'Ladies Formal Heels Collection', 'original_price' => 3500, 'offer_price' => 1499, 'brand' => 'Metro', 'condition' => 'new', 'quantity' => 22, 'description' => 'Elegant formal heels in patent leather finish. Available in black, brown and nude. Sizes 4-9.', 'is_featured' => false],
            ['shop' => 'Mumbai Shoe Palace', 'category' => 'Shoes', 'name' => 'Kids School Shoes', 'original_price' => 1200, 'offer_price' => 499, 'brand' => 'Bata', 'condition' => 'new', 'quantity' => 50, 'description' => 'Durable school shoes for boys and girls. Canvas and leather options. Sizes 25-38.', 'is_featured' => false],
            ['shop' => 'Mumbai Shoe Palace', 'category' => 'Shoes', 'name' => 'Men\'s Casual Loafers', 'original_price' => 2800, 'offer_price' => 1199, 'brand' => 'Clarks', 'condition' => 'new', 'quantity' => 30, 'description' => 'Comfortable slip-on loafers in genuine leather. Great for office and casual wear. Sizes 7-12.', 'is_featured' => false],
            ['shop' => 'Mumbai Shoe Palace', 'category' => 'Shoes', 'name' => 'Adidas Stan Smith Replica', 'original_price' => 3200, 'offer_price' => 1499, 'brand' => 'Adidas', 'condition' => 'new', 'quantity' => 40, 'description' => 'Classic white sneakers inspired by the iconic design. Comfortable and stylish for all occasions.', 'is_featured' => true],

            // TechZone Ahmedabad
            ['shop' => 'TechZone Ahmedabad', 'category' => 'Electronics', 'name' => 'Samsung LED TV 43" (2022 Model)', 'original_price' => 38000, 'offer_price' => 24999, 'brand' => 'Samsung', 'condition' => 'new', 'quantity' => 5, 'description' => 'Samsung 43-inch Full HD LED TV with 3 HDMI ports, 2 USB ports. Smart TV with built-in WiFi. Box piece.', 'is_featured' => true],
            ['shop' => 'TechZone Ahmedabad', 'category' => 'Electronics', 'name' => 'Boat Bluetooth Speakers (Pair)', 'original_price' => 6000, 'offer_price' => 2799, 'brand' => 'boAt', 'condition' => 'new', 'quantity' => 12, 'description' => 'Powerful stereo speakers with 20W output, deep bass, and 12 hours battery. Waterproof design.', 'is_featured' => false],
            ['shop' => 'TechZone Ahmedabad', 'category' => 'Electronics', 'name' => 'Laptop Refurbished Dell Core i5', 'original_price' => 45000, 'offer_price' => 18500, 'brand' => 'Dell', 'condition' => 'good', 'quantity' => 3, 'description' => 'Dell Latitude Core i5, 8GB RAM, 256GB SSD. Professionally refurbished with 6 months warranty. Windows 11.', 'is_featured' => false],
            ['shop' => 'TechZone Ahmedabad', 'category' => 'Mobile Accessories', 'name' => 'Wireless Earbuds TWS Pro', 'original_price' => 4500, 'offer_price' => 1499, 'brand' => 'Noise', 'condition' => 'new', 'quantity' => 30, 'description' => 'True wireless earbuds with Active Noise Cancellation, 30 hours total playback. Multiple color options.', 'is_featured' => true],
            ['shop' => 'TechZone Ahmedabad', 'category' => 'Mobile Accessories', 'name' => 'Fast Charging Cable Bundle (5 Pcs)', 'original_price' => 1500, 'offer_price' => 499, 'brand' => 'Anker', 'condition' => 'new', 'quantity' => 100, 'description' => 'Pack of 5 multi-type charging cables (Type-C, Micro USB, Lightning). 3A fast charging, 2m length.', 'is_featured' => false],

            // Home Comfort Bengaluru
            ['shop' => 'Home Comfort Bengaluru', 'category' => 'Home & Decor', 'name' => 'Decorative Cushion Covers (Set of 5)', 'original_price' => 2000, 'offer_price' => 799, 'brand' => 'Home Centre', 'condition' => 'new', 'quantity' => 40, 'description' => 'Beautiful printed cushion covers in multiple designs. 16x16 inch size. Machine washable cotton.', 'is_featured' => false],
            ['shop' => 'Home Comfort Bengaluru', 'category' => 'Furniture', 'name' => 'Wooden Coffee Table Set', 'original_price' => 15000, 'offer_price' => 7999, 'brand' => 'Pepperfry', 'condition' => 'like_new', 'quantity' => 2, 'description' => 'Solid sheesham wood coffee table with 2 side tables. Minimal use, like new condition. Self-pickup preferred.', 'is_featured' => false],
            ['shop' => 'Home Comfort Bengaluru', 'category' => 'Home & Decor', 'name' => 'Wall Art Canvas Prints (Set of 3)', 'original_price' => 3500, 'offer_price' => 1299, 'brand' => 'Usha', 'condition' => 'new', 'quantity' => 15, 'description' => 'Modern abstract canvas prints in 3 complementary designs. 12x18 inch each. Ready to hang.', 'is_featured' => false],

            // Jaipur Textile Corner
            ['shop' => 'Jaipur Textile Corner', 'category' => 'Fashion', 'name' => 'Banarasi Silk Saree', 'original_price' => 8000, 'offer_price' => 3999, 'brand' => 'Taneira', 'condition' => 'new', 'quantity' => 8, 'description' => 'Pure Banarasi silk saree with zari work. Traditional design with modern color palette. Comes with matching blouse piece.', 'is_featured' => true],
            ['shop' => 'Jaipur Textile Corner', 'category' => 'Fashion', 'name' => 'Rajasthani Embroidered Dupatta', 'original_price' => 1200, 'offer_price' => 499, 'brand' => 'Rajasthali', 'condition' => 'new', 'quantity' => 50, 'description' => 'Hand embroidered pure cotton dupatta with mirror work. Traditional patterns in vibrant colors.', 'is_featured' => false],
            ['shop' => 'Jaipur Textile Corner', 'category' => 'Home & Decor', 'name' => 'Jaipuri Printed Bedsheet Set', 'original_price' => 2500, 'offer_price' => 999, 'brand' => 'Swayam', 'condition' => 'new', 'quantity' => 35, 'description' => 'Double bed 100% cotton Jaipuri printed bedsheet with 2 pillow covers. Traditional block print design.', 'is_featured' => false],

            // Beauty World Hyderabad
            ['shop' => 'Beauty World Hyderabad', 'category' => 'Beauty', 'name' => 'Lakme Complete Makeup Kit', 'original_price' => 5000, 'offer_price' => 2499, 'brand' => 'Lakme', 'condition' => 'new', 'quantity' => 10, 'description' => 'Complete makeup set including foundation, lipsticks, mascara, eyeliner, blush, and primers. All authentic products.', 'is_featured' => false],
            ['shop' => 'Beauty World Hyderabad', 'category' => 'Beauty', 'name' => 'Hair Care Bundle - Shampoo + Conditioner + Serum', 'original_price' => 1800, 'offer_price' => 899, 'brand' => 'Dove', 'condition' => 'new', 'quantity' => 25, 'description' => 'Complete hair care set. Shampoo 700ml, Conditioner 500ml, Hair Serum 100ml. For all hair types.', 'is_featured' => false],
            ['shop' => 'Beauty World Hyderabad', 'category' => 'Beauty', 'name' => 'Skincare Essentials Kit', 'original_price' => 3000, 'offer_price' => 1399, 'brand' => 'Neutrogena', 'condition' => 'new', 'quantity' => 18, 'description' => 'Complete skincare routine: Cleanser, Toner, Moisturizer, Sunscreen SPF50. For normal to oily skin.', 'is_featured' => true],

            // Sports Arena Chandigarh
            ['shop' => 'Sports Arena Chandigarh', 'category' => 'Sports', 'name' => 'Cricket Kit Complete Set', 'original_price' => 8000, 'offer_price' => 3999, 'brand' => 'SG', 'condition' => 'new', 'quantity' => 5, 'description' => 'Full cricket kit: bat, pads, gloves, helmet, guard, and kit bag. Ideal for club and school players.', 'is_featured' => false],
            ['shop' => 'Sports Arena Chandigarh', 'category' => 'Sports', 'name' => 'Yoga Mat Premium with Bag', 'original_price' => 2500, 'offer_price' => 999, 'brand' => 'Boldfit', 'condition' => 'new', 'quantity' => 40, 'description' => '6mm thick non-slip premium yoga mat. Extra long (183cm), includes carry bag and strap.', 'is_featured' => false],
            ['shop' => 'Sports Arena Chandigarh', 'category' => 'Sports', 'name' => 'Dumbbell Set 5kg x 2', 'original_price' => 2000, 'offer_price' => 899, 'brand' => 'Kore', 'condition' => 'new', 'quantity' => 20, 'description' => 'Cast iron fixed weight dumbbells. Rubber coated, sweat resistant grip. Pair of 5kg dumbbells.', 'is_featured' => false],

            // Gadget Galaxy Pune
            ['shop' => 'Gadget Galaxy Pune', 'category' => 'Mobile Accessories', 'name' => 'Smartwatch Fitness Tracker', 'original_price' => 6000, 'offer_price' => 1999, 'brand' => 'Fire-Boltt', 'condition' => 'new', 'quantity' => 15, 'description' => 'Smart fitness band with heart rate monitor, sleep tracker, 30+ sports modes. 10-day battery life.', 'is_featured' => true],
            ['shop' => 'Gadget Galaxy Pune', 'category' => 'Mobile Accessories', 'name' => 'Power Bank 20000mAh', 'original_price' => 3500, 'offer_price' => 1499, 'brand' => 'Mi', 'condition' => 'new', 'quantity' => 30, 'description' => 'Xiaomi 20000mAh power bank with 18W fast charging. 3 USB outputs, LED indicator, compact design.', 'is_featured' => false],
            ['shop' => 'Gadget Galaxy Pune', 'category' => 'Electronics', 'name' => 'Wireless Keyboard & Mouse Combo', 'original_price' => 2500, 'offer_price' => 899, 'brand' => 'Logitech', 'condition' => 'new', 'quantity' => 20, 'description' => 'Logitech wireless combo with silent keys and responsive mouse. 2.4GHz connection, 24 months battery life.', 'is_featured' => false],

            // Saree Palace Chennai
            ['shop' => 'Saree Palace Chennai', 'category' => 'Fashion', 'name' => 'Kanjivaram Silk Saree', 'original_price' => 12000, 'offer_price' => 6999, 'brand' => 'Kumaran Silks', 'condition' => 'new', 'quantity' => 6, 'description' => 'Authentic Kanjivaram pure silk saree with zari border and pallu. Traditional temple design. Certificate of authenticity included.', 'is_featured' => true],
            ['shop' => 'Saree Palace Chennai', 'category' => 'Fashion', 'name' => 'Cotton Tant Saree Collection', 'original_price' => 3000, 'offer_price' => 1299, 'brand' => 'Satyam Fashion', 'condition' => 'new', 'quantity' => 25, 'description' => 'Lightweight cotton tant sarees perfect for daily wear. Set of 3 sarees in different color combinations.', 'is_featured' => false],
            ['shop' => 'Saree Palace Chennai', 'category' => 'Watches', 'name' => 'Titan Analog Watch for Men', 'original_price' => 5000, 'offer_price' => 2999, 'brand' => 'Titan', 'condition' => 'new', 'quantity' => 8, 'description' => 'Titan Karishma analog watch with leather strap. Water resistant, sapphire crystal glass. Comes in original box.', 'is_featured' => false],
            // Kerala Spices
            ['shop' => 'Kerala Spices & Grocery', 'category' => 'Grocery', 'name' => 'Kerala Spices Combo Box', 'original_price' => 1500, 'offer_price' => 799, 'brand' => 'Everest', 'condition' => 'new', 'quantity' => 60, 'description' => 'Box of 10 authentic Kerala spices: turmeric, black pepper, cardamom, cloves, cinnamon, and more. Farm fresh.', 'is_featured' => false],
            ['shop' => 'Kerala Spices & Grocery', 'category' => 'Grocery', 'name' => 'Virgin Coconut Oil 1 Litre', 'original_price' => 800, 'offer_price' => 449, 'brand' => 'Cocoraj', 'condition' => 'new', 'quantity' => 100, 'description' => 'Pure cold-pressed virgin coconut oil. No preservatives, no additives. Good for cooking and hair care.', 'is_featured' => false],
        ];

        foreach ($products as $idx => $data) {
            $shopName = $data['shop'];
            unset($data['shop']);
            $categoryName = $data['category'];
            unset($data['category']);

            $shop = Shop::where('name', $shopName)->first();
            $category = Category::where('name', $categoryName)->first();

            if (!$shop || !$category) continue;

            $slug = Str::slug($data['name']);
            $base = $slug;
            $i = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $base . '-' . $i++;
            }

            $product = Product::create(array_merge($data, [
                'user_id' => $shop->user_id,
                'shop_id' => $shop->id,
                'category_id' => $category->id,
                'slug' => $slug,
                'status' => 'approved',
                'unit' => 'piece',
                'views_count' => rand(10, 500),
                'calls_count' => rand(1, 50),
                'whatsapp_count' => rand(1, 30),
                'directions_count' => rand(0, 20),
            ]));

            // Create a placeholder image record (no actual file)
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'products/placeholder.jpg',
                'is_primary' => true,
                'sort_order' => 0,
                'alt_text' => $product->name,
            ]);
        }
    }
}
