<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@bachatmart.com'],
            [
                'name' => 'BachatMart Admin',
                'password' => Hash::make('admin@123'),
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
                'phone' => '+91 9800000000',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
            ]
        );

        // Sellers
        $sellers = [
            ['name' => 'Rajesh Kumar', 'email' => 'rajesh@example.com', 'phone' => '+91 9811111111', 'city' => 'Delhi'],
            ['name' => 'Priya Sharma', 'email' => 'priya@example.com', 'phone' => '+91 9822222222', 'city' => 'Mumbai'],
            ['name' => 'Amit Patel', 'email' => 'amit@example.com', 'phone' => '+91 9833333333', 'city' => 'Ahmedabad'],
            ['name' => 'Sunita Verma', 'email' => 'sunita@example.com', 'phone' => '+91 9844444444', 'city' => 'Bengaluru'],
            ['name' => 'Suresh Gupta', 'email' => 'suresh@example.com', 'phone' => '+91 9855555555', 'city' => 'Jaipur'],
            ['name' => 'Meena Reddy', 'email' => 'meena@example.com', 'phone' => '+91 9866666666', 'city' => 'Hyderabad'],
            ['name' => 'Vikram Singh', 'email' => 'vikram@example.com', 'phone' => '+91 9877777777', 'city' => 'Chandigarh'],
            ['name' => 'Kavitha Nair', 'email' => 'kavitha@example.com', 'phone' => '+91 9888888888', 'city' => 'Kochi'],
            ['name' => 'Rohit Joshi', 'email' => 'rohit@example.com', 'phone' => '+91 9899999999', 'city' => 'Pune'],
            ['name' => 'Deepa Iyer', 'email' => 'deepa@example.com', 'phone' => '+91 9800111111', 'city' => 'Chennai'],
        ];

        foreach ($sellers as $seller) {
            User::updateOrCreate(
                ['email' => $seller['email']],
                array_merge($seller, [
                    'password' => Hash::make('seller@123'),
                    'role' => 'seller',
                    'status' => 'active',
                    'email_verified_at' => now(),
                    'state' => 'India',
                ])
            );
        }

        // Customers
        $customers = [
            ['name' => 'Arun Kumar', 'email' => 'arun@example.com', 'phone' => '+91 9000000001'],
            ['name' => 'Sanjay Mehta', 'email' => 'sanjay@example.com', 'phone' => '+91 9000000002'],
            ['name' => 'Pooja Shah', 'email' => 'pooja@example.com', 'phone' => '+91 9000000003'],
            ['name' => 'Ravi Tiwari', 'email' => 'ravi@example.com', 'phone' => '+91 9000000004'],
            ['name' => 'Anita Das', 'email' => 'anita@example.com', 'phone' => '+91 9000000005'],
        ];

        foreach ($customers as $customer) {
            User::updateOrCreate(
                ['email' => $customer['email']],
                array_merge($customer, [
                    'password' => Hash::make('customer@123'),
                    'role' => 'customer',
                    'status' => 'active',
                    'email_verified_at' => now(),
                ])
            );
        }
    }
}
