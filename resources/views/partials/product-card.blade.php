<article class="product-card">
    <div class="product-card-icon">+</div>

    <div class="product-card-body">
        <div class="product-category">{{ $product['category'] }}</div>

        <h3 class="product-name">{{ $product['name'] }}</h3>

        <div class="product-pricing">
            <strong>${{ number_format($product['price'], 2) }}</strong>

            @if (!empty($product['old_price']))
                <span class="product-old-price">
                    ${{ number_format($product['old_price'], 2) }}
                </span>
            @endif
        </div>

        <div class="product-rating">
            ★ {{ number_format($product['rating'], 1) }}/5
        </div>
    </div>

    <div class="product-card-actions">

        @auth
            <button type="button" class="btn-add">
                Add
            </button>
        @endauth

        @guest
            <a href="{{ route('login') }}" class="btn-add">
                Add
            </a>
        @endguest

    </div>
</article>