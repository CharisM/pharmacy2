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

        // Return JSON for AJAX calls, redirect for regular form posts
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('success', 'Cart updated.');
    }

    public function remove(CartItem $cartItem)
    {
        $this->authorizeItem($cartItem);
        $cartItem->delete();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['ok' => true]);
        }

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
        session()->forget('checkout_selected_ids');
        return redirect()->route('checkout');
    }

    /**
     * Store selected cart item IDs in session, then redirect to checkout.
     */
    public function checkoutSelected(Request $request)
    {
        $raw = $request->input('selected_ids', '');
        $ids = array_filter(array_map('intval', explode(',', $raw)));

        if (empty($ids)) {
            return back()->with('error', 'Please select at least one item to checkout.');
        }

        // Validate ownership — keep only IDs belonging to this user
        $validIds = CartItem::where('user_id', auth('web')->id())
            ->whereIn('id', $ids)
            ->pluck('id')
            ->toArray();

        if (empty($validIds)) {
            return back()->with('error', 'No valid items selected.');
        }

        session(['checkout_selected_ids' => $validIds]);
        session()->forget('buynow_product_id');

        return redirect()->route('checkout');
    }

    private function authorizeItem(CartItem $cartItem): void
    {
        abort_unless($cartItem->user_id === auth('web')->id(), 403);
    }
}
