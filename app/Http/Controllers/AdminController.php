<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'adminName' => auth()->user()->name,
            'products' => Product::orderBy('name')->paginate(20),
            'totalProducts' => Product::count(),
            'totalStock' => Product::sum('stock'),
        ]);
    }

    public function users()
    {
        return view('admin.users', [
            'adminName' => auth()->user()->name,
            'users' => User::orderBy('is_admin', 'desc')
                ->orderBy('name')
                ->paginate(20),
        ]);
    }

    public function updateProduct(Request $request, Product $product)
    {
        $data = $request->validate([
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|string|max:255',
        ]);

        $product->update($data);

        return back()->with('success', 'Product stock and image path updated.');
    }
}
