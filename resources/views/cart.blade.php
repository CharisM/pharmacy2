@extends('layouts.app')
@section('title', 'Your Cart – Healthcare Pharmacy')

@push('styles')
<style>
    .cart-wrap { max-width: 1100px; margin: 32px auto; padding: 0 24px; }
    .cart-title { font-size: clamp(28px,3vw,40px); margin: 0 0 6px; }
    .cart-subtitle { color: #475569; margin: 0 0 24px; }

    .cart-empty {
        background: #fff; border: 1px solid rgba(15,23,42,.08);
        border-radius: 18px; padding: 48px; text-align: center;
    }

    .cart-table {
        background: #fff; border: 1px solid rgba(15,23,42,.08);
        border-radius: 18px; overflow: hidden; margin-bottom: 20px;
    }

    .cart-row {
        display: grid;
        grid-template-columns: 80px 1fr auto auto;
        gap: 16px; align-items: center;
        padding: 18px 24px;
        border-bottom: 1px solid rgba(15,23,42,.06);
    }
    .cart-row:last-child { border-bottom: none; }

    .cart-thumb {
        width: 80px; height: 80px; border-radius: 12px;
        overflow: hidden; background: #e7fbef;
        display: grid; place-items: center; flex-shrink: 0;
    }
    .cart-thumb img { width:100%; height:100%; object-fit:cover; }
    .cart-thumb-placeholder { color:#047857; font-size:12px; font-weight:700; }

    .cart-item-name  { font-weight: 800; font-size: 16px; margin: 0 0 4px; }
    .cart-item-cat   { font-size: 13px; color: #16a34a; text-transform: uppercase; letter-spacing:.08em; margin-bottom:4px; }
    .cart-item-price { font-size: 13px; color: #475569; }

    .qty-form { display: flex; align-items: center; gap: 6px; }
    .qty-btn {
        width:30px; height:30px; border-radius:8px;
        border:1px solid rgba(15,23,42,.12); background:#f8fafc;
        font-size:18px; cursor:pointer; display:grid; place-items:center;
        line-height:1; transition: background 150ms;
    }
    .qty-btn:hover { background:#e7fbef; }
    .qty-val { width: 36px; text-align:center; font-weight:700; font-size:15px; }

    .cart-item-subtotal { font-weight:800; font-size:17px; white-space:nowrap; }

    .btn-remove {
        background: none; border: none; cursor: pointer;
        color: #ef4444; padding: 4px; border-radius:6px;
        transition: background 150ms;
    }
    .btn-remove:hover { background: #fee2e2; }

    .cart-summary {
        background:#fff; border:1px solid rgba(15,23,42,.08);
        border-radius:18px; padding:24px;
        display:flex; justify-content:space-between; align-items:center;
        flex-wrap:wrap; gap:16px;
    }
    .cart-total-label { color:#475569; font-size:15px; }
    .cart-total-amount { font-size:24px; font-weight:900; color:#0f172a; }

    .cart-actions { display:flex; gap:12px; flex-wrap:wrap; align-items:center; }

    .btn-clear {
        padding:12px 20px; border-radius:999px; border:1.5px solid #ef4444;
        color:#ef4444; background:transparent; font-weight:700; cursor:pointer;
        transition: background 150ms;
    }
    .btn-clear:hover { background:#fee2e2; }

    .btn-checkout {
        padding:12px 28px; border-radius:999px;
        background:#16a34a; color:#fff;
        text-decoration:none; font-weight:700;
        transition: background 150ms;
    }
    .btn-checkout:hover { background:#047857; }

    @media(max-width:640px){
        .cart-row { grid-template-columns: 60px 1fr; }
        .cart-item-subtotal { grid-column: 2; }
        .qty-form { grid-column: 2; }
        .btn-remove { grid-column: 1; grid-row: 1; }
    }
</style>
@endpush

@section('content')
<div class="cart-wrap">
    <h1 class="cart-title">Your Cart</h1>
    <p class="cart-subtitle">Review your items and proceed to checkout.</p>

    @if ($cartItems->isEmpty())
        <div class="cart-empty">
            <h2 style="margin:0 0 10px;">Your cart is empty</h2>
            <p style="color:#475569;margin:0 0 20px;">Add products and come back here to review them.</p>
            <a href="{{ route('categories') }}" class="btn-checkout">Browse products</a>
        </div>
    @else
        @php
            $total = $cartItems->reduce(fn($sum,$item) =>
                $sum + ($item->product ? $item->product->price * $item->quantity : 0), 0);
        @endphp

        <div class="cart-table">
            @foreach ($cartItems as $item)
                @if ($item->product)
                <div class="cart-row">
                    {{-- Thumb --}}
                    <div class="cart-thumb">
                        @if ($item->product->image_url)
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                        @else
                            <span class="cart-thumb-placeholder">No img</span>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div>
                        <div class="cart-item-cat">{{ $item->product->category }}</div>
                        <p class="cart-item-name">{{ $item->product->name }}</p>
                        <span class="cart-item-price">₱{{ number_format($item->product->price, 2) }} each</span>
                    </div>

                    {{-- Qty --}}
                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="qty-form">
                        @csrf @method('PATCH')
                        <button type="submit" name="quantity" value="{{ $item->quantity - 1 }}" class="qty-btn">−</button>
                        <span class="qty-val">{{ $item->quantity }}</span>
                        <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" class="qty-btn">+</button>
                    </form>

                    {{-- Subtotal + remove --}}
                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px;">
                        <span class="cart-item-subtotal">₱{{ number_format($item->product->price * $item->quantity, 2) }}</span>
                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-remove" title="Remove">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            @endforeach
        </div>

        <div class="cart-summary">
            <div>
                <div class="cart-total-label">{{ $cartItems->sum('quantity') }} item(s) — Total</div>
                <div class="cart-total-amount">₱{{ number_format($total, 2) }}</div>
            </div>
            <div class="cart-actions">
                <form action="{{ route('cart.clear') }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-clear">Clear Cart</button>
                </form>
                <a href="{{ route('checkout') }}" class="btn-checkout">Proceed to Checkout →</a>
            </div>
        </div>
    @endif
</div>
@endsection
