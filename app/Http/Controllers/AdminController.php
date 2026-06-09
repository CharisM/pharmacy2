<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'adminName' => auth()->user()->name,
            'products' => Product::orderBy('name')->paginate(20),
            'totalProducts' => Product::count(),
            'totalStock' => Product::sum('stock'),
            'totalUsers' => DB::table('sessions')
                ->whereNotNull('user_id')
                ->whereIn('user_id', User::where('is_admin', false)->whereNotNull('email_verified_at')->pluck('id'))
                ->count(),
            'outOfStock' => Product::where('stock', 0)->count(),
        ]);
    }

    public function users()
    {
        $activeUserIds = DB::table('sessions')
            ->whereNotNull('user_id')
            ->pluck('user_id')
            ->toArray();

        return view('admin.users', [
            'adminName'     => auth()->user()->name,
            'activeUserIds' => $activeUserIds,
            'users'         => User::where('is_admin', false)
                ->whereNotNull('email_verified_at')
                ->orderBy('name')
                ->paginate(20),
        ]);
    }

    public function resetUsers()
    {
        DB::table('sessions')
            ->whereIn('user_id', User::where('is_admin', false)->pluck('id'))
            ->delete();

        return redirect()->route('admin.users')->with('success', 'All user sessions have been cleared. The Active Users list has been reset.');
    }

    public function forceLogoutAll()
    {
        DB::table('sessions')
            ->where('user_id', '!=', auth()->id())
            ->delete();

        return redirect()->route('admin.users')->with('success', 'All users have been logged out and must sign in again.');
    }

    public function updateProduct(Request $request, Product $product)
    {
        $data = $request->validate([
            'stock'        => 'required|integer|min:0',
            'image'        => 'nullable|string|max:255',
            'image_upload' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image_upload')) {
            $path = $request->file('image_upload')->store('product-images', 'public');
            $data['image'] = $path;
        }

        unset($data['image_upload']);
        $product->update($data);

        return back()->with('success', 'Product updated successfully.');
    }
}
