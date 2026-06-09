@extends('layouts.app')
@section('title', 'Checkout – Healthcare Pharmacy')

@push('styles')
<style>
    .checkout-wrap { max-width: 860px; margin: 32px auto; padding: 0 24px; }
    .checkout-title { font-size: clamp(28px,3vw,40px); margin: 0 0 6px; }
    .checkout-subtitle { color:#475569; margin:0 0 28px; }

    .checkout-card {
        background:#fff; border:1px solid rgba(15,23,42,.08);
        border-radius:18px; padding:28px; margin-bottom:20px;
    }
    .checkout-card h2 { margin:0 0 18px; font-size:20px; }

    .order-row {
        display:grid; grid-template-columns:60px 1fr auto;
        gap:14px; align-items:center; padding:14px 0;
        border-bottom:1px solid rgba(15,23,42,.06);
    }
    .order-row:last-child { border-bottom:none; }
    .order-thumb {
        width:60px; height:60px; border-radius:10px;
        background:#e7fbef; overflow:hidden; display:grid; place-items:center;
    }
    .order-thumb img { width:100%;height:100%;object-fit:cover; }
    .order-name { font-weight:700; }
    .order-cat  { font-size:13px; color:#16a34a; text-transform:uppercase; letter-spacing:.08em; }
    .order-qty  { font-size:13px; color:#475569; }
    .order-price { font-weight:800; font-size:17px; }

    .order-total {
        display:flex; justify-content:space-between;
        font-size:18px; font-weight:800; padding-top:16px;
        border-top:1px solid rgba(15,23,42,.08); margin-top:8px;
    }

    .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
    .form-group { display:grid; gap:6px; }
    .form-group.full { grid-column:1/-1; }
    .form-group label { font-size:13px; font-weight:700; color:#475569; }
    .form-group input, .form-group select, .form-group textarea {
        padding:12px 14px; border-radius:12px;
        border:1px solid rgba(15,23,42,.12); background:#f8fafc;
        font-size:14px; outline:none; font-family:inherit;
        transition: border-color 150ms;
    }
    .form-group input:focus, .form-group select:focus {
        border-color:#16a34a; background:#fff;
    }
    .form-group textarea { min-height:80px; resize:vertical; }

    .btn-place-order {
        width:100%; padding:15px; border-radius:999px;
        background:#16a34a; color:#fff;
        font-size:16px; font-weight:800; border:none;
        cursor:pointer; transition:background 150ms; font-family:inherit;
    }
    .btn-place-order:hover { background:#047857; }

    @media(max-width:560px){ .form-grid{ grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
@php
    $buyNowProductId = session('buynow_product_id');
    $buyNowProduct   = $buyNowProductId ? \App\Models\Product::find($buyNowProductId) : null;

    if ($buyNowProduct) {
        $orderItems = collect([
            (object)[
                'product'  => $buyNowProduct,
                'quantity' => 1,
            ]
        ]);
    } else {
        $orderItems = \App\Models\CartItem::with('product')
            ->where('user_id', auth()->id())
            ->get()
            ->filter(fn($i) => $i->product);
    }

    $total = $orderItems->reduce(fn($s, $i) => $s + $i->product->price * $i->quantity, 0);
@endphp

<div class="checkout-wrap">
    <h1 class="checkout-title">Checkout</h1>
    <p class="checkout-subtitle">
        {{ $buyNowProduct ? 'Direct purchase — review your order below.' : 'Review your cart and complete your order.' }}
    </p>

    @if ($orderItems->isEmpty())
        <div style="background:#fff;border:1px solid rgba(15,23,42,.08);border-radius:18px;padding:48px;text-align:center;">
            <h2 style="margin:0 0 12px;">Nothing to check out</h2>
            <p style="color:#475569;margin:0 0 20px;">Your cart is empty. Add some products first.</p>
            <a href="{{ route('categories') }}" style="padding:12px 28px;border-radius:999px;background:#16a34a;color:#fff;text-decoration:none;font-weight:700;">Browse Products</a>
        </div>
    @else
        {{-- Order summary --}}
        <div class="checkout-card">
            <h2>Order Summary</h2>
            @foreach ($orderItems as $item)
            <div class="order-row">
                <div class="order-thumb">
                    @if ($item->product->image_url)
                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                    @else
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#047857" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="4"/><path d="M12 8v8M8 12h8"/></svg>
                    @endif
                </div>
                <div>
                    <div class="order-cat">{{ $item->product->category }}</div>
                    <div class="order-name">{{ $item->product->name }}</div>
                    <div class="order-qty">Qty: {{ $item->quantity }}</div>
                </div>
                <div class="order-price">₱{{ number_format($item->product->price * $item->quantity, 2) }}</div>
            </div>
            @endforeach
            <div class="order-total">
                <span>Total</span>
                <span>₱{{ number_format($total, 2) }}</span>
            </div>
        </div>

        {{-- Delivery & payment form --}}
        <div class="checkout-card">
            <h2>Delivery Details</h2>
            <form method="POST" action="#">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" value="{{ auth()->user()->name }}" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" required>
                    </div>
                    <div class="form-group full">
                        <label>Address</label>
                        <input type="text" name="address" placeholder="Street, Barangay, City" required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="tel" name="phone" placeholder="+63 9XX XXX XXXX" required>
                    </div>
                    <div class="form-group">
                        <label>Payment Method</label>
                        <select name="payment_method">
                            <option value="cod">Cash on Delivery</option>
                            <option value="gcash">GCash</option>
                            <option value="card">Credit / Debit Card</option>
                        </select>
                    </div>
                    <div class="form-group full">
                        <label>Order Notes (optional)</label>
                        <textarea name="notes" placeholder="Any special instructions..."></textarea>
                    </div>
                </div>
                <div style="margin-top:20px;">
                    <button type="submit" class="btn-place-order">Place Order — ₱{{ number_format($total, 2) }}</button>
                </div>
            </form>
        </div>
    @endif
</div>
@endsection
