@extends('layouts.app')
@section('title', 'Categories – MediCare Pharmacy')

@section('content')
@php
    $categoryDescriptions = [
        'Medicines' => 'Prescription & OTC drugs',
        'Personal Care' => 'Daily hygiene essentials',
        'Baby Care' => 'Safe products for little ones',
        'Wellness' => 'Natural & herbal remedies',
        'Vitamins' => 'Supplements & multivitamins',
        'Health Devices' => 'Monitors & diagnostics',
        'First Aid' => 'Emergency care kits',
    ];
@endphp

<section class="page-header">
    <div class="page-panel">
        @if (!empty($selectedCategory))
            <a href="{{ route('categories') }}" class="back-to-categories" style="display: inline-block; margin-bottom: 0.5rem;">← Back to categories</a>
            <h1>{{ $selectedCategory }}</h1>
            <p>{{ $categoryDescriptions[$selectedCategory] ?? '' }}</p>
        @else
            <h1>Categories</h1>
            <p>Explore our wide range of health & wellness categories.</p>
        @endif
    </div>
</section>

<section class="section">
    <div class="section-inner categories-page">
        @if (!empty($selectedCategory))
            <div class="products-grid" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 1.5rem;">
                @forelse ($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @empty
                    <p>No products found in this category.</p>
                @endforelse
            </div>
        @else
            @foreach ($categories as $cat)
                <a href="{{ route('categories', ['category' => $cat['slug']]) }}" class="category-card-full">
                    <div class="category-card-icon">
                        @include('partials.category-icon', ['icon' => $cat['icon']])
                    </div>
                    <h3>{{ $cat['name'] }}</h3>
                    <p>{{ $categoryDescriptions[$cat['name']] ?? '' }}</p>
                    <span class="browse-link">Browse →</span>
                </a>
            @endforeach
        @endif
    </div>
</section>
@endsection
