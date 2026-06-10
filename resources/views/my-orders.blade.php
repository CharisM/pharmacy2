@extends('layouts.app')
@section('title', 'My Orders – Healthcare Pharmacy')

@push('styles')
<style>
    .mo-wrap { max-width: 860px; margin: 32px auto; padding: 0 24px 60px; }

    .mo-page-title { font-size: clamp(22px,3vw,30px); font-weight: 900; margin: 0 0 2px; }
    .mo-page-sub   { color: #475569; font-size: 14px; margin: 0 0 24px; }

    .mo-card {
        background: #fff;
        border: 1px solid rgba(15,23,42,.08);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 16px;
        transition: box-shadow 180ms;
    }
    .mo-card:hover { box-shadow: 0 8px 28px rgba(15,23,42,.09); }

    .mo-card-head {
        padding: 14px 20px;
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;
        border-bottom: 1px solid rgba(15,23,42,.06);
        background: #f8fafc;
    }
    .mo-order-id   { font-size: 13px; font-weight: 800; color: #6366f1; }
    .mo-order-date { font-size: 12px; color: #94a3b8; font-weight: 600; }

    .mo-card-body { padding: 16px 20px; }

    .mo-item-row {
        display: grid;
        grid-template-columns: 44px 1fr auto;
        gap: 12px; align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid rgba(15,23,42,.04);
    }
    .mo-item-row:last-child { border-bottom: none; }
    .mo-thumb {
        width: 44px; height: 44px; border-radius: 8px;
        overflow: hidden; background: #e7fbef;
        display: grid; place-items: center;
        border: 1px solid rgba(15,23,42,.07); flex-shrink: 0;
    }
    .mo-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .mo-item-cat  { font-size: 10px; color: #16a34a; text-transform: uppercase; letter-spacing: .1em; font-weight: 700; }
    .mo-item-name { font-weight: 700; font-size: 13px; color: #0f172a; line-height: 1.3; }
    .mo-item-qty  { font-size: 12px; color: #64748b; margin-top: 1px; }
    .mo-item-price { font-weight: 800; font-size: 13px; color: #0f172a; white-space: nowrap; }

    .mo-card-foot {
        padding: 12px 20px;
        border-top: 1px solid rgba(15,23,42,.06);
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;
        background: #fafafa;
    }
    .mo-meta { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

    .mo-badge {
        display: inline-block; padding: 3px 12px;
        border-radius: 999px; font-size: 11px; font-weight: 800; text-transform: uppercase;
    }
    .pay-cod   { background: #fef3c7; color: #92400e; }
    .pay-gcash { background: #dbeafe; color: #1d4ed8; }

    .status-pending    { background: #fef3c7; color: #92400e; }
    .status-processing { background: #eef2ff; color: #4338ca; }
    .status-shipped    { background: #e0f2fe; color: #0369a1; }
    .status-delivered  { background: #dcfce7; color: #166534; }
    .status-cancelled  { background: #fee2e2; color: #991b1b; }

    .mo-total { font-weight: 900; font-size: 16px; color: #16a34a; }

    .mo-empty {
        text-align: center; padding: 60px 24px;
        background: #fff; border-radius: 16px;
        border: 1px solid rgba(15,23,42,.08);
    }
    .mo-empty p { color: #475569; font-size: 15px; margin: 10px 0 22px; }
    .btn-shop {
        display: inline-block; padding: 12px 28px;
        background: #16a34a; color: #fff; border-radius: 10px;
        font-weight: 800; font-size: 14px; text-decoration: none;
        transition: background 150ms;
    }
    .btn-shop:hover { background: #047857; }
</style>
@endpush

@section('content')
<div class="mo-wrap">

    <h1 class="mo-page-title">My Orders</h1>
    <p class="mo-page-sub">Your complete order history, newest first.</p>

    @forelse ($orders as $order)
    <div class="mo-card">

        {{-- Header --}}
        <div class="mo-card-head">
            <span class="mo-order-id">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
            <span class="mo-order-date">{{ $order->created_at->format('M d, Y · h:i A') }}</span>
        </div>

        {{-- Items --}}
        <div class="mo-card-body">
            @foreach ($order->items as $item)
            <div class="mo-item-row">
                <div class="mo-thumb">
                    @if($item->product && $item->product->image_url)
                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                    @else
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#047857" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="4"/><path d="M12 8v8M8 12h8"/></svg>
                    @endif
                </div>
                <div>
                    @if($item->product)
                        <div class="mo-item-cat">{{ $item->product->category }}</div>
                        <div class="mo-item-name">{{ $item->product->name }}</div>
                    @else
                        <div class="mo-item-name" style="color:#94a3b8;">Product no longer available</div>
                    @endif
                    <div class="mo-item-qty">Qty: {{ $item->quantity }} × ₱{{ number_format($item->price, 2) }}</div>
                </div>
                <div class="mo-item-price">₱{{ number_format($item->price * $item->quantity, 2) }}</div>
            </div>
            @endforeach
        </div>

        {{-- Footer --}}
        <div class="mo-card-foot">
            <div class="mo-meta">
                <span class="mo-badge pay-{{ strtolower($order->payment_method) }}">
                    {{ strtoupper($order->payment_method) }}
                </span>
                <span class="mo-badge status-{{ strtolower($order->status) }}">
                    {{ $order->status }}
                </span>
            </div>
            <span class="mo-total">₱{{ number_format($order->total, 2) }}</span>
        </div>

    </div>
    @empty
    <div class="mo-empty">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" style="margin:0 auto;display:block;"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        <p>You haven't placed any orders yet.</p>
        <a href="{{ route('categories') }}" class="btn-shop">Start Shopping</a>
    </div>
    @endforelse

</div>
@endsection
