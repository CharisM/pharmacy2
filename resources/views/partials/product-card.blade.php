@php
    $productId = data_get($product, 'id');
    $category = data_get($product, 'category');
    $name = data_get($product, 'name');
    $price = data_get($product, 'price', 0);
    $oldPrice = data_get($product, 'old_price');
    $image = data_get($product, 'image');

    // Determine image URL: allow absolute URLs or storage paths
    if (!empty($image)) {
        if (Str::startsWith($image, ['http://', 'https://'])) {
            $imageUrl = $image;
        } else {
            $imageUrl = asset('storage/' . ltrim($image, '/'));
        }
    } else {
        $imageUrl = null;
    }
@endphp

<article class="product-card">
    <div class="product-image">
        @if (!empty($imageUrl))
            <img src="{{ $imageUrl }}" alt="{{ $name }}">
        @else
            <div class="product-image-placeholder" aria-hidden="true">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <rect x="0" y="0" width="24" height="24" rx="6" fill="white" opacity="0.08"/>
                    <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        @endif
        <button type="button" class="img-add-btn" aria-label="Quick add">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 5v14M5 12h14" />
            </svg>
        </button>
    </div>

    <div class="product-card-body">
        <div class="product-card-top">
            <div>
                <div class="product-category">{{ $category }}</div>
                <h3 class="product-name">{{ $name }}</h3>
            </div>
        </div>

        <div class="product-pricing">
            <strong>₱{{ number_format($price, 2) }}</strong>

            @if (!empty($oldPrice))
                <span class="product-old-price">
                    ₱{{ number_format($oldPrice, 2) }}
                </span>
            @endif
        </div>
    </div>

    <div class="product-card-actions">
        @auth
            <form action="{{ route('cart.add', $productId) }}" method="POST">
                @csrf
                <button type="submit" class="btn-add small">Add</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn-add small">Add</a>
        @endauth
    </div>
</article>
