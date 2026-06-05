<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Medicines',
            'Personal Care',
            'Baby Care',
            'Wellness',
            'Vitamins',
            'Health Devices',
            'First Aid',
        ];

        $now = now();

        foreach ($categories as $category) {
            for ($i = 1; $i <= 10; $i++) {
                DB::table('products')->insert([
                    'name' => $category . ' Product ' . $i,
                    'category' => $category,
                    'price' => rand(100, 5000) / 100,
                    'old_price' => null,
                    'rating' => rand(10, 50) / 10,
                    'image' => null,
                    'stock' => rand(0, 20),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
