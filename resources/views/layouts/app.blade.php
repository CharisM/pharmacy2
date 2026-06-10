<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Healthcare Pharmacy')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        :root {
            --bg: #eef7f1;
            --surface: #ffffff;
            --surface-soft: #f8fafc;
            --text: #0f172a;
            --muted: #475569;
            --accent: #16a34a;
            --accent-strong: #047857;
            --border: rgba(15, 23, 42, 0.08);
            color-scheme: light;
            font-family: 'Nunito', ui-sans-serif, system-ui, sans-serif;
            background: linear-gradient(180deg, #f3faf5 0%, #f8fbff 100%);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.96);
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
            position: sticky;
            top: 0;
            z-index: 50;
            backdrop-filter: blur(12px);
            box-shadow: 0 16px 36px rgba(15, 23, 42, 0.06);
        }

        .navbar-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 16px 24px;
            flex-wrap: wrap;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            color: var(--text);
        }

        .brand-icon {
            width: 48px;
            height: 48px;
            display: grid;
            place-items: center;
            border-radius: 18px;
            background: #ffffff;
            box-shadow: 0 18px 40px rgba(16, 163, 127, 0.12);
        }

        .brand-icon svg {
            width: 24px;
            height: 24px;
        }

        /* ADD THIS BELOW .brand-icon */
        .brand-logo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .brand-sub {
            display: block;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: #047857;
            line-height: 1.1;
        }

        .navbar-nav {
            display: flex;
            align-items: center;
            gap: 28px;
            list-style: none;
            margin: 0;
            padding: 0;
            flex-wrap: wrap;
        }

        .navbar-nav a {
            text-decoration: none;
            color: var(--text);
            font-weight: 600;
            font-size: 15px;
            transition: color 180ms ease;
        }

        .navbar-nav a.active,
        .navbar-nav a:hover {
            color: var(--accent-strong);
        }

        .navbar-search {
            flex: 1;
            min-width: 240px;
            max-width: 380px;
            display: flex;
            align-items: center;
            gap: 10px;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 999px;
            padding: 12px 18px;
            box-shadow: inset 0 0 0 1px rgba(16, 163, 127, 0.05);
        }

        .navbar-search input {
            border: none;
            outline: none;
            background: transparent;
            flex: 1;
            font-size: 14px;
            color: var(--text);
            min-width: 0;
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .icon-btn {
            width: 46px;
            height: 46px;
            border-radius: 16px;
            border: 1px solid rgba(15, 23, 42, 0.08);
            background: #ffffff;
            color: var(--text);
            display: grid;
            place-items: center;
            cursor: pointer;
            position: relative;
            text-decoration: none;
            transition: transform 180ms ease, background 180ms ease, border-color 180ms ease;
            box-shadow: 0 18px 30px rgba(15, 23, 42, 0.06);
        }

        .icon-btn:hover {
            transform: translateY(-1px);
            background: rgba(22, 163, 74, 0.08);
            border-color: rgba(16, 163, 127, 0.24);
        }

        .account-dropdown {
            position: relative;
        }

        .account-dropdown-button {
            width: 46px;
            height: 46px;
            border-radius: 16px;
            border: 1px solid rgba(15, 23, 42, 0.08);
            background: #ffffff;
            color: var(--text);
            display: grid;
            place-items: center;
            cursor: pointer;
            position: relative;
            text-decoration: none;
            transition: transform 180ms ease, background 180ms ease, border-color 180ms ease;
            box-shadow: 0 18px 30px rgba(15, 23, 42, 0.06);
        }

        .account-dropdown-button:hover {
            transform: translateY(-1px);
            background: rgba(22, 163, 74, 0.08);
        }

        .account-dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: calc(100% + 12px);
            min-width: 200px;
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.12);
            border-radius: 18px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
            padding: 10px 0;
            z-index: 200;
        }

        .account-dropdown.open .account-dropdown-menu {
            display: block;
        }

        .account-dropdown-menu a,
        .account-dropdown-menu button {
            display: block;
            width: 100%;
            padding: 12px 18px;
            text-align: left;
            border: none;
            background: transparent;
            color: var(--text);
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
        }

        .account-dropdown-menu a:hover,
        .account-dropdown-menu button:hover {
            background: rgba(22, 163, 74, 0.08);
        }

        .account-dropdown-menu button {
            font-family: inherit;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 24px;
            padding: 2px 10px;
            border-radius: 999px;
            background: var(--accent);
            color: #ffffff;
            font-size: 12px;
            font-weight: 700;
            position: absolute;
            top: -7px;
            right: -7px;
        }
        .page-header {
            padding: 48px 24px 36px;
        }

        .page-panel {
            max-width: 1120px;
            margin: 0 auto;
            background: var(--surface);
            border-radius: 32px;
            padding: 36px 40px;
            box-shadow: 0 24px 52px rgba(15, 23, 42, 0.08);
            border: 1px solid rgba(16, 163, 127, 0.08);
        }

        .page-panel h1 {
            margin: 0 0 14px;
            font-size: clamp(2rem, 2.8vw, 3rem);
            line-height: 1.05;
        }

        .page-panel h1 span {
            color: var(--accent);
        }

        .page-panel p {
            margin: 0;
            color: var(--muted);
            line-height: 1.8;
            font-size: 15.5px;
        }

        .profile-card {
            background: var(--surface-soft);
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 28px;
            padding: 28px;
            display: grid;
            gap: 24px;
        }

        .profile-card .profile-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        .profile-card h2 {
            margin: 0 0 8px;
            font-size: 18px;
            color: var(--accent-strong);
        }

        .profile-card p {
            margin: 0;
            color: var(--muted);
            line-height: 1.8;
        }

        .filter-panel {
            max-width: 1100px;
            margin: 18px auto 0;
            padding: 0 24px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 18px;
            border-radius: 999px;
            background: #ecfdf5;
            color: #0f172a;
            text-decoration: none;
            font-weight: 600;
            border: 1px solid transparent;
            transition: transform 180ms ease, background 180ms ease, color 180ms ease;
        }

        .pill:hover {
            transform: translateY(-1px);
            background: #d9fcd7;
        }

        .pill-active {
            background: var(--accent);
            color: #ffffff;
        }

        .section-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .shop-controls {
            max-width: 1100px;
            margin: 18px auto 0;
            padding: 0 24px;
            display: flex;
            gap: 14px;
            align-items: center;
        }

        .shop-search {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 10px;
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 999px;
            padding: 10px 16px;
        }

        .shop-search input {
            border: none;
            outline: none;
            background: transparent;
            flex: 1;
            font-size: 14px;
            color: #0f172a;
            min-width: 0;
        }

        .btn-outline {
            background: transparent;
            color: var(--accent-strong);
            border: 1px solid rgba(16, 163, 127, 0.28);
            padding: 12px 24px;
            border-radius: 999px;
            font-weight: 700;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            cursor: pointer;
            transition: transform 180ms ease, background 180ms ease, border-color 180ms ease;
            box-shadow: 0 14px 30px rgba(16, 163, 127, 0.06);
        }

        .btn-outline:hover {
            background: rgba(16, 163, 127, 0.1);
            border-color: rgba(16, 163, 127, 0.4);
            transform: translateY(-1px);
        }

        .categories-page {
            display: grid;
            gap: 20px;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        }

        .category-card-full {
            display: grid;
            gap: 14px;
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 24px;
            padding: 28px 24px;
            color: #0f172a;
            text-decoration: none;
            transition: transform 180ms ease, box-shadow 180ms ease;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
        }

        .category-card-full:hover {
            transform: translateY(-2px);
            box-shadow: 0 24px 52px rgba(15, 23, 42, 0.12);
        }

        .category-card-icon {
            width: 56px;
            height: 56px;
            display: grid;
            place-items: center;
            border-radius: 20px;
            background: rgba(16, 163, 127, 0.1);
            color: var(--accent-dark);
        }

        .category-card-full h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 800;
        }

        .category-card-full p {
            margin: 0;
            font-size: 14px;
            color: #475569;
            line-height: 1.6;
        }

        .browse-link {
            color: var(--accent-dark);
            font-weight: 700;
            font-size: 14px;
        }

        .category-tile {
            display: grid;
            place-items: center;
            background: #ecfdf5;
            border-radius: 28px;
            min-height: 180px;
            color: #0f172a;
            text-decoration: none;
            font-weight: 800;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
        }


        .values-grid {
            display: grid;
            gap: 22px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            margin-bottom: 36px;
        }

        .stats-grid {
            display: grid;
            gap: 20px;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            margin-bottom: 36px;
        }

        .stat-card {
            display: grid;
            gap: 12px;
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 24px;
            padding: 28px 24px;
            text-align: center;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
        }

        .stat-icon {
            color: var(--accent);
            margin: 0 auto;
        }

        .stat-card strong {
            font-size: 24px;
            color: var(--text);
        }

        .stat-card span {
            font-size: 13px;
            color: #475569;
        }


        .value-card,
        .mission-panel,
        .info-card,
        .contact-card {
            background: #ecfdf5;
            border-radius: 28px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
        }

        .value-card {
            padding: 28px 26px;
            min-height: 170px;
        }

        .value-card h3 {
            margin: 0 0 14px;
            color: var(--accent-dark);
        }

        .value-card p {
            margin: 0;
            color: #475569;
            line-height: 1.8;
        }

        .mission-panel {
            max-width: 760px;
            margin: 36px auto 0;
            padding: 30px 28px;
            text-align: center;
            background: #ecfdf5;
            border-radius: 28px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
        }

        .mission-panel h2 {
            margin: 0 0 16px;
            font-size: 1.7rem;
            color: var(--text);
        }

        .mission-panel p {
            margin: 0;
            color: #475569;
            line-height: 1.8;
        }


        .contact-grid {
            display: grid;
            gap: 26px;
            grid-template-columns: 1.05fr 0.95fr;
        }

        .contact-info {
            display: grid;
            gap: 18px;
        }

        .info-card {
            padding: 24px 28px;
            display: grid;
            gap: 10px;
            grid-template-columns: auto 1fr;
            align-items: flex-start;
            background: #ecfdf5;
            border-radius: 28px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
        }

        .info-icon {
            color: var(--accent);
            min-width: 24px;
            grid-column: 1 / 2;
            grid-row: 1 / 4;
            margin-top: 4px;
        }

        .info-card strong {
            grid-column: 2;
            color: var(--accent-dark);
            font-size: 14px;
            margin: 0;
        }

        .info-card span {
            grid-column: 2;
            color: #475569;
            font-size: 15px;
            line-height: 1.8;
            margin: 0;
        }


        .contact-card {
            padding: 30px 28px;
        }

        .contact-card h2 {
            margin: 0 0 20px;
        }

        .contact-card form {
            display: grid;
            gap: 16px;
        }

        .contact-card label {
            font-size: 14px;
            color: #475569;
            font-weight: 700;
        }

        .contact-card input,
        .contact-card textarea {
            width: 100%;
            padding: 14px 16px;
            border-radius: 16px;
            border: 1px solid rgba(15, 23, 42, 0.1);
            background: #ffffff;
            outline: none;
            font-size: 14px;
            color: #0f172a;
        }

        .contact-card textarea {
            min-height: 150px;
            resize: vertical;
        }

        .empty-state {
            padding: 48px 28px;
            text-align: center;
            background: #ecfdf5;
            border-radius: 32px;
        }

        .empty-state h2 {
            margin: 0 0 12px;
            color: var(--text);
        }

        .empty-state p {
            margin: 0;
            color: #475569;
        }

        .flash-message {
            margin: 18px 0 22px;
            padding: 14px 18px;
            border-radius: 16px;
            background: #dcfce7;
            border: 1px solid #86efac;
            color: #166534;
            font-weight: 700;
        }

        main.page-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 24px 48px;
        }

        footer.footer {
            background: #ffffff;
            border-top: 1px solid rgba(15, 23, 42, 0.08);
        }

        .footer-inner {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 36px;
            padding: 42px 24px 28px;
            color: #334155;
        }

        .footer-brand p {
            margin: 14px 0 0;
            font-size: 14px;
            line-height: 1.8;
            color: #475569;
            max-width: 320px;
        }

        .footer-inner h4 {
            margin: 0 0 18px;
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
        }

        .footer-inner ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: 10px;
        }

        .footer-inner li {
            margin: 0;
        }

        .footer-inner a {
            color: #475569;
            text-decoration: none;
            font-size: 14px;
        }

        .footer-inner a:hover {
            color: #16a34a;
        }

        .footer-contact {
            display: grid;
            gap: 14px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #475569;
            font-size: 14px;
        }

        .contact-item svg {
            color: #16a34a;
            min-width: 18px;
        }

        .footer-bottom {
            text-align: center;
            font-size: 13px;
            color: #64748b;
            padding: 18px 24px 24px;
            border-top: 1px solid rgba(15, 23, 42, 0.06);
        }

        @media (max-width: 980px) {
            .stats-grid,
            .values-grid {
                grid-template-columns: 1fr;
            }

            .contact-grid {
                grid-template-columns: 1fr;
            }

            .categories-page {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }

            .shop-controls {
                flex-direction: column;
                align-items: stretch;
            }

            .shop-search {
                flex: 1;
            }

            .btn-filters {
                width: 100%;
                justify-content: center;
            }
        }

        /* Product Card - unified design (home + categories) */
        .products-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(240px,1fr)); gap:22px; }
        .product-card { background:linear-gradient(180deg,#f4fdf7 0%,#ffffff 100%); border:1px solid rgba(15,23,42,0.08); border-radius:28px; overflow:hidden; display:flex; flex-direction:column; box-shadow:0 20px 45px rgba(15,23,42,0.06); transition:transform 180ms ease,box-shadow 180ms ease; min-height:380px; }
        .product-card:hover { transform:translateY(-4px); box-shadow:0 28px 56px rgba(15,23,42,0.10); }
        .pc-image { width:100%; height:190px; flex-shrink:0; overflow:hidden; background:rgba(16,163,127,0.06); display:flex; align-items:center; justify-content:center; }
        .pc-image img { width:100%; height:100%; object-fit:cover; display:block; }
        .pc-image-placeholder { width:80px; height:80px; border-radius:20px; background:rgba(16,163,127,0.12); color:#047857; display:grid; place-items:center; }
        .pc-body { padding:18px 20px 10px; flex:1; display:flex; flex-direction:column; gap:8px; }
        .pc-category { font-size:12px; font-weight:700; color:#047857; letter-spacing:.14em; text-transform:uppercase; }
        .pc-name { margin:0; font-size:17px; font-weight:800; color:#0f172a; line-height:1.3; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; min-height:2.6em; }
        .pc-pricing { display:flex; align-items:center; gap:10px; flex-wrap:wrap; margin-top:auto; padding-top:10px; }
        .pc-pricing strong { color:#047857; font-size:19px; }
        .pc-old-price { color:#475569; text-decoration:line-through; font-size:13px; }
        .pc-actions { padding:12px 20px 18px; flex-shrink:0; display:flex; gap:8px; }
        .pc-form { flex:1; }
        .pc-btn { width:100%; padding:10px 14px; border-radius:12px; font-size:13px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; gap:6px; border:none; transition:all 160ms ease; text-decoration:none; font-family:inherit; }
        .pc-btn-cart { background:#ffffff; color:#047857; border:1.5px solid rgba(16,163,127,0.25); }
        .pc-btn-cart:hover { background:rgba(16,163,127,0.08); border-color:rgba(16,163,127,0.5); }
        .pc-btn-buynow { background:#16a34a; color:#ffffff; }
        .pc-btn-buynow:hover { background:#047857; }
        /* Stock badge */
        .pc-stock-row { margin-top:2px; }
        .pc-stock-badge { display:inline-block; font-size:11px; font-weight:700; padding:3px 10px; border-radius:999px; }
        .pc-stock-ok  { background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0; }
        .pc-stock-low { background:#fffbeb; color:#b45309; border:1px solid #fde68a; }
        .pc-stock-oos { background:#fff1f2; color:#e11d48; border:1px solid #fecdd3; }
        /* Out-of-stock overlay on image */
        .pc-image { position:relative; }
        .pc-oos-overlay { position:absolute; inset:0; background:rgba(0,0,0,0.45); display:flex; align-items:center; justify-content:center; color:#fff; font-size:13px; font-weight:800; letter-spacing:.05em; text-transform:uppercase; border-radius:0; }
    </style>
    @stack('styles')
</head>
<body>
@php
    $cartItemCount = auth('web')->check()
        ? \App\Models\CartItem::where('user_id', auth('web')->id())->sum('quantity')
        : 0;
    $unreadReplyCount = auth('web')->check() && !auth('web')->user()->is_admin
        ? \App\Models\Message::where('user_id', auth('web')->id())->where('has_unread_reply', true)->count()
        : 0;
@endphp

@unless(request()->is('admin*'))
<nav class="navbar">
    <div class="navbar-inner">
        <a href="{{ route('home') }}" class="navbar-brand">
            <div class="brand-icon">
                <img src="{{ asset('images/logopharmacy.png') }}" alt="Logo" class="brand-logo">
            </div>
            <div>
                <span>Healthcare</span>
                <span class="brand-sub">Pharmacy</span>
            </div>
        </a>

        <ul class="navbar-nav">
            <li><a href="{{ route('home') }}"       class="{{ request()->routeIs('home')       ? 'active' : '' }}">Home</a></li>
            <li><a href="{{ route('categories') }}" class="{{ request()->routeIs('categories') ? 'active' : '' }}">Categories</a></li>
            <li><a href="{{ route('about') }}"      class="{{ request()->routeIs('about')      ? 'active' : '' }}">About Us</a></li>
            <li><a href="{{ route('contact') }}"    class="{{ request()->routeIs('contact')    ? 'active' : '' }}">Contact</a></li>
        </ul>

        <div class="navbar-search">
            <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input type="text" placeholder="Search medicines, products...">
        </div>

        <div class="navbar-actions">
            <a href="{{ auth()->check() ? route('cart') : route('login') }}" class="icon-btn" title="Cart">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>
                @if ($cartItemCount > 0)
                    <span class="badge">{{ $cartItemCount }}</span>
                @endif
            </a>

            @auth('web')
                @if (auth('web')->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="btn-outline" style="padding:6px 14px;font-size:13px;text-decoration:none;margin-right:8px;">
                        Admin
                    </a>
                @endif
            @endauth

            @auth('web')
                <div class="account-dropdown">
                    <button type="button" class="account-dropdown-button" data-account-toggle aria-expanded="false" title="Account">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        @if($unreadReplyCount > 0)
                            <span class="badge" style="background:#ef4444;">{{ $unreadReplyCount }}</span>
                        @endif
                    </button>
                    <div class="account-dropdown-menu" data-account-menu aria-hidden="true">
                        <a href="{{ route('profile') }}">Profile</a>
                        <a href="{{ route('messages.index') }}" style="display:flex;align-items:center;justify-content:space-between;">
                            My Messages
                            @if($unreadReplyCount > 0)
                                <span style="background:#ef4444;color:#fff;font-size:11px;font-weight:800;padding:2px 7px;border-radius:999px;">{{ $unreadReplyCount }}</span>
                            @endif
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit">Logout</button>
                        </form>
                    </div>
                </div>
            @endauth

            @guest('web')
                <a href="{{ route('login') }}" class="btn-outline" style="padding:6px 14px;font-size:13px;text-decoration:none;">
                    Login
                </a>
            @endguest
        </div>
    </div>
</nav>
@endunless
<main class="page-container">
@if (session('success'))
    <div class="flash-message">
        {{ session('success') }}
    </div>
@endif

@yield('content')
</main>

@unless(request()->is('admin*'))
<footer class="footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <a href="{{ route('home') }}" class="navbar-brand" style="font-size:16px;">
                <div class="brand-icon">
                    <img src="{{ asset('images/logopharmacy.png') }}" alt="Logo" class="brand-logo">
                </div>
                <div>
                    <span>Healthcare</span>
                    <span class="brand-sub">Pharmacy</span>
                </div>
            </a>
            <p>Genuine medicines, wellness products and expert care right at your doorstep.</p>
        </div>

        <div>
            <h4>Quick Links</h4>
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('categories') }}">Categories</a></li>
                <li><a href="{{ route('about') }}">About Us</a></li>
                <li><a href="{{ route('contact') }}">Contact</a></li>
            </ul>
        </div>

        <div>
            <h4>Support</h4>
            <ul>
                <li><a href="#">Help Center</a></li>
                <li><a href="#">Track Order</a></li>
                <li><a href="#">Returns</a></li>
                <li><a href="#">Privacy Policy</a></li>
            </ul>
        </div>

        <div>
            <h4>Contact</h4>
            <div class="footer-contact">
                <div class="contact-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.18 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.91a16 16 0 0 0 6.06 6.06l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                    +1 (555) 123 4567
                </div>
                <div class="contact-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                    </svg>
                    hello@healthcarepharmacy.com
                </div>
                <div class="contact-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                    </svg>
                    123 Health St, NY
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        Â© {{ date('Y') }} Healthcare Pharmacy. All rights reserved.
    </div>
</footer>
@endunless

<script src="{{ asset('js/app.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const accountToggle = document.querySelector('[data-account-toggle]');
        const accountMenu = document.querySelector('[data-account-menu]');

        if (!accountToggle || !accountMenu) {
            return;
        }

        const dropdown = accountToggle.closest('.account-dropdown');

        accountToggle.addEventListener('click', function (event) {
            event.stopPropagation();
            const isOpen = dropdown.classList.toggle('open');
            accountToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            accountMenu.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
        });

        document.addEventListener('click', function (event) {
            if (!dropdown.contains(event.target) && dropdown.classList.contains('open')) {
                dropdown.classList.remove('open');
                accountToggle.setAttribute('aria-expanded', 'false');
                accountMenu.setAttribute('aria-hidden', 'true');
            }
        });
    });
</script>
@stack('scripts')
</body>
</html>
