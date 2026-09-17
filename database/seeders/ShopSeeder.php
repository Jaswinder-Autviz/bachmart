<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        $shops = [
            [
                'seller_email' => 'rajesh@example.com',
                'name' => 'Fashion Hub Delhi',
                'description' => 'Your one-stop destination for the latest fashion trends. Men\'s, Women\'s, and Kids clothing at amazing prices.',
                'phone' => '+91 9811111111',
                'whatsapp' => '9811111111',
                'email' => 'fashionhub@gmail.com',
                'address' => 'Shop No. 12, Lajpat Nagar Market',
                'area' => 'Lajpat Nagar',
                'city' => 'Delhi',
                'state' => 'Delhi',
                'pincode' => '110024',
                'latitude' => 28.5665,
                'longitude' => 77.2433,
                'is_featured' => true,
                'is_verified' => true,
                'status' => 'active',
                'opening_hours' => $this->defaultHours(),
            ],
            [
                'seller_email' => 'priya@example.com',
                'name' => 'Mumbai Shoe Palace',
                'description' => 'Largest shoe store in Dadar. Sports shoes, formal shoes, sandals, and heels for all occasions.',
                'phone' => '+91 9822222222',
                'whatsapp' => '9822222222',
                'email' => 'mumbaishopalace@gmail.com',
                'address' => 'Ground Floor, Dadar West Market',
                'area' => 'Dadar West',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400028',
                'latitude' => 19.0195,
                'longitude' => 72.8442,
                'is_featured' => true,
                'is_verified' => true,
                'status' => 'active',
                'opening_hours' => $this->defaultHours(),
            ],
            [
                'seller_email' => 'amit@example.com',
                'name' => 'TechZone Ahmedabad',
                'description' => 'Electronics, mobiles, laptops, and accessories at wholesale prices. Authorized reseller of multiple brands.',
                'phone' => '+91 9833333333',
                'whatsapp' => '9833333333',
                'email' => 'techzone@gmail.com',
                'address' => 'Shop 7, ITC Plaza, CG Road',
                'area' => 'CG Road',
                'city' => 'Ahmedabad',
                'state' => 'Gujarat',
                'pincode' => '380009',
                'latitude' => 23.0225,
                'longitude' => 72.5714,
                'is_featured' => false,
                'is_verified' => true,
                'status' => 'active',
                'opening_hours' => $this->defaultHours(),
            ],
            [
                'seller_email' => 'sunita@example.com',
                'name' => 'Home Comfort Bengaluru',
                'description' => 'Beautiful home decor, cushions, curtains, wall art, and furniture at discounted prices.',
                'phone' => '+91 9844444444',
                'whatsapp' => '9844444444',
                'email' => 'homecomfort@gmail.com',
                'address' => '45, Brigade Road, Near Garuda Mall',
                'area' => 'Brigade Road',
                'city' => 'Bengaluru',
                'state' => 'Karnataka',
                'pincode' => '560025',
                'latitude' => 12.9716,
                'longitude' => 77.5946,
                'is_featured' => false,
                'is_verified' => false,
                'status' => 'active',
                'opening_hours' => $this->defaultHours(),
            ],
            [
                'seller_email' => 'suresh@example.com',
                'name' => 'Jaipur Textile Corner',
                'description' => 'Traditional Rajasthani textiles, dupattas, kurtas and handicrafts. Direct from artisans to you.',
                'phone' => '+91 9855555555',
                'whatsapp' => '9855555555',
                'email' => 'jaipurtextile@gmail.com',
                'address' => 'Johari Bazaar, Near Hawa Mahal',
                'area' => 'Johari Bazaar',
                'city' => 'Jaipur',
                'state' => 'Rajasthan',
                'pincode' => '302003',
                'latitude' => 26.9239,
                'longitude' => 75.8267,
                'is_featured' => false,
                'is_verified' => false,
                'status' => 'active',
                'opening_hours' => $this->defaultHours(),
            ],
            [
                'seller_email' => 'meena@example.com',
                'name' => 'Beauty World Hyderabad',
                'description' => 'Cosmetics, skincare, haircare and beauty products from top brands. Special offers every week.',
                'phone' => '+91 9866666666',
                'whatsapp' => '9866666666',
                'email' => 'beautyworld@gmail.com',
                'address' => 'Shop 22, Begumpet Main Road',
                'area' => 'Begumpet',
                'city' => 'Hyderabad',
                'state' => 'Telangana',
                'pincode' => '500016',
                'latitude' => 17.4399,
                'longitude' => 78.4983,
                'is_featured' => false,
                'is_verified' => false,
                'status' => 'active',
                'opening_hours' => $this->defaultHours(),
            ],
            [
                'seller_email' => 'vikram@example.com',
                'name' => 'Sports Arena Chandigarh',
                'description' => 'Cricket, football, gym equipment and all kinds of sports accessories at wholesale rates.',
                'phone' => '+91 9877777777',
                'whatsapp' => '9877777777',
                'email' => 'sportsarena@gmail.com',
                'address' => 'Sector 17, Near Bus Stand',
                'area' => 'Sector 17',
                'city' => 'Chandigarh',
                'state' => 'Punjab',
                'pincode' => '160017',
                'latitude' => 30.7333,
                'longitude' => 76.7794,
                'is_featured' => false,
                'is_verified' => false,
                'status' => 'active',
                'opening_hours' => $this->defaultHours(),
            ],
            [
                'seller_email' => 'kavitha@example.com',
                'name' => 'Kerala Spices & Grocery',
                'description' => 'Fresh Kerala spices, organic grocery, coconut oil and traditional food items.',
                'phone' => '+91 9888888888',
                'whatsapp' => '9888888888',
                'email' => 'keralasp@gmail.com',
                'address' => 'Broadway Market, Ernakulam',
                'area' => 'Broadway',
                'city' => 'Kochi',
                'state' => 'Kerala',
                'pincode' => '682031',
                'latitude' => 9.9312,
                'longitude' => 76.2673,
                'is_featured' => false,
                'is_verified' => false,
                'status' => 'active',
                'opening_hours' => $this->defaultHours(),
            ],
            [
                'seller_email' => 'rohit@example.com',
                'name' => 'Gadget Galaxy Pune',
                'description' => 'Mobile accessories, chargers, earphones, smartwatches and gadgets at best prices in Pune.',
                'phone' => '+91 9899999999',
                'whatsapp' => '9899999999',
                'email' => 'gadgetgalaxy@gmail.com',
                'address' => 'FC Road, Near Ferguson College',
                'area' => 'FC Road',
                'city' => 'Pune',
                'state' => 'Maharashtra',
                'pincode' => '411004',
                'latitude' => 18.5204,
                'longitude' => 73.8567,
                'is_featured' => false,
                'is_verified' => false,
                'status' => 'active',
                'opening_hours' => $this->defaultHours(),
            ],
            [
                'seller_email' => 'deepa@example.com',
                'name' => 'Saree Palace Chennai',
                'description' => 'Silk sarees, cotton sarees, lehengas and traditional South Indian attire at factory prices.',
                'phone' => '+91 9800111111',
                'whatsapp' => '9800111111',
                'email' => 'sareepalace@gmail.com',
                'address' => 'T Nagar, Near Panagal Park',
                'area' => 'T Nagar',
                'city' => 'Chennai',
                'state' => 'Tamil Nadu',
                'pincode' => '600017',
                'latitude' => 13.0418,
                'longitude' => 80.2341,
                'is_featured' => false,
                'is_verified' => false,
                'status' => 'active',
                'opening_hours' => $this->defaultHours(),
            ],
        ];

        foreach ($shops as $data) {
            $sellerEmail = $data['seller_email'];
            unset($data['seller_email']);

            $user = User::where('email', $sellerEmail)->first();
            if (!$user) continue;

            if ($user->shop) continue; // Already has a shop

            $slug = Str::slug($data['name']);
            $base = $slug;
            $i = 1;
            while (Shop::where('slug', $slug)->exists()) {
                $slug = $base . '-' . $i++;
            }

            Shop::create(array_merge($data, [
                'user_id' => $user->id,
                'slug' => $slug,
                'rating' => round(rand(35, 50) / 10, 1),
                'reviews_count' => rand(3, 30),
            ]));
        }
    }

    private function defaultHours(): array
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $hours = [];
        foreach ($days as $day) {
            $hours[$day] = [
                'open' => $day !== 'sunday',
                'from' => '10:00',
                'to' => '21:00',
            ];
        }
        $hours['sunday']['from'] = '11:00';
        $hours['sunday']['to'] = '19:00';
        $hours['sunday']['open'] = true;
        return $hours;
    }
}
