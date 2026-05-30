@extends('layouts.app')
@section('title', 'Home – Healthcare Pharmacy')

@push('styles')
<style>
    :root {
        --bg: #f6fff7;
        --surface: #ffffff;
        --surface-soft: #e7fbef;
        --accent: #16a34a;
        --accent-dark: #047857;
        --text: #0f172a;
        --muted: #475569;
        --border: rgba(15, 23, 42, 0.08);
    }

    .hero {
        padding: 60px 24px 44px;
        background: radial-gradient(circle at top left, rgba(16, 163, 127, 0.16) 0%, rgba(255,255,255,0.95) 40%), linear-gradient(180deg, #f4fff7 0%, #ffffff 100%);
    }

    .hero-inner {
        max-width: 1040px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1.35fr 0.9fr;
        gap: 32px;
        align-items: center;
    }

    .hero-copy {
        padding: 10px 0;
    }

    .hero-logo-wrap {
        width: 90px;
        height: 90px;
        background: #10b981;
        border-radius: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 22px;
        box-shadow: 0 24px 64px rgba(16, 163, 127, 0.12);
    }

    .hero-logo-wrap svg {
        width: 42px;
        height: 42px;
    }

    .hero-brand-name,
    .hero-brand-sub {
        display: block;
        font-weight: 800;
    }

    .hero-brand-name {
        font-size: 46px;
        color: var(--text);
        letter-spacing: -0.04em;
        margin-bottom: 4px;
    }

    .hero-brand-sub {
        font-size: 16px;
        color: var(--accent);
        letter-spacing: 0.22em;
        text-transform: uppercase;
        margin-bottom: 24px;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(16, 163, 127, 0.12);
        color: var(--accent-dark);
        font-size: 13px;
        font-weight: 700;
        padding: 10px 16px;
        border-radius: 999px;
        margin-bottom: 18px;
    }

    .hero h1 {
        font-size: clamp(42px, 5vw, 64px);
        line-height: 0.95;
        color: var(--text);
        margin-bottom: 18px;
    }

    .hero h1 span {
        color: var(--accent);
    }

    .hero-desc {
        max-width: 640px;
        font-size: 17px;
        line-height: 1.85;
        color: var(--muted);
        margin-bottom: 32px;
    }

    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
    }

    .btn-primary,
    .btn-outline,
    .btn-add {
        border: 1px solid transparent;
        border-radius: 999px;
        font-weight: 700;
        transition: all 180ms ease;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-primary {
        background: var(--accent);
        color: #ffffff;
        padding: 14px 28px;
    }

    .btn-primary:hover {
        background: #059669;
    }

    .btn-outline:hover {
        background: rgba(16, 163, 127, 0.08);
    }

    .hero-panel {
        display: grid;
        place-items: center;
    }

    .hero-panel-card {
        width: 100%;
        max-width: 420px;
        background: var(--surface);
        border: 1px solid rgba(15, 23, 42, 0.06);
        border-radius: 32px;
        padding: 40px 30px;
        box-shadow: 0 28px 64px rgba(15, 23, 42, 0.08);
    }

    .hero-panel-icon {
        width: 64px;
        height: 64px;
        border-radius: 20px;
        background: var(--accent);
        color: #fff;
        display: grid;
        place-items: center;
        margin-bottom: 24px;
        font-size: 28px;
        box-shadow: 0 18px 40px rgba(16, 163, 127, 0.22);
    }

    .hero-panel-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 4px;
    }

    .hero-panel-sub {
        font-size: 13px;
        color: var(--accent);
        letter-spacing: 0.25em;
        text-transform: uppercase;
        margin-bottom: 18px;
        display: inline-block;
    }

    .hero-panel-copy {
        color: var(--muted);
        line-height: 1.8;
        margin-bottom: 22px;
    }

    .hero-panel-meta {
        display: grid;
        gap: 18px;
        padding-top: 20px;
        border-top: 1px solid rgba(15, 23, 42, 0.08);
    }

    .hero-panel-stat {
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: var(--text);
        font-weight: 700;
    }

    .hero-panel-stat span {
        color: var(--muted);
        font-size: 14px;
        font-weight: 500;
    }

    .carousel-section {
        padding: 28px 24px 0;
    }

    .carousel-panel {
        max-width: 1100px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 34px;
        border: 1px solid rgba(15, 23, 42, 0.08);
        overflow: hidden;
        box-shadow: 0 28px 80px rgba(15, 23, 42, 0.08);
    }

    .carousel-inner {
        position: relative;
        min-height: 380px;
        padding: 36px;
        display: grid;
        align-items: center;
        gap: 24px;
    }

    .carousel-slide {
        display: none;
        grid-template-columns: 1.1fr 0.9fr;
        gap: 28px;
        align-items: center;
    }

    .carousel-slide.active {
        display: grid;
    }

    .carousel-copy {
        max-width: 520px;
    }

    .carousel-copy h3 {
        margin: 0 0 14px;
        font-size: 30px;
        line-height: 1.05;
        color: var(--text);
    }

    .carousel-copy p {
        margin: 0 0 20px;
        color: var(--muted);
        line-height: 1.8;
        font-size: 16px;
    }

    .carousel-copy .btn-primary {
        padding: 14px 28px;
    }

    .carousel-image {
    position: relative;
    min-height: 320px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.carousel-banner{
    width: 420px;
    height: 280px;
    object-fit: contain;
    border-radius: 28px;
    display: block;
}
    .carousel-image .image-plate {
        width: 100%;
        max-width: 360px;
        aspect-ratio: 1 / 1;
        background: linear-gradient(180deg, #dbeffe 0%, #ffffff 100%);
        border-radius: 36px;
        box-shadow: 0 18px 40px rgba(16, 163, 127, 0.12);
        display: grid;
        place-items: center;
        position: relative;
    }

    .carousel-image .image-plate::before {
        content: '';
        position: absolute;
        width: 140%;
        height: 140%;
        top: -30%;
        left: -20%;
        background: rgba(22, 163, 74, 0.08);
        border-radius: 50%;
    }

    .carousel-image .bottle {
        width: 170px;
        height: 230px;
        border-radius: 34px;
        background: linear-gradient(180deg, #6b46c1 0%, #9f7aea 100%);
        position: relative;
        box-shadow: 0 22px 42px rgba(75, 0, 130, 0.18);
        display: grid;
        place-items: center;
        color: #ffffff;
        font-weight: 700;
        letter-spacing: 0.02em;
        text-align: center;
        padding: 18px;
    }

    .carousel-image .bottle::before {
        content: '';
        position: absolute;
        top: 16px;
        width: 110px;
        height: 58px;
        background: rgba(255,255,255,0.18);
        border-radius: 16px;
        left: 50%;
        transform: translateX(-50%);
    }

    .carousel-image .pill-group {
        position: absolute;
        display: grid;
        gap: 12px;
        top: 18px;
        right: 22px;
    }

    .carousel-image .pill-group span {
        width: 26px;
        height: 14px;
        background: #6b46c1;
        border-radius: 999px;
    }

    .carousel-nav {
        display: flex;
        justify-content: center;
        gap: 10px;
        padding: 18px 0 26px;
        background: #f4fff7;
    }

    .carousel-dot {
        width: 12px;
        height: 12px;
        border-radius: 999px;
        border: 1px solid rgba(16, 163, 74, 0.4);
        background: transparent;
        cursor: pointer;
        transition: transform 150ms ease, background 150ms ease;
    }

    .carousel-dot.active {
        background: #16a34a;
        transform: scale(1.1);
    }

    @media (max-width: 980px) {
        .carousel-slide {
            grid-template-columns: 1fr;
        }

        .carousel-inner {
            padding: 24px;
        }
    }

    @media (max-width: 680px) {
        .carousel-inner {
            padding: 18px;
        }

        .carousel-copy h3 {
            font-size: 26px;
        }

        .carousel-image {
            min-height: 260px;
        }
    }

    

    .section {
        padding: 48px 24px 28px;
        background: var(--bg);
    }

    .section-inner {
        max-width: 1200px;
        margin: 0 auto;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        align-items: center;
        margin-bottom: 22px;
    }

    .section-header h2 {
        margin: 0;
        font-size: 28px;
        color: var(--text);
    }

    .view-all {
        color: var(--accent-dark);
        font-weight: 700;
        text-decoration: none;
    }

    .categories-grid,
    .products-grid {
        display: grid;
        gap: 18px;
    }

    .categories-grid {
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    }

    .category-card {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 24px;
        padding: 22px 20px;
        display: grid;
        gap: 14px;
        justify-items: center;
        text-align: center;
        transition: transform 180ms ease, box-shadow 180ms ease;
    }

    .category-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
    }

    .category-card .category-icon {
        width: 56px;
        height: 56px;
        display: grid;
        place-items: center;
        border-radius: 18px;
        background: rgba(16, 163, 127, 0.1);
        color: var(--accent-dark);
    }

    .category-card span {
        color: var(--text);
        font-weight: 700;
        font-size: 15px;
    }

    .products-grid {
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    }

    .product-card {
        background: linear-gradient(180deg, #f4fdf7 0%, #ffffff 100%);
        border: 1px solid var(--border);
        border-radius: 28px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        min-height: 340px;
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.06);
    }

    .product-card-body {
        padding: 22px;
        display: grid;
        gap: 18px;
        flex: 1;
    }

    .product-card-top {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        align-items: flex-start;
    }

    .product-category {
        font-size: 13px;
        color: var(--accent-dark);
        font-weight: 700;
        letter-spacing: 0.15em;
        text-transform: uppercase;
    }

    .product-name {
        margin: 0;
        font-size: 18px;
        color: var(--text);
        font-weight: 800;
        line-height: 1.3;
    }

    .product-pricing {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .product-pricing strong {
        color: var(--accent-dark);
        font-size: 20px;
    }

    .product-old-price {
        color: var(--muted);
        text-decoration: line-through;
        font-size: 14px;
    }

    .product-rating {
        color: var(--accent-dark);
        font-size: 13px;
        font-weight: 700;
    }

    .product-card-actions {
        padding: 0 22px 22px;
    }

    .btn-add {
        width: 100%;
        background: #ffffff;
        color: var(--accent-dark);
        border: 1px solid rgba(16, 163, 127, 0.2);
        padding: 12px 18px;
        border-radius: 16px;
    }

    .btn-add:hover {
        background: rgba(16, 163, 127, 0.08);
    }

    .product-card-icon {
        width: 56px;
        height: 56px;
        border-radius: 18px;
        background: var(--accent);
        color: #ffffff;
        display: grid;
        place-items: center;
        font-size: 24px;
        margin: 0 auto;
    }

    @media (max-width: 980px) {
        .hero-inner {
            grid-template-columns: 1fr;
        }

        .features-inner {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 680px) {
        .features-inner,
        .categories-grid,
        .products-grid {
            grid-template-columns: 1fr;
        }

        .hero h1 {
            font-size: 38px;
        }

        .hero-panel-card {
            padding: 30px 24px;
        }
    }
</style>
@endpush

@section('content')

<section class="carousel-section">
    <div class="carousel-panel">
        <div class="carousel-inner">
            <article class="carousel-slide active" data-slide="0">
                <div class="carousel-copy">
                    <h3>Discover Wellness Essentials</h3>
                    <p>Browse premium supplements, vitamins, and care products in our curated carousel. Each slide highlights a top wellness pick with a clean, user-friendly layout.</p>
                    <a href="{{ route('shop') }}" class="btn-primary">Shop Wellness</a>
                </div>
                    <div class="carousel-image">
                    <img src="{{ asset('images/c1.jpg') }}"
                    alt="Carousel Image"
                    class="carousel-banner">
                </div>
            </article>

            <article class="carousel-slide" data-slide="1">
                <div class="carousel-copy">
                    <h3>Fast Delivery on Everyday Care</h3>
                    <p>Get your essential medicines and personal care items delivered quickly. The carousel helps your customers focus on speed, convenience, and trust.</p>
                    <a href="{{ route('shop') }}" class="btn-primary">View Delivery</a>
                </div>
               <div class="carousel-image">
                    <img src="{{ asset('images/c2.jpg') }}"
                        alt="Carousel Image"
                        class="carousel-banner">
                </div>
            </article>

            <article class="carousel-slide" data-slide="2">
                <div class="carousel-copy">
                    <h3>Trusted Care from Certified Pharmacists</h3>
                    <p>Share confidence with expert support and genuine products. This slide showcases the pharmacy’s care promise and product quality.</p>
                    <a href="{{ route('contact') }}" class="btn-primary">Contact Support</a>
                </div>
                <div class="carousel-image">
                    <img src="{{ asset('images/c3.jpg') }}"
                        alt="Carousel Image"
                        class="carousel-banner">
                </div>
            </article>
        </div>
        <div class="carousel-nav">
            <button class="carousel-dot active" type="button" data-slide="0"></button>
            <button class="carousel-dot" type="button" data-slide="1"></button>
            <button class="carousel-dot" type="button" data-slide="2"></button>
        </div>
    </div>
</section>

<section class="section" style="background: #fafafa;">
    <div class="section-inner">
        <div class="section-header">
            <h2>Shop by Category</h2>
            <a href="{{ route('categories') }}" class="view-all">View All</a>
        </div>
        <div class="categories-grid">
            @foreach ($categories as $cat)
            <a href="{{ route('shop', ['category' => $cat['name']]) }}" class="category-card">
                <div class="category-icon">
                    @include('partials.category-icon', ['icon' => $cat['icon']])
                </div>
                <span>{{ $cat['name'] }}</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

<section class="section">
    <div class="section-inner">
        <div class="section-header">
            <h2>Featured Products</h2>
            <a href="{{ route('shop') }}" class="view-all">View All</a>
        </div>
        <div class="products-grid">
            @foreach ($featured_products as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const slides = document.querySelectorAll('.carousel-slide');
        const dots = document.querySelectorAll('.carousel-dot');
        let activeIndex = 0;

        function setActive(index) {
            slides.forEach((slide, idx) => {
                slide.classList.toggle('active', idx === index);
            });
            dots.forEach((dot, idx) => {
                dot.classList.toggle('active', idx === index);
            });
            activeIndex = index;
        }

        dots.forEach(dot => {
            dot.addEventListener('click', function () {
                setActive(Number(this.dataset.slide));
            });
        });

        setInterval(function () {
            setActive((activeIndex + 1) % slides.length);
        }, 6000);
    });
</script>
@endpush

@endsection