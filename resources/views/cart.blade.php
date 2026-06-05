@extends('layouts.app')
@section('title', 'Your Cart – Healthcare Pharmacy')

@section('content')
<div class="page-header" style="padding: 32px 24px 0; max-width: 1100px; margin: 0 auto;">
    <h1 style="font-size: clamp(32px, 3vw, 44px); margin-bottom: 10px;">Your Cart</h1>
    <p style="color: #475569; margin: 0;">Review the items you added and proceed to checkout.</p>
</div>

<div style="max-width: 1100px; margin: 24px auto; padding: 0 24px;">
    @if ($cartItems->isEmpty())
        <div style="background: #ffffff; border: 1px solid rgba(15, 23, 42, 0.08); border-radius: 18px; padding: 40px; text-align: center;">
            <h2 style="margin: 0 0 10px; font-size: 24px;">Your cart is empty</h2>
            <p style="margin: 0 0 18px; color: #475569;">Add products from the store and come back here to review them.</p>
            <a href="{{ route('home') }}" style="display: inline-flex; padding: 12px 24px; border-radius: 999px; background: #16a34a; color: #ffffff; text-decoration: none; font-weight: 700;">Browse products</a>
        </div>
    @else
        <div style="display: grid; gap: 22px;">
            @php
                $cartTotal = $cartItems->reduce(function ($sum, $item) {
                    if ($item->product) {
                        return $sum + ($item->product->price * $item->quantity);
                    }
                    return $sum;
                }, 0);
            @endphp

            <div style="background: #ffffff; border: 1px solid rgba(15, 23, 42, 0.08); border-radius: 18px; padding: 24px;">
                <h2 style="margin: 0 0 16px; font-size: 22px;">Cart summary</h2>
                <p style="margin: 0; color: #475569;">{{ $cartItems->sum('quantity') }} item(s) in your cart — total <strong>₱{{ number_format($cartTotal, 2) }}</strong>.</p>
            </div>

            <div style="background: #ffffff; border: 1px solid rgba(15, 23, 42, 0.08); border-radius: 18px; padding: 24px; display: grid; gap: 18px;">
                @foreach ($cartItems as $item)
                    @if ($item->product)
                        <div style="display: grid; grid-template-columns: 100px minmax(0, 1fr) auto; gap: 16px; align-items: center; padding: 18px; border-radius: 16px; background: #f8fdf7;">
                            <div style="width: 100px; height: 100px; border-radius: 16px; overflow: hidden; background: #e7fbef; display: grid; place-items: center;">
                                @if ($item->product->image)
                                    <img src="{{ Str::startsWith($item->product->image, ['http://', 'https://']) ? $item->product->image : asset('storage/' . ltrim($item->product->image, '/')) }}" alt="{{ $item->product->name }}" style="width:100%;height:100%;object-fit:cover;" />
                                @else
                                    <span style="color: #047857; font-weight: 700;">No image</span>
                                @endif
                            </div>

                            <div>
                                <div style="font-size: 16px; color: #16a34a; text-transform: uppercase; letter-spacing: .08em; margin-bottom: 6px;">{{ $item->product->category }}</div>
                                <h3 style="margin: 0 0 8px; font-size: 20px;">{{ $item->product->name }}</h3>
                                <p style="margin: 0; color: #475569;">Quantity: <strong>{{ $item->quantity }}</strong></p>
                            </div>

                            <div style="text-align: right;">
                                <div style="font-size: 18px; font-weight: 800;">₱{{ number_format($item->product->price * $item->quantity, 2) }}</div>
                                <div style="color: #6b7280;">₱{{ number_format($item->product->price, 2) }} each</div>
                            </div>
                        </div>
                    @else
                        <div style="padding: 18px; border-radius: 16px; background: #fff1f2; color: #9f1239;">
                            <p style="margin: 0;"><strong>Removed product:</strong> This item is no longer available.</p>
                        </div>
                    @endif
                @endforeach
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 16px;">
                <a href="{{ route('checkout') }}" style="padding: 14px 22px; border-radius: 999px; background: #16a34a; color: #ffffff; text-decoration: none; font-weight: 700;">Proceed to checkout</a>
            </div>
        </div>
    @endif
</div>
@endsection
