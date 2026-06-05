<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;

class ShopController extends Controller
{
    public function categories(Request $request, $category = null)
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

        // Attach a slug to each category for nicer URLs
        foreach ($categories as &$c) {
            $c['slug'] = Str::slug($c['name']);
        }

        $selectedCategory = null;
        $products = collect();

        if (!empty($category)) {
            // Find matching category by slug
            $found = collect($categories)->firstWhere('slug', $category);
            if ($found) {
                $selectedCategory = $found['name'];
                // Fetch products matching the category name
                $products = Product::where('category', $selectedCategory)->get();
            }
        }

        return view('categories', compact('categories', 'selectedCategory', 'products'));
    }
}
