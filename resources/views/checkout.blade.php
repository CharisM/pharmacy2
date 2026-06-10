@extends('layouts.app')
@section('title', 'Checkout – Healthcare Pharmacy')

@push('styles')
<style>
    .co-wrap {
        max-width: 720px;
        margin: 32px auto;
        padding: 0 24px 60px;
    }

    .back-link {
        display: inline-flex; align-items: center; gap: 6px;
        color: #475569; font-size: 14px; font-weight: 600;
        text-decoration: none; margin-bottom: 18px;
        transition: color 150ms;
    }
    .back-link:hover { color: #16a34a; }

    .co-page-title { font-size: clamp(22px,3vw,30px); font-weight: 900; margin: 0 0 2px; }
    .co-page-sub   { color: #475569; font-size: 14px; margin: 0 0 22px; }

    .alert-error {
        padding: 12px 16px; border-radius: 10px; margin-bottom: 16px;
        background: #fff1f2; color: #be123c; border: 1px solid #fda4af;
        font-weight: 700; font-size: 14px;
    }

    /* ── Shared card ── */
    .co-card {
        background: #fff;
        border: 1px solid rgba(15,23,42,.08);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 14px;
    }
    .co-card-head {
        padding: 15px 20px;
        border-bottom: 1px solid rgba(15,23,42,.06);
        display: flex; align-items: center; gap: 10px;
    }
    .co-card-head h2 { margin: 0; font-size: 15px; font-weight: 800; color: #0f172a; }
    .co-card-body { padding: 18px 20px; }

    /* ── Order items ── */
    .co-item-row {
        display: grid;
        grid-template-columns: 52px 1fr auto;
        gap: 12px; align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid rgba(15,23,42,.05);
    }
    .co-item-row:last-child { border-bottom: none; }
    .co-item-thumb {
        width: 52px; height: 52px; border-radius: 8px;
        overflow: hidden; background: #e7fbef;
        display: grid; place-items: center;
        border: 1px solid rgba(15,23,42,.07); flex-shrink: 0;
    }
    .co-item-thumb img { width:100%; height:100%; object-fit:cover; }
    .co-item-cat  { font-size: 10px; color: #16a34a; text-transform: uppercase; letter-spacing: .1em; font-weight: 700; }
    .co-item-name { font-weight: 800; font-size: 14px; color: #0f172a; line-height:1.3; }
    .co-item-qty  { font-size: 12px; color: #64748b; margin-top: 2px; }
    .co-item-price { font-weight: 800; font-size: 15px; color: #0f172a; white-space: nowrap; }

    /* ── Delivery form ── */
    .form-field { margin-bottom: 13px; }
    .form-field label { display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 5px; }
    .form-field input,
    .form-field select,
    .form-field textarea {
        width: 100%; box-sizing: border-box;
        padding: 10px 13px; border-radius: 9px;
        border: 1px solid rgba(15,23,42,.12); background: #f8fafc;
        font-size: 14px; outline: none; font-family: inherit;
        transition: border-color 150ms;
    }
    .form-field input:focus, .form-field select:focus, .form-field textarea:focus {
        border-color: #16a34a; background: #fff;
    }
    .form-field textarea { min-height: 76px; resize: vertical; }
    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

    /* ── Payment method ── */
    .pay-options { display: flex; flex-direction: column; gap: 9px; }
    .pay-option {
        display: flex; align-items: center; gap: 13px;
        padding: 12px 15px; border-radius: 10px;
        border: 1.5px solid rgba(15,23,42,.1); cursor: pointer;
        transition: border-color 150ms, background 150ms;
        background: #f8fafc;
    }
    .pay-option:has(input:checked) {
        border-color: #16a34a; background: #f0fdf4;
    }
    .pay-option input[type=radio] { accent-color: #16a34a; width:16px; height:16px; flex-shrink:0; }
    .pay-option-icon { font-size: 20px; line-height: 1; }
    .pay-option-label { font-weight: 700; font-size: 14px; color: #0f172a; }
    .pay-option-sub   { font-size: 12px; color: #64748b; margin-top: 1px; }

    /* ── Order summary ── */
    .co-summary-row {
        display: flex; justify-content: space-between; align-items: center;
        font-size: 14px; padding: 8px 0;
        border-bottom: 1px solid rgba(15,23,42,.05);
        color: #475569;
    }
    .co-summary-row:last-of-type { border-bottom: none; }
    .co-summary-row strong { color: #0f172a; font-weight: 700; }
    .co-summary-total {
        display: flex; justify-content: space-between; align-items: center;
        margin-top: 12px; padding-top: 14px;
        border-top: 2px solid rgba(15,23,42,.08);
        font-weight: 900; font-size: 17px;
    }
    .co-summary-total .total-amt { color: #16a34a; font-size: 22px; }

    /* ── Place order button ── */
    .btn-place-order {
        display: block; width: 100%; padding: 15px;
        border-radius: 10px;
        background: #16a34a; color: #fff;
        font-size: 16px; font-weight: 800; border: none;
        cursor: pointer; transition: background 150ms; font-family: inherit;
        text-align: center; margin-top: 6px;
    }
    .btn-place-order:hover { background: #047857; }

    .co-secure-note {
        text-align: center; font-size: 12px; color: #94a3b8;
        margin-top: 10px; display: flex; align-items: center; justify-content: center; gap: 5px;
    }

    @media (max-width: 540px) {
        .form-grid-2 { grid-template-columns: 1fr; }
        .co-wrap { padding: 0 14px 60px; }
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
        $orderItems  = !empty($selectedIds)
            ? CartItem::with('product')
                ->where('user_id', auth()->id())
                ->whereIn('id', $selectedIds)
                ->get()
                ->filter(fn($i) => $i->product)
            : collect();
        $buyNowProduct = null;
    }

    $total     = $orderItems->reduce(fn($s, $i) => $s + $i->product->price * $i->quantity, 0);
    $itemCount = $orderItems->sum('quantity');
@endphp

<div class="co-wrap">

    <a href="{{ route('cart') }}" class="back-link">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        Back to Cart
    </a>

    <h1 class="co-page-title">Checkout</h1>
    <p class="co-page-sub">
        {{ $buyNowProduct ? 'Direct purchase — review your order below.' : "Review your {$itemCount} selected item(s) and complete your order." }}
    </p>

    @if(session('error'))
        <div class="alert-error">⚠️ {{ session('error') }}</div>
    @endif

    @if ($orderItems->isEmpty())
        <div class="co-card">
            <div class="co-card-body" style="text-align:center;padding:48px 24px;">
                <h2 style="margin:0 0 10px;">No items selected</h2>
                <p style="color:#475569;margin:0 0 20px;">Go back to your cart and select the items you want to purchase.</p>
                <a href="{{ route('cart') }}" class="btn-place-order" style="display:inline-block;width:auto;padding:12px 28px;text-decoration:none;border-radius:999px;">
                    ← Back to Cart
                </a>
            </div>
        </div>
    @else

    <form method="POST" action="{{ route('orders.store') }}" id="checkoutForm">
    @csrf

        {{-- 1. Order Items --}}
        <div class="co-card">
            <div class="co-card-head">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                <h2>Order Items ({{ $itemCount }})</h2>
            </div>
            <div class="co-card-body">
                @foreach ($orderItems as $item)
                <div class="co-item-row">
                    <div class="co-item-thumb">
                        @if($item->product->image_url)
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                        @else
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#047857" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="4"/><path d="M12 8v8M8 12h8"/></svg>
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
            </div>
        </div>

        {{-- 2. Delivery Details --}}
        <div class="co-card">
            <div class="co-card-head">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <h2>Delivery Details</h2>
            </div>
            <div class="co-card-body">
                <div class="form-grid-2">
                    <div>
                        <div class="form-field">
                            <label>Name *</label>
                            <input type="text" name="first_name"
                                   value="{{ old('first_name', auth()->user()->name) }}"
                                   required placeholder="Juan Dela Cruz">
                        </div>
                        <div class="form-field">
                            <label>Phone Number *</label>
                            <input type="tel" name="phone"
                                   value="{{ old('phone') }}"
                                   required placeholder="+63 9XX XXX XXXX">
                        </div>
                    </div>
                    <div>
                        <div class="form-field">
                            <label>Complete Address *</label>
                            <input type="text" name="address"
                                   value="{{ old('address') }}"
                                   required placeholder="House No., Street, Barangay, City, Province">
                        </div>
                        <div class="form-field">
                            <label>Order Notes <span style="font-weight:400;color:#94a3b8;">(optional)</span></label>
                            <textarea name="notes" placeholder="Special instructions, preferred delivery time, etc.">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Payment Method --}}
        <div class="co-card">
            <div class="co-card-head">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                <h2>Payment Method</h2>
            </div>
            <div class="co-card-body">
                <div class="pay-options">
                    <label class="pay-option">
                        <input type="radio" name="payment_method" value="cod"
                               {{ old('payment_method', 'cod') == 'cod' ? 'checked' : '' }}>
                        <span class="pay-option-icon">💵</span>
                        <div>
                            <div class="pay-option-label">Cash on Delivery</div>
                            <div class="pay-option-sub">Pay when your order arrives</div>
                        </div>
                    </label>
                    <label class="pay-option">
                        <input type="radio" name="payment_method" value="gcash"
                               {{ old('payment_method') == 'gcash' ? 'checked' : '' }}>
                        <span class="pay-option-icon">📱</span>
                        <div>
                            <div class="pay-option-label">GCash</div>
                            <div class="pay-option-sub">Pay via GCash mobile wallet</div>
                        </div>
                    </label>

                </div>
            </div>
        </div>

        {{-- 4. Order Summary + Place Order --}}
        <div class="co-card">
            <div class="co-card-head">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                <h2>Order Summary</h2>
            </div>
            <div class="co-card-body">
                <div class="co-summary-row">
                    <span>Subtotal ({{ $itemCount }} item{{ $itemCount > 1 ? 's' : '' }})</span>
                    <strong>₱{{ number_format($total, 2) }}</strong>
                </div>
                <div class="co-summary-row">
                    <span>Shipping Fee</span>
                    <strong style="color:#16a34a;">FREE</strong>
                </div>
                <div class="co-summary-total">
                    <span>Total Payment</span>
                    <span class="total-amt">₱{{ number_format($total, 2) }}</span>
                </div>

                <button type="submit" class="btn-place-order">
                    Place Order — ₱{{ number_format($total, 2) }}
                </button>

                <p class="co-secure-note">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    Secure checkout — your information is protected
                </p>
            </div>
        </div>

    </form>
    @endif

</div>
@endsection
