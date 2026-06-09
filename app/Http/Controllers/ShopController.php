<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ShopController extends Controller
{
    public function categories(Request $request, $category = null)
    {
        $categories = [
            ['name' => 'Medicines',     'slug' => 'medicines'],
            ['name' => 'Personal Care', 'slug' => 'personal-care'],
            ['name' => 'Baby Care',     'slug' => 'baby-care'],
            ['name' => 'Wellness',      'slug' => 'wellness'],
            ['name' => 'Vitamins',      'slug' => 'vitamins'],
            ['name' => 'First Aid',     'slug' => 'first-aid'],
        ];

        $selectedCategory = null;
        $products = collect();

        if (!empty($category) && $category !== 'all') {
            $found = collect($categories)->firstWhere('slug', $category);
            if ($found) {
                $selectedCategory = $found['name'];
                $products = Product::where('category', $selectedCategory)->get();
            }
        } else {
            $products = Product::all();
        }

        return view('categories', compact('categories', 'selectedCategory', 'products'));
    }
}
