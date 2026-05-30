<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $all_products = [
            ['id' => 1,  'name' => 'Paracetamol 500mg',      'category' => 'Medicines',      'price' => 4.99,  'old_price' => 6.99,  'rating' => 4.0],
            ['id' => 2,  'name' => 'Vitamin C 1000mg',        'category' => 'Vitamins',       'price' => 12.5,  'old_price' => null,   'rating' => 4.7],
            ['id' => 3,  'name' => 'Baby Lotion 200ml',       'category' => 'Baby Care',      'price' => 8.25,  'old_price' => null,   'rating' => 4.0],
            ['id' => 4,  'name' => 'Digital Thermometer',     'category' => 'Health Devices', 'price' => 15.00, 'old_price' => null,   'rating' => 4.6],
            ['id' => 5,  'name' => 'Herbal Cough Syrup',      'category' => 'Wellness',       'price' => 9.99,  'old_price' => null,   'rating' => 4.5],
            ['id' => 6,  'name' => 'First Aid Kit',           'category' => 'First Aid',      'price' => 24.99, 'old_price' => null,   'rating' => 4.8],
            ['id' => 7,  'name' => 'Omega-3 Capsules',        'category' => 'Vitamins',       'price' => 18.75, 'old_price' => 21.00,  'rating' => 4.7],
            ['id' => 8,  'name' => 'Blood Pressure Monitor',  'category' => 'Health Devices', 'price' => 49.00, 'old_price' => null,   'rating' => 4.3],
            ['id' => 9,  'name' => 'Ibuprofen 400mg',         'category' => 'Medicines',      'price' => 5.50,  'old_price' => null,   'rating' => 4.2],
            ['id' => 10, 'name' => 'Sunscreen SPF 50',        'category' => 'Personal Care',  'price' => 13.99, 'old_price' => null,   'rating' => 4.6],
            ['id' => 11, 'name' => 'Vitamin D3 2000IU',       'category' => 'Vitamins',       'price' => 9.99,  'old_price' => null,   'rating' => 4.8],
            ['id' => 12, 'name' => 'Baby Shampoo 300ml',      'category' => 'Baby Care',      'price' => 7.50,  'old_price' => null,   'rating' => 4.4],
        ];

        $category_filter = $request->get('category');
        $products = $category_filter
            ? array_filter($all_products, fn($p) => $p['category'] === $category_filter)
            : $all_products;

        $categories = array_values(array_unique(array_map(fn($p) => $p['category'], $all_products)));

        return view('shop', [
            'products' => array_values($products),
            'category_filter' => $category_filter,
            'categories' => $categories,
        ]);
    }

    public function categories()
    {
        $categories = [
            ['name' => 'Medicines',      'count' => 12, 'icon' => 'pill'],
            ['name' => 'Personal Care',  'count' => 8,  'icon' => 'sparkles'],
            ['name' => 'Baby Care',      'count' => 10, 'icon' => 'baby'],
            ['name' => 'Wellness',       'count' => 15, 'icon' => 'leaf'],
            ['name' => 'Vitamins',       'count' => 20, 'icon' => 'flask'],
            ['name' => 'Health Devices', 'count' => 7,  'icon' => 'heart-pulse'],
            ['name' => 'First Aid',      'count' => 5,  'icon' => 'first-aid'],
        ];
        return view('categories', compact('categories'));
    }
}