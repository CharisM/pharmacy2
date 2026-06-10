@extends('layouts.app')
@section('title', 'Checkout – Healthcare Pharmacy')

@push('styles')
<style>
    .checkout-wrap { max-width: 960px; margin: 32px auto; padding: 0 24px 48px; }
    .checkout-title { font-size: clamp(24px,3vw,36px); margin: 0 0 4px; font-weight: 900; }
    .checkout-subtitle { color: #475569; margin: 0 0 28px; font-size: 14px; }

    .co-grid { display: grid; grid-template-columns: 1fr 380px; gap: 22px; align-items: start; }

    .co-card {
        background: #fff; border: 1px solid rgba(15,23,42,.08);
        border-radius: 18px; overflow: hidden;
    }
    .co-card-head {
        padding: 18px 22px; border-bottom: 1px solid rgba(15,23,42,.06);
        display: flex; align-items: center; gap: 10px;
    }
    .co-card-head h2 { margin: 0; font-size: 17px; font-weight: 800; }
    .co-card-body { padding: 20px 22px; }

    /* Order item rows */
    .co-item-row {
        display: grid;
        grid-template-columns: 54px 1fr auto;
        gap: 12px; align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid rgba(15,23,42,.05);
    }
    .co-item-row:last-child { border-bottom: none; }
    .co-item-thumb {
        width: 54px; height: 54px; border-radius: 8px;
        overflow: hidden; background: #e7fbef;
        display: grid; place-items: center;
        border: 1px solid rgba(15,23,42,.07); flex-shrink: 0;
    }
    .co-item-thumb img { width:100%; height:100%; object-fit:cover; }
    .co-item-cat  { font-size: 11px; color: #16a34a; text-transform: uppercase; letter-spacing: .1em; font-weight: 700; }
    .co-item-name { font-weight: 800; font-size: 14px; color: #0f172a; line-height:1.3; }
    .co-item-qty  { font-size: 12px; color: #64748b; margin-top: 2px; }
    .co-item-price { font-weight: 800; font-size: 15px; color: #0f172a; white-space: nowrap; }

    .co-total-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 14px 0 0; margin-top: 6px;
        border-top: 1px solid rgba(15,23,42,.08);
        font-weight: 800; font-size: 17px;
    }
    .co-total-row .amt { color: #16a34a; font-size: 20px; }

    /* Delivery form */
    .form-field { margin-bottom: 14px; }
    .form-field label { display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 5px; }
    .form-field input,
    .form-field select,
    .form-field textarea {
        width: 100%; box-sizing: border-box;
        padding: 11px 14px; border-radius: 10px;
        border: 1px solid rgba(15,23,42,.12); background: #f8fafc;
        font-size: 14px; outline: none; font-family: inherit;
        transition: border-color 150ms;
    }
    .form-field input:focus, .form-field select:focus, .form-field textarea:focus {
        border-color: #16a34a; background: #fff;
    }
    .form-field textarea { min-height: 80px; resize: vertical; }
    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

    /* Summary card */
    .co-summary-line {
        display: flex; justify-content: space-between;
        font-size: 14px; padding: 7px 0;
        border-bottom: 1px solid rgba(15,23,42,.05);
        color: #475569;
    }
    .co-summary-line:last-of-type { border-bottom: none; }
    .co-summary-line strong { color: #0f172a; font-weight: 800; }
    .co-summary-total {
        display: flex; justify-content: space-between; align-items: center;
        margin-top: 14px; padding-top: 14px;
        border-top: 2px solid rgba(15,23,42,.08);
        font-size: 18px; font-weight: 900;
    }
    .co-summary-total .total-amt { color: #16a34a; font-size: 22px; }

    .btn-place-order {
        width: 100%; padding: 14px; border-radius: 999px;
        background: #16a34a; color: #fff;
        font-size: 16px; font-weight: 800; border: none;
        cursor: pointer; transition: background 150ms; font-family: inherit;
        margin-top: 16px;
    }
    .btn-place-order:hover { background: #047857; }

    .back-link {
        display: inline-flex; align-items: center; gap: 6px;
        color: #475569; font-size: 14px; font-weight: 600;
        text-decoration: none; margin-bottom: 20px;
        transition: color 150ms;
    }
    .back-link:hover { color: #16a34a; }

    .alert-error {
        padding: 12px 16px; border-radius: 10px; margin-bottom: 16px;
        background: #fff1f2; color: #be123c; border: 1px solid #fda4af;
        font-weight: 700; font-size: 14px;
    }

    @media (max-width: 760px) {
        .co-grid { grid-template-columns: 1fr; }
        .form-grid-2 { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
@php
    use App\Models\CartItem;
    use App\Models\Product;

    $buyNowProductId = session('buynow_product_id');

    if ($buyNowProductId) {
        $buyNowProduct = Product::find($buyNowProductId);
        $orderItems = $buyNowProduct
            ? collect([(object)['product' => $buyNowProduct, 'quantity' => 1, 'id' => null]])
            : collect();
    } else {
        $selectedIds = session('checkout_selected_ids', []);
        if (!empty($selectedIds)) {
            $orderItems = CartItem::with('product')
                ->where('user_id', auth()->id())
                ->whereIn('id', $selectedIds)
                ->get()
                ->filter(fn($i) => $i->product);
        } else {
            $orderItems = collect();
        }
        $buyNowProduct = null;
    }

    $total    = $orderItems->reduce(fn($s, $i) => $s + $i->product->price * $i->quantity, 0);
    $itemCount = $orderItems->sum('quantity');
@endphp

<div class="checkout-wrap">
    <a href="{{ route('cart') }}" class="back-link">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        Back to Cart
    </a>

    <h1 class="checkout-title">Checkout</h1>
    <p class="checkout-subtitle">
        {{ $buyNowProduct ? 'Direct purchase — review your order below.' : "Review your {$itemCount} selected item(s) and complete your order." }}
    </p>

    @if(session('error'))
        <div class="alert-error">⚠️ {{ session('error') }}</div>
    @endif

    @if ($orderItems->isEmpty())
        <div style="background:#fff;border:1px solid rgba(15,23,42,.08);border-radius:18px;padding:56px 32px;text-align:center;">
            <h2 style="margin:0 0 12px;">No items selected</h2>
            <p style="color:#475569;margin:0 0 20px;">Go back to your cart and select the items you want to purchase.</p>
            <a href="{{ route('cart') }}" class="btn-place-order" style="display:inline-block;width:auto;padding:12px 28px;text-decoration:none;border-radius:999px;">
                ← Back to Cart
            </a>
        </div>
    @else
        <div class="co-grid">

            {{-- Left: Order Items + Delivery Form --}}
            <div>
                {{-- Order Items --}}
                <div class="co-card" style="margin-bottom:18px;">
                    <div class="co-card-head">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><path d="M9 17H5a2 2 0 0 0-2 2v0a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v0a2 2 0 0 0-2-2h-4"/><path d="M12 3v14"/><path d="M8 7l4-4 4 4"/></svg>
                        <h2>Order Items ({{ $itemCount }})</h2>
                    </div>
                    <div class="co-card-body">
                        @foreach ($orderItems as $item)
                        <div class="co-item-row">
                            <div class="co-item-thumb">
                                @if($item->product->image_url)
                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                                @else
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#047857" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="4"/><path d="M12 8v8M8 12h8"/></svg>
                                @endif
                            </div>
                            <div>
                                <div class="co-item-cat">{{ $item->product->category }}</div>
                                <div class="co-item-name">{{ $item->product->name }}</div>
                                <div class="co-item-qty">Qty: {{ $item->quantity }} × ₱{{ number_format($item->product->price, 2) }}</div>
                            </div>
                            <div class="co-item-price">₱{{ number_format($item->product->price * $item->quantity, 2) }}</div>
                        </div>
                        @endforeach
                        <div class="co-total-row">
                            <span>Items Total</span>
                            <span class="amt">₱{{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Delivery Form --}}
                <div class="co-card">
                    <div class="co-card-head">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <h2>Delivery Details</h2>
                    </div>
                    <div class="co-card-body">
                        <form method="POST" action="{{ route('orders.store') }}" id="checkoutForm">
                            @csrf
                            <div class="form-grid-2">
                                <div class="form-field">
                                    <label>First Name *</label>
                                    <input type="text" name="first_name"
                                           value="{{ old('first_name', explode(' ', auth()->user()->name)[0]) }}"
                                           required placeholder="Juan">
                                </div>
                                <div class="form-field">
                                    <label>Last Name *</label>
                                    <input type="text" name="last_name"
                                           value="{{ old('last_name', implode(' ', array_slice(explode(' ', auth()->user()->name), 1))) }}"
                                           required placeholder="Dela Cruz">
                                </div>
                            </div>
                            <div class="form-field">
                                <label>Complete Address *</label>
                                <input type="text" name="address"
                                       value="{{ old('address') }}"
                                       required placeholder="House No., Street, Barangay, City, Province">
                            </div>
                            <div class="form-grid-2">
                                <div class="form-field">
                                    <label>Phone Number *</label>
                                    <input type="tel" name="phone"
                                           value="{{ old('phone') }}"
                                           required placeholder="+63 9XX XXX XXXX">
                                </div>
                                <div class="form-field">
                                    <label>Payment Method *</label>
                                    <select name="payment_method">
                                        <option value="cod"  {{ old('payment_method') == 'cod'   ? 'selected' : '' }}>💵 Cash on Delivery</option>
                                        <option value="gcash" {{ old('payment_method') == 'gcash' ? 'selected' : '' }}>📱 GCash</option>
                                        <option value="card" {{ old('payment_method') == 'card'  ? 'selected' : '' }}>💳 Credit / Debit Card</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-field">
                                <label>Order Notes <span style="font-weight:400;color:#94a3b8;">(optional)</span></label>
                                <textarea name="notes" placeholder="Special instructions, preferred delivery time, etc.">{{ old('notes') }}</textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Right: Order Summary --}}
            <div>
                <div class="co-card" style="position:sticky;top:90px;">
                    <div class="co-card-head">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        <h2>Order Summary</h2>
                    </div>
                    <div class="co-card-body">
                        <div class="co-summary-line">
                            <span>Items ({{ $itemCount }})</span>
                            <strong>₱{{ number_format($total, 2) }}</strong>
                        </div>
                        <div class="co-summary-line">
                            <span>Shipping</span>
                            <strong style="color:#16a34a;">FREE</strong>
                        </div>
                        <div class="co-summary-total">
                            <span>Total</span>
                            <span class="total-amt">₱{{ number_format($total, 2) }}</span>
                        </div>
                        <button type="submit" form="checkoutForm" class="btn-place-order">
                            Place Order — ₱{{ number_format($total, 2) }}
                        </button>
                        <a href="{{ route('cart') }}" style="display:block;text-align:center;margin-top:12px;font-size:13px;color:#64748b;text-decoration:none;">
                            ← Edit Cart
                        </a>
                    </div>
                </div>
            </div>

        </div>
    @endif
</div>
@endsection
