@php
    $productId  = data_get($product, 'id');
    $category   = data_get($product, 'category');
    $name       = data_get($product, 'name');
    $price      = data_get($product, 'price', 0);
    $oldPrice   = data_get($product, 'old_price');
    $imageUrl   = is_object($product) ? ($product->image_url ?? null) : null;
    $stock      = (int) data_get($product, 'stock', 0);
    $outOfStock = $stock === 0;
@endphp

<article class="product-card" data-product-id="{{ $productId }}">
    {{-- Image --}}
    <div class="pc-image">
        @if ($imageUrl)
            <img src="{{ $imageUrl }}" alt="{{ $name }}" onerror="this.style.display='none';this.nextElementSibling.style.display='grid'">
            <div class="pc-image-placeholder" style="display:none">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" xmlns="http://www.w3.org/2000/svg">
                    <rect x="3" y="3" width="18" height="18" rx="4"/><path d="M3 9l4-4 4 4 4-4 4 4"/>
                    <circle cx="8.5" cy="14.5" r="1.5"/>
                </svg>
            </div>
        @else
            <div class="pc-image-placeholder">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" xmlns="http://www.w3.org/2000/svg">
                    <rect x="3" y="3" width="18" height="18" rx="4"/><path d="M3 9l4-4 4 4 4-4 4 4"/>
                    <circle cx="8.5" cy="14.5" r="1.5"/>
                </svg>
            </div>
        @endif
        @if ($outOfStock)
            <span class="pc-oos-overlay">Out of Stock</span>
        @endif
    </div>

    {{-- Body --}}
    <div class="pc-body">
        <span class="pc-category">{{ $category }}</span>
        <h3 class="pc-name">{{ $name }}</h3>

        <div class="pc-pricing">
            <strong>₱{{ number_format($price, 2) }}</strong>
            @if (!empty($oldPrice))
                <span class="pc-old-price">₱{{ number_format($oldPrice, 2) }}</span>
            @endif
        </div>

        <div class="pc-stock-row">
            @if ($outOfStock)
                <span class="pc-stock-badge pc-stock-oos">Out of Stock</span>
            @elseif ($stock <= 10)
                <span class="pc-stock-badge pc-stock-low">Only {{ $stock }} left</span>
            @else
                <span class="pc-stock-badge pc-stock-ok">{{ $stock }} in stock</span>
            @endif
        </div>
    </div>

    {{-- Actions --}}
    <div class="pc-actions">
        @auth
            @if ($outOfStock)
                <button type="button" class="pc-btn pc-btn-cart" disabled style="opacity:.45;cursor:not-allowed;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    Add to Cart
                </button>
                <button type="button" class="pc-btn pc-btn-buynow" disabled style="opacity:.45;cursor:not-allowed;">Buy Now</button>
            @else
                <form action="{{ route('cart.add', $productId) }}" method="POST" class="pc-form">
                    @csrf
                    <button type="submit" class="pc-btn pc-btn-cart">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                        Add to Cart
                    </button>
                </form>
                <form action="{{ route('cart.buynow', $productId) }}" method="POST" class="pc-form">
                    @csrf
                    <button type="submit" class="pc-btn pc-btn-buynow">Buy Now</button>
                </form>
            @endif
        @else
            <a href="{{ route('login') }}" class="pc-btn pc-btn-cart">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                Add to Cart
            </a>
            <a href="{{ route('login') }}" class="pc-btn pc-btn-buynow">Buy Now</a>
        @endauth
    </div>
</article>
