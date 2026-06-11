<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    const CATEGORIES = [
        'Medicines', 'Personal Care', 'Baby Care', 'Wellness', 'Vitamins', 'First Aid',
    ];

    public function index()
    {
        $grouped = Product::orderBy('name')->get()->groupBy('category');

        $ordered = collect();
        foreach (self::CATEGORIES as $cat) {
            if ($grouped->has($cat)) $ordered[$cat] = $grouped[$cat];
        }
        foreach ($grouped as $cat => $items) {
            if (!$ordered->has($cat)) $ordered[$cat] = $items;
        }

        return response()
            ->view('admin.dashboard', [
                'adminName'     => auth('admin')->user()->name,
                'products'      => $ordered,
                'categories'    => self::CATEGORIES,
                'totalProducts' => Product::count(),
                'totalStock'    => Product::sum('stock'),
                'totalOrders'   => Order::count(),
                'totalUsers'    => DB::table('sessions')
                    ->whereNotNull('user_id')
                    ->whereIn('user_id', User::where('is_admin', false)->whereNotNull('email_verified_at')->pluck('id'))
                    ->count(),
                'outOfStock'    => Product::where('stock', 0)->count(),
            ])
            ->withHeaders([
                'Cache-Control' => 'no-store, no-cache, must-revalidate',
                'Pragma'        => 'no-cache',
            ]);
    }

    public function products(Request $request)
    {
        $query = Product::orderBy('name');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('stock_status')) {
            match($request->stock_status) {
                'out' => $query->where('stock', 0),
                'low' => $query->where('stock', '>', 0)->where('stock', '<', 10),
                'in'  => $query->where('stock', '>=', 10),
                default => null,
            };
        }

        $products = $query->paginate(15)->withQueryString();

        return response()
            ->view('admin.products', [
                'products'      => $products,
                'categories'    => self::CATEGORIES,
                'totalProducts' => Product::count(),
                'inStock'       => Product::where('stock', '>=', 10)->count(),
                'lowStock'      => Product::where('stock', '>', 0)->where('stock', '<', 10)->count(),
                'outOfStock'    => Product::where('stock', 0)->count(),
            ])
            ->withHeaders([
                'Cache-Control' => 'no-store, no-cache, must-revalidate',
                'Pragma'        => 'no-cache',
            ]);
    }

    public function storeProduct(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string|max:100',
            'price'       => 'required|numeric|min:0',
            'old_price'   => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'stock'       => 'required|integer|min:0',
            'is_featured' => 'nullable|boolean',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        unset($data['image']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return back()->with('success', 'Product added successfully.');
    }

    public function updateProduct(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string|max:100',
            'price'       => 'required|numeric|min:0',
            'old_price'   => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'stock'       => 'required|integer|min:0',
            'is_featured' => 'nullable|boolean',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        unset($data['image']);

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return back()->with('success', 'Product updated successfully.');
    }

    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $product->update(['stock' => (int) $request->stock]);

        return response()->json([
            'stock'      => $product->fresh()->stock,
            'totalStock' => Product::sum('stock'),
            'outOfStock' => Product::where('stock', 0)->count(),
            'message'    => 'Stock updated successfully.',
        ])->withHeaders([
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma'        => 'no-cache',
        ]);
    }

    public function destroyProduct(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return back()->with('success', 'Product deleted successfully.');
    }

    // ── Orders ────────────────────────────────────────────────────────────────

    public function orders(Request $request)
    {
        $query = Order::with('user', 'items.product')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        return view('admin.orders', [
            'adminName' => auth('admin')->user()->name,
            'orders'    => $query->paginate(15)->withQueryString(),
            'statuses'  => Order::$statuses,
            'counts'    => [
                'all'       => Order::count(),
                'Pending'   => Order::where('status', 'Pending')->count(),
                'Processing'=> Order::where('status', 'Processing')->count(),
                'Shipped'   => Order::where('status', 'Shipped')->count(),
                'Delivered' => Order::where('status', 'Delivered')->count(),
                'Cancelled' => Order::where('status', 'Cancelled')->count(),
            ],
        ]);
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:Pending,Processing,Shipped,Delivered,Cancelled',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        if ($oldStatus === $newStatus) {
            return back()->with('success', "Order #" . str_pad($order->id, 5, '0', STR_PAD_LEFT) . " status is already {$newStatus}.");
        }

        $order->loadMissing('items');

        DB::transaction(function () use ($order, $oldStatus, $newStatus) {
            // Restore stock when cancelling an active order
            if ($newStatus === 'Cancelled' && $oldStatus !== 'Cancelled') {
                foreach ($order->items as $item) {
                    Product::where('id', $item->product_id)
                        ->lockForUpdate()
                        ->increment('stock', $item->quantity);
                }
            }

            // Deduct stock if reactivating a previously cancelled order
            if ($oldStatus === 'Cancelled' && $newStatus !== 'Cancelled') {
                $productIds = $order->items->pluck('product_id');
                $products   = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

                foreach ($order->items as $item) {
                    $product = $products->get($item->product_id);
                    if ($product && $product->stock >= $item->quantity) {
                        $product->decrement('stock', $item->quantity);
                    }
                }
            }

            $order->update(['status' => $newStatus]);
        });

        return back()->with('success', "Order #" . str_pad($order->id, 5, '0', STR_PAD_LEFT) . " status updated to {$newStatus}.");
    }

    // ── Users ─────────────────────────────────────────────────────────────────

    public function users()
    {
        $activeUserIds = DB::table('sessions')->whereNotNull('user_id')->pluck('user_id')->toArray();

        return view('admin.users', [
            'adminName'     => auth('admin')->user()->name,
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

        return redirect()->route('admin.users')->with('success', 'All user sessions have been cleared.');
    }

    public function deleteUser(User $user)
    {
        $orderIds = DB::table('orders')->where('user_id', $user->id)->pluck('id');

        DB::table('sessions')->where('user_id', $user->id)->delete();
        DB::table('cart_items')->where('user_id', $user->id)->delete();
        DB::table('password_reset_tokens')->where('email', $user->email)->delete();
        DB::table('order_items')->whereIn('order_id', $orderIds)->delete();
        DB::table('orders')->where('user_id', $user->id)->delete();
        DB::table('messages')->where('user_id', $user->id)->delete();
        $user->delete();

        return redirect()->route('admin.users')->with('success', "User account has been deleted.");
    }

    public function deleteAllUsers()
    {
        $nonAdminIds = User::where('is_admin', false)->pluck('id');
        $emails      = User::where('is_admin', false)->pluck('email');
        $orderIds    = DB::table('orders')->whereIn('user_id', $nonAdminIds)->pluck('id');

        DB::table('sessions')->whereIn('user_id', $nonAdminIds)->delete();
        DB::table('cart_items')->whereIn('user_id', $nonAdminIds)->delete();
        DB::table('password_reset_tokens')->whereIn('email', $emails)->delete();
        DB::table('order_items')->whereIn('order_id', $orderIds)->delete();
        DB::table('orders')->whereIn('user_id', $nonAdminIds)->delete();
        DB::table('messages')->whereIn('user_id', $nonAdminIds)->delete();
        User::where('is_admin', false)->delete();

        return redirect()->route('admin.users')->with('success', 'All user accounts have been deleted.');
    }

    public function clearAllUsers(Request $request)
    {
        return $this->deleteAllUsers();
    }
}
