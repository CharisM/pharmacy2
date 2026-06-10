<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = [
            ['name' => 'Medicines',         'icon' => 'pill',      'color' => '#e8f5e9'],
            ['name' => 'Vitamins',           'icon' => 'flask',     'color' => '#e8f5e9'],
            ['name' => 'Personal Care',      'icon' => 'sparkles',  'color' => '#e8f5e9'],
            ['name' => 'Medical Supplies',   'icon' => 'first-aid', 'color' => '#e8f5e9'],
            ['name' => 'Wellness Products',  'icon' => 'leaf',      'color' => '#e8f5e9'],
            ['name' => 'Others',             'icon' => 'baby',      'color' => '#e8f5e9'],
        ];

        $featured_products = \App\Models\Product::where('is_featured', true)->get();

        return view('home', compact('categories', 'featured_products'));
    }
}