<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ShopController extends Controller
{
    public function categories(Request $request, $category = null)
    {
        $categories = [
            ['name' => 'Medicines',         'slug' => 'medicines'],
            ['name' => 'Vitamins',           'slug' => 'vitamins'],
            ['name' => 'Personal Care',      'slug' => 'personal-care'],
            ['name' => 'Medical Supplies',   'slug' => 'medical-supplies'],
            ['name' => 'Wellness Products',  'slug' => 'wellness-products'],
            ['name' => 'Others',             'slug' => 'others'],
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
