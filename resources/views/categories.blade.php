@extends('layouts.app')
@section('title', 'Categories – MediCare Pharmacy')

@section('content')
<section class="page-header">
    <div class="page-panel">
        <h1>Categories</h1>
        <p>Explore our wide range of health & wellness categories.</p>
    </div>
</section>

<section class="section">
    <div class="section-inner categories-page">
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
        @foreach ($categories as $cat)
            <a href="{{ route('shop', ['category' => $cat['name']]) }}" class="category-card-full">
                <div class="category-card-icon">
                    @include('partials.category-icon', ['icon' => $cat['icon']])
                </div>
                <h3>{{ $cat['name'] }}</h3>
                <p>{{ $categoryDescriptions[$cat['name']] ?? '' }}</p>
                <span class="browse-link">Browse →</span>
            </a>
        @endforeach
    </div>
</section>
@endsection
