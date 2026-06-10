<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

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
        $buyNowProduct   = $buyNowProductId ? Product::find($buyNowProductId) : null;

        if ($buyNowProduct) {
            $orderItems = collect([(object)['product' => $buyNowProduct, 'quantity' => 1]]);
        } else {
            $orderItems = CartItem::with('product')
                ->where('user_id', auth('web')->id())
                ->get()
                ->filter(fn($i) => $i->product);
        }

        if ($orderItems->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        $total = $orderItems->reduce(fn($s, $i) => $s + $i->product->price * $i->quantity, 0);

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

        foreach ($orderItems as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item->product->id,
                'quantity'   => $item->quantity,
                'price'      => $item->product->price,
            ]);
        }

        // Clear cart / buy-now session
        if ($buyNowProduct) {
            session()->forget('buynow_product_id');
        } else {
            CartItem::where('user_id', auth('web')->id())->delete();
        }

        return redirect()->route('orders.confirmation', $order)->with('success', 'Order placed successfully!');
    }

    public function confirmation(Order $order)
    {
        abort_unless($order->user_id === auth('web')->id(), 403);
        $order->load('items.product');
        return view('order-confirmation', compact('order'));
    }
}
