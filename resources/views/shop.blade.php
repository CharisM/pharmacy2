@extends('layouts.app')
@section('title', 'Shop – MediCare Pharmacy')

@section('content')

<section class="page-header">
    <div class="page-panel">
        <h1>Shop</h1>
        <p>Browse our full catalog of medicines & wellness products.</p>
    </div>

    <div class="shop-controls">
        <div class="shop-search">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input type="text" placeholder="Search products...">
        </div>

        <button class="btn-filters">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
            </svg>
            Filters
        </button>
    </div>

    <div class="filter-panel">
        <a href="{{ route('shop') }}" class="pill {{ empty($category_filter) ? 'pill-active' : '' }}">
            All
        </a>

        @foreach ($categories as $category)
            <a href="{{ route('shop', ['category' => $category]) }}"
               class="pill {{ $category_filter === $category ? 'pill-active' : '' }}">
                {{ $category }}
            </a>
        @endforeach
    </div>
</section>

<section class="section">
    <div class="section-inner">

        {{-- 🔥 THIS IS THE IMPORTANT FIX --}}
        <div class="products-grid">

            @forelse ($products as $product)
                @include('partials.product-card', ['product' => $product])
            @empty
                <div class="empty-state">
                    <h2>No products found</h2>
                    <p>Try selecting another category or browse our full shop.</p>
                </div>
            @endforelse

        </div>

    </div>
</section>

@endsection