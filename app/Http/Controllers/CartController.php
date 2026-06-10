<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', auth('web')->id())
            ->get();

        return view('cart', compact('cartItems'));
    }

    public function add(Product $product)
    {
        $cartItem = CartItem::where('user_id', auth('web')->id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            CartItem::create([
                'user_id'    => auth('web')->id(),
                'product_id' => $product->id,
                'quantity'   => 1,
            ]);
        }

        return back()->with('success', 'Added to cart!');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $this->authorizeItem($cartItem);

        $qty = (int) $request->input('quantity', 1);

        if ($qty < 1) {
            $cartItem->delete();
        } else {
            $cartItem->update(['quantity' => $qty]);
        }

        return back()->with('success', 'Cart updated.');
    }

    public function remove(CartItem $cartItem)
    {
        $this->authorizeItem($cartItem);
        $cartItem->delete();

        return back()->with('success', 'Item removed.');
    }

    public function clear()
    {
        CartItem::where('user_id', auth('web')->id())->delete();

        return back()->with('success', 'Cart cleared.');
    }

    public function buyNow(Product $product)
    {
        session(['buynow_product_id' => $product->id]);

        return redirect()->route('checkout');
    }

    private function authorizeItem(CartItem $cartItem): void
    {
        abort_unless($cartItem->user_id === auth('web')->id(), 403);
    }
}
