<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'address'        => 'required|string|max:255',
            'phone'          => 'required|string|max:30',
            'payment_method' => 'required|string',
        ]);

        $buyNowProductId = session('buynow_product_id');
        $isBuyNow        = (bool) $buyNowProductId;

        if ($isBuyNow) {
            $product = Product::find($buyNowProductId);
            if (!$product) {
                return back()->with('error', 'Product not found.');
            }
            $itemRefs = collect([['product_id' => $product->id, 'quantity' => 1]]);
        } else {
            $selectedIds = session('checkout_selected_ids', []);
            if (empty($selectedIds)) {
                return redirect()->route('cart')->with('error', 'No items selected. Please select items from your cart.');
            }

            $cartItems = CartItem::with('product')
                ->where('user_id', auth('web')->id())
                ->whereIn('id', $selectedIds)
                ->get()
                ->filter(fn($i) => $i->product);

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart')->with('error', 'Selected items are no longer available.');
            }

            $itemRefs = $cartItems->map(fn($i) => [
                'product_id' => $i->product_id,
                'quantity'   => $i->quantity,
            ]);
        }

        try {
            $order = DB::transaction(function () use ($request, $itemRefs, $isBuyNow) {
                $productIds = $itemRefs->pluck('product_id');
                $products   = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

                foreach ($itemRefs as $ref) {
                    $product = $products->get($ref['product_id']);
                    if (!$product || $product->stock < $ref['quantity']) {
                        $name  = $product->name ?? 'A product';
                        $avail = $product->stock ?? 0;
                        throw new \RuntimeException("'{$name}' only has {$avail} unit(s) left in stock.");
                    }
                }

                $total = $itemRefs->reduce(
                    fn($s, $ref) => $s + $products->get($ref['product_id'])->price * $ref['quantity'],
                    0
                );

                $order = Order::create([
                    'user_id'        => auth('web')->id(),
                    'first_name'     => $request->first_name,
                    'last_name'      => $request->last_name,
                    'address'        => $request->address,
                    'phone'          => $request->phone,
                    'payment_method' => $request->payment_method,
                    'notes'          => $request->notes,
                    'total'          => $total,
                    'status'         => 'Pending',
                ]);

                foreach ($itemRefs as $ref) {
                    $product = $products->get($ref['product_id']);
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $product->id,
                        'quantity'   => $ref['quantity'],
                        'price'      => $product->price,
                    ]);
                    $product->decrement('stock', $ref['quantity']);
                }

                return $order;
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        // Clear only the ordered items from cart
        if ($isBuyNow) {
            session()->forget('buynow_product_id');
        } else {
            $selectedIds = session('checkout_selected_ids', []);
            CartItem::where('user_id', auth('web')->id())
                ->whereIn('id', $selectedIds)
                ->delete();
            session()->forget('checkout_selected_ids');
        }

        return redirect()->route('orders.confirmation', $order)
            ->with('success', 'Order placed successfully!');
    }

    public function confirmation(Order $order)
    {
        abort_unless($order->user_id === auth('web')->id(), 403);
        $order->load('items.product');
        return view('order-confirmation', compact('order'));
    }
}
