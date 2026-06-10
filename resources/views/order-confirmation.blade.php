@extends('layouts.app')
@section('title', 'Order Confirmed – Healthcare Pharmacy')

@push('styles')
<style>
    .co-wrap { max-width: 720px; margin: 32px auto; padding: 0 24px 60px; }

    .co-card { background: #fff; border: 1px solid rgba(15,23,42,.08); border-radius: 16px; overflow: hidden; margin-bottom: 14px; }
    .co-card-head { padding: 15px 20px; border-bottom: 1px solid rgba(15,23,42,.06); display: flex; align-items: center; gap: 10px; }
    .co-card-head h2 { margin: 0; font-size: 15px; font-weight: 800; color: #0f172a; }
    .co-card-body { padding: 18px 20px; }

    .confirm-hero {
        text-align: center; padding: 36px 24px 28px;
        background: #fff; border: 1px solid rgba(15,23,42,.08);
        border-radius: 16px; margin-bottom: 14px;
    }
    .confirm-icon {
        width: 68px; height: 68px; background: #f0fdf4; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;
    }
    .confirm-hero h1 { margin: 0 0 6px; font-size: 24px; font-weight: 900; color: #0f172a; }
    .confirm-hero p  { margin: 0; color: #475569; font-size: 14px; }
    .order-id-badge {
        display: inline-block; margin-top: 12px;
        background: #f0fdf4; color: #16a34a;
        border: 1px solid #bbf7d0; border-radius: 999px;
        padding: 5px 16px; font-size: 13px; font-weight: 800;
    }

    .detail-row { display: flex; justify-content: space-between; align-items: flex-start; padding: 9px 0; border-bottom: 1px solid rgba(15,23,42,.05); font-size: 14px; }
    .detail-row:last-child { border-bottom: none; }
    .detail-row span { color: #475569; }
    .detail-row strong { color: #0f172a; font-weight: 700; text-align: right; max-width: 60%; }

    .co-item-row { display: grid; grid-template-columns: 48px 1fr auto; gap: 12px; align-items: center; padding: 10px 0; border-bottom: 1px solid rgba(15,23,42,.05); }
    .co-item-row:last-child { border-bottom: none; }
    .co-item-thumb { width: 48px; height: 48px; border-radius: 8px; overflow: hidden; background: #e7fbef; display: grid; place-items: center; border: 1px solid rgba(15,23,42,.07); flex-shrink: 0; }
    .co-item-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .co-item-cat  { font-size: 10px; color: #16a34a; text-transform: uppercase; letter-spacing: .1em; font-weight: 700; }
    .co-item-name { font-weight: 800; font-size: 14px; color: #0f172a; line-height: 1.3; }
    .co-item-qty  { font-size: 12px; color: #64748b; margin-top: 2px; }
    .co-item-price { font-weight: 800; font-size: 14px; color: #0f172a; white-space: nowrap; }

    .total-row { display: flex; justify-content: space-between; align-items: center; margin-top: 12px; padding-top: 14px; border-top: 2px solid rgba(15,23,42,.08); font-weight: 900; font-size: 17px; }
    .total-row .amt { color: #16a34a; font-size: 22px; }

    .payment-badge { display: inline-block; padding: 3px 12px; border-radius: 999px; font-size: 12px; font-weight: 800; text-transform: uppercase; }
    .pay-cod   { background: #fef3c7; color: #92400e; }
    .pay-gcash { background: #dbeafe; color: #1d4ed8; }

    .status-badge { display: inline-block; padding: 3px 12px; border-radius: 999px; font-size: 12px; font-weight: 800; background: #fef3c7; color: #92400e; }

    .btn-home { display: block; width: 100%; padding: 14px; border-radius: 10px; background: #16a34a; color: #fff; font-size: 15px; font-weight: 800; border: none; cursor: pointer; text-align: center; text-decoration: none; transition: background 150ms; margin-bottom: 10px; }
    .btn-home:hover { background: #047857; }
    .btn-outline-green { display: block; width: 100%; padding: 13px; border-radius: 10px; background: transparent; color: #16a34a; font-size: 15px; font-weight: 800; border: 2px solid #16a34a; text-align: center; text-decoration: none; transition: all 150ms; }
    .btn-outline-green:hover { background: #f0fdf4; }
</style>
@endpush

@section('content')
<div class="co-wrap">

    {{-- Hero --}}
    <div class="confirm-hero">
        <div class="confirm-icon">
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <h1>Order Placed!</h1>
        <p>Your order has been placed successfully. We'll process it shortly.</p>
        <span class="order-id-badge">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
    </div>

    {{-- Delivery Details --}}
    <div class="co-card">
        <div class="co-card-head">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            <h2>Delivery Details</h2>
        </div>
        <div class="co-card-body">
            <div class="detail-row"><span>Full Name</span><strong>{{ $order->full_name }}</strong></div>
            <div class="detail-row"><span>Phone</span><strong>{{ $order->phone }}</strong></div>
            <div class="detail-row"><span>Address</span><strong>{{ $order->address }}</strong></div>
            @if($order->notes)
            <div class="detail-row"><span>Notes</span><strong>{{ $order->notes }}</strong></div>
            @endif
        </div>
    </div>

    {{-- Payment & Status --}}
    <div class="co-card">
        <div class="co-card-head">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
            <h2>Payment & Status</h2>
        </div>
        <div class="co-card-body">
            <div class="detail-row">
                <span>Payment Method</span>
                <strong>
                    <span class="payment-badge pay-{{ strtolower($order->payment_method) }}">
                        {{ strtoupper($order->payment_method) }}
                    </span>
                </strong>
            </div>
            <div class="detail-row">
                <span>Order Status</span>
                <strong><span class="status-badge">{{ $order->status }}</span></strong>
            </div>
            <div class="detail-row">
                <span>Order Date</span>
                <strong>{{ $order->created_at->format('F d, Y h:i A') }}</strong>
            </div>
        </div>
    </div>

    {{-- Items --}}
    <div class="co-card">
        <div class="co-card-head">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            <h2>Items Ordered ({{ $order->items->sum('quantity') }})</h2>
        </div>
        <div class="co-card-body">
            @foreach($order->items as $item)
            <div class="co-item-row">
                <div class="co-item-thumb">
                    @if($item->product && $item->product->image_url)
                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                    @else
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#047857" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="4"/><path d="M12 8v8M8 12h8"/></svg>
                    @endif
                </div>
                <div>
                    @if($item->product)
                        <div class="co-item-cat">{{ $item->product->category }}</div>
                        <div class="co-item-name">{{ $item->product->name }}</div>
                    @else
                        <div class="co-item-name" style="color:#94a3b8;">Product no longer available</div>
                    @endif
                    <div class="co-item-qty">Qty: {{ $item->quantity }} × ₱{{ number_format($item->price, 2) }}</div>
                </div>
                <div class="co-item-price">₱{{ number_format($item->price * $item->quantity, 2) }}</div>
            </div>
            @endforeach

            <div class="total-row">
                <span>Total Paid</span>
                <span class="amt">₱{{ number_format($order->total, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <a href="{{ route('home') }}" class="btn-home">Continue Shopping</a>
    <a href="{{ route('categories') }}" class="btn-outline-green">Browse Categories</a>

</div>
@endsection
