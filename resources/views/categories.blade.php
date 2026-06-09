@extends('layouts.app')
@section('title', 'Categories – MediCare Pharmacy')

@push('styles')
<style>
    :root {
        --accent: #16a34a;
        --accent-dark: #047857;
        --text: #0f172a;
        --muted: #475569;
        --border: rgba(15,23,42,0.08);
    }

    .page-header { padding: 36px 24px 0; }
    .page-header h1 { font-size: 28px; color: var(--text); margin: 0 0 4px; }
    .page-header p  { color: var(--muted); margin: 0; }

    .cat-filter-bar {
        padding: 20px 24px 0;
        max-width: 1200px;
        margin: 0 auto;
    }

    .cat-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .cat-tabs a {
        display: inline-block;
        padding: 8px 20px;
        border-radius: 999px;
        border: 1.5px solid var(--border);
        color: var(--muted);
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: all 160ms ease;
        background: #fff;
    }

    .cat-tabs a:hover,
    .cat-tabs a.active {
        background: var(--accent);
        border-color: var(--accent);
        color: #fff;
    }

    .section { padding: 28px 24px 48px; }
    .section-inner { max-width: 1200px; margin: 0 auto; }

    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 60px 0;
        color: var(--muted);
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <div style="max-width:1200px;margin:0 auto">
        <h1>Shop by Category</h1>
        <p>Browse our range of health & wellness products.</p>
    </div>
</div>

<div class="cat-filter-bar">
    <ul class="cat-tabs">
        <li><a href="{{ route('categories', ['category' => 'all']) }}"
               class="{{ $selectedCategory === null ? 'active' : '' }}">All</a></li>
        @foreach ($categories as $cat)
        <li><a href="{{ route('categories', ['category' => $cat['slug']]) }}"
               class="{{ $selectedCategory === $cat['name'] ? 'active' : '' }}">{{ $cat['name'] }}</a></li>
        @endforeach
    </ul>
</div>

<section class="section">
    <div class="section-inner">
        <div class="products-grid">
            @forelse ($products as $product)
                @include('partials.product-card', ['product' => $product])
            @empty
                <p class="empty-state">No products found in this category.</p>
            @endforelse
        </div>
    </div>
</section>

@endsection
