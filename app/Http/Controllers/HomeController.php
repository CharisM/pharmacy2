<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = [
            ['name' => 'Medicines',       'icon' => 'pill',        'color' => '#e8f5e9'],
            ['name' => 'Personal Care',   'icon' => 'sparkles',    'color' => '#e8f5e9'],
            ['name' => 'Baby Care',       'icon' => 'baby',        'color' => '#e8f5e9'],
            ['name' => 'Wellness',        'icon' => 'leaf',        'color' => '#e8f5e9'],
            ['name' => 'Vitamins',        'icon' => 'flask',       'color' => '#e8f5e9'],
            ['name' => 'Health Devices',  'icon' => 'heart-pulse', 'color' => '#e8f5e9'],
            ['name' => 'First Aid',       'icon' => 'first-aid',   'color' => '#e8f5e9'],
        ];

        $featured_products = [
            ['id' => 1, 'name' => 'Paracetamol 500mg',     'category' => 'Medicines',      'price' => 4.99,  'old_price' => 6.99, 'rating' => 4.0],
            ['id' => 2, 'name' => 'Vitamin C 1000mg',      'category' => 'Vitamins',       'price' => 12.5,  'old_price' => null,  'rating' => 4.7],
            ['id' => 3, 'name' => 'Baby Lotion 200ml',     'category' => 'Baby Care',      'price' => 8.25,  'old_price' => null,  'rating' => 4.0],
            ['id' => 4, 'name' => 'Digital Thermometer',   'category' => 'Health Devices', 'price' => 15.00, 'old_price' => null,  'rating' => 4.6],
            ['id' => 5, 'name' => 'Herbal Cough Syrup',    'category' => 'Wellness',       'price' => 9.99,  'old_price' => null,  'rating' => 4.5],
            ['id' => 6, 'name' => 'First Aid Kit',         'category' => 'First Aid',      'price' => 24.99, 'old_price' => null,  'rating' => 4.8],
            ['id' => 7, 'name' => 'Omega-3 Capsules',      'category' => 'Vitamins',       'price' => 18.75, 'old_price' => 21.00, 'rating' => 4.7],
            ['id' => 8, 'name' => 'Blood Pressure Monitor','category' => 'Health Devices', 'price' => 49.00, 'old_price' => null,  'rating' => 4.3],
        ];

        return view('home', compact('categories', 'featured_products'));
    }
}