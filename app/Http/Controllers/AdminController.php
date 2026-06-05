<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'adminName' => auth()->user()->name,
            'totalUsers' => User::count(),
            'totalProducts' => Product::count(),
        ]);
    }
}
