@extends('layouts.app')
@section('title', 'Your Cart – Healthcare Pharmacy')

@push('styles')
<style>
    .cart-wrap { max-width: 1100px; margin: 32px auto; padding: 0 24px 120px; }
    .cart-title { font-size: clamp(24px,3vw,36px); margin: 0 0 4px; font-weight: 900; }
    .cart-subtitle { color: #475569; margin: 0 0 22px; font-size:14px; }

    /* ── Empty ── */
    .cart-empty {
        background:#fff; border:1px solid rgba(15,23,42,.08);
        border-radius:18px; padding:56px 32px; text-align:center;
    }

    /* ── Header row ── */
    .cart-header-row {
        display: grid;
        grid-template-columns: 20px 1fr 120px 130px 110px 44px;
        gap: 12px;
        align-items: center;
        padding: 10px 20px;
        background: #fff;
        border: 1px solid rgba(15,23,42,.08);
        border-radius: 12px;
        margin-bottom: 10px;
        font-size: 13px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    /* ── Cart Item Card ── */
    .cart-item-card {
        display: grid;
        grid-template-columns: 20px 1fr 120px 130px 110px 44px;
        gap: 12px;
        align-items: center;
        padding: 16px 20px;
        background: #fff;
        border: 1px solid rgba(15,23,42,.08);
        border-radius: 14px;
        margin-bottom: 8px;
        transition: border-color 180ms, box-shadow 180ms;
    }
    .cart-item-card.is-selected {
        border-color: rgba(22,163,74,.35);
        box-shadow: 0 0 0 2px rgba(22,163,74,.08);
    }
    .cart-item-card.is-removing {
        opacity: 0;
        transform: translateX(20px);
        transition: opacity 250ms, transform 250ms;
    }

    /* Checkbox */
    .cart-cb {
        width: 18px; height: 18px;
        accent-color: #16a34a;
        cursor: pointer;
        flex-shrink: 0;
    }

    /* Product info */
    .cart-product-cell {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
    }
    .cart-thumb {
        width: 72px; height: 72px; flex-shrink: 0;
        border-radius: 10px; overflow: hidden;
        background: #e7fbef;
        display: grid; place-items: center;
        border: 1px solid rgba(15,23,42,.07);
    }
    .cart-thumb img { width:100%; height:100%; object-fit:cover; }
    .cart-item-cat   { font-size:11px; color:#16a34a; text-transform:uppercase; letter-spacing:.1em; font-weight:700; margin-bottom:2px; }
    .cart-item-name  { font-weight:800; font-size:15px; margin:0 0 3px; color:#0f172a; line-height:1.3; }
    .cart-item-price { font-size:13px; color:#64748b; }

    /* Price cell */
    .cart-price-cell { font-weight:700; font-size:15px; color:#0f172a; }

    /* Qty stepper */
    .qty-stepper {
        display: flex;
        align-items: center;
        gap: 0;
        border: 1px solid rgba(15,23,42,.12);
        border-radius: 8px;
        overflow: hidden;
        width: fit-content;
    }
    .qty-stepper-btn {
        width: 32px; height: 32px;
        background: #f8fafc;
        border: none;
        font-size: 17px;
        cursor: pointer;
        color: #475569;
        display: grid; place-items: center;
        transition: background 150ms;
        line-height: 1;
    }
    .qty-stepper-btn:hover { background: #e7fbef; color: #16a34a; }
    .qty-stepper-val {
        width: 36px; text-align: center;
        font-weight: 800; font-size: 14px; color: #0f172a;
        border: none; background: #fff; pointer-events: none;
    }

    /* Subtotal */
    .cart-subtotal-cell { font-weight:800; font-size:16px; color:#16a34a; white-space:nowrap; }

    /* Remove */
    .btn-remove-item {
        width:36px; height:36px;
        background:none; border:1px solid rgba(239,68,68,.2);
        border-radius:8px; cursor:pointer; color:#ef4444;
        display:grid; place-items:center;
        transition: background 150ms, border-color 150ms;
    }
    .btn-remove-item:hover { background:#fee2e2; border-color:#ef4444; }

    /* ── Sticky Footer Bar ── */
    .cart-sticky-bar {
        position: fixed;
        bottom: 0; left: 0; right: 0;
        background: #fff;
        border-top: 1px solid rgba(15,23,42,.1);
        box-shadow: 0 -8px 32px rgba(15,23,42,.1);
        z-index: 40;
        padding: 14px 24px;
    }
    .cart-sticky-inner {
        max-width: 1100px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
    }
    .cart-sticky-left {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    .select-all-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        color: #334155;
        user-select: none;
    }
    .select-all-wrap input { accent-color: #16a34a; width:18px; height:18px; cursor:pointer; }
    .cart-clear-btn {
        background: none; border: none; cursor: pointer;
        color: #ef4444; font-weight: 700; font-size: 13px;
        padding: 6px 10px; border-radius: 8px;
        transition: background 150ms;
    }
    .cart-clear-btn:hover { background: #fee2e2; }
    .cart-sticky-right {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    .cart-summary-text { font-size: 14px; color: #475569; }
    .cart-summary-text strong { color: #0f172a; font-size: 15px; }
    .cart-total-display {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }
    .cart-total-label-sm { font-size: 12px; color: #64748b; }
    .cart-total-amount { font-size: 22px; font-weight: 900; color: #16a34a; }
    .btn-checkout {
        padding: 12px 28px;
        border-radius: 999px;
        background: #16a34a; color: #fff;
        border: none; font-weight: 800; font-size: 15px;
        cursor: pointer; text-decoration: none;
        transition: background 150ms, transform 150ms;
        display: inline-flex; align-items: center; gap: 8px;
        font-family: inherit;
    }
    .btn-checkout:hover { background: #047857; transform: translateY(-1px); }
    .btn-checkout:disabled { background: #94a3b8; cursor: not-allowed; transform: none; }

    /* Alerts */
    .cart-alert {
        padding: 12px 16px; border-radius: 10px;
        font-weight: 700; font-size: 14px; margin-bottom: 16px;
    }
    .cart-alert-success { background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; }
    .cart-alert-error   { background:#fff1f2; color:#be123c; border:1px solid #fda4af; }

    @media (max-width: 768px) {
        .cart-header-row { display: none; }
        .cart-item-card {
            grid-template-columns: 20px 1fr 44px;
            grid-template-rows: auto auto auto;
        }
        .cart-item-card .cart-product-cell { grid-column: 2; }
        .cart-item-card .cart-price-cell   { display: none; }
        .cart-item-card .qty-stepper       { grid-column: 2; }
        .cart-item-card .cart-subtotal-cell{ grid-column: 2; }
        .cart-item-card .btn-remove-item   { grid-column: 3; grid-row: 1; }
    }
</style>
@endpush

@section('content')
<div class="cart-wrap">
    <h1 class="cart-title">🛒 My Cart</h1>
    <p class="cart-subtitle">Select items you want to purchase, then proceed to checkout.</p>

    @if(session('success'))
        <div class="cart-alert cart-alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="cart-alert cart-alert-error">⚠️ {{ session('error') }}</div>
    @endif

    @if ($cartItems->isEmpty())
        <div class="cart-empty">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" style="margin-bottom:16px;"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            <h2 style="margin:0 0 10px;color:#334155;">Your cart is empty</h2>
            <p style="color:#64748b;margin:0 0 24px;">Browse our products and add items to your cart.</p>
            <a href="{{ route('categories') }}" class="btn-checkout" style="display:inline-flex;">Browse Products</a>
        </div>
    @else
        {{-- Column Headers --}}
        <div class="cart-header-row">
            <span></span>
            <span>Product</span>
            <span>Unit Price</span>
            <span>Quantity</span>
            <span>Subtotal</span>
            <span></span>
        </div>

        {{-- Cart Items --}}
        <div id="cartItemsList">
            @foreach ($cartItems as $item)
                @if ($item->product)
                <div class="cart-item-card is-selected"
                     id="cart-row-{{ $item->id }}"
                     data-id="{{ $item->id }}"
                     data-price="{{ $item->product->price }}"
                     data-qty="{{ $item->quantity }}">

                    {{-- Checkbox --}}
                    <input type="checkbox"
                           class="cart-cb item-cb"
                           data-id="{{ $item->id }}"
                           checked
                           onchange="onItemCheck(this)" />

                    {{-- Product --}}
                    <div class="cart-product-cell">
                        <div class="cart-thumb">
                            @if($item->product->image_url)
                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                            @else
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#047857" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="4"/><path d="M12 8v8M8 12h8"/></svg>
                            @endif
                        </div>
                        <div style="min-width:0;">
                            <div class="cart-item-cat">{{ $item->product->category }}</div>
                            <div class="cart-item-name">{{ $item->product->name }}</div>
                            <div class="cart-item-price">₱{{ number_format($item->product->price, 2) }} each</div>
                        </div>
                    </div>

                    {{-- Unit Price --}}
                    <div class="cart-price-cell">₱{{ number_format($item->product->price, 2) }}</div>

                    {{-- Qty Stepper --}}
                    <div>
                        <div class="qty-stepper">
                            <button type="button" class="qty-stepper-btn"
                                    onclick="changeQty({{ $item->id }}, -1, {{ $item->product->stock }})">−</button>
                            <span class="qty-stepper-val" id="qty-{{ $item->id }}">{{ $item->quantity }}</span>
                            <button type="button" class="qty-stepper-btn"
                                    onclick="changeQty({{ $item->id }}, 1, {{ $item->product->stock }})">+</button>
                        </div>
                    </div>

                    {{-- Subtotal --}}
                    <div class="cart-subtotal-cell" id="sub-{{ $item->id }}">
                        ₱{{ number_format($item->product->price * $item->quantity, 2) }}
                    </div>

                    {{-- Remove --}}
                    <button type="button" class="btn-remove-item" title="Remove"
                            onclick="removeItem({{ $item->id }})">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6l-1 14H6L5 6"/>
                            <path d="M10 11v6M14 11v6"/>
                            <path d="M9 6V4h6v2"/>
                        </svg>
                    </button>
                </div>
                @endif
            @endforeach
        </div>

        <div id="cartEmptyMsg" style="display:none;" class="cart-empty">
            <h2 style="margin:0 0 10px;color:#334155;">Your cart is empty</h2>
            <a href="{{ route('categories') }}" class="btn-checkout" style="display:inline-flex;margin-top:12px;">Browse Products</a>
        </div>

        {{-- Sticky Bar --}}
        <div class="cart-sticky-bar">
            <div class="cart-sticky-inner">
                <div class="cart-sticky-left">
                    <label class="select-all-wrap">
                        <input type="checkbox" id="selectAll" checked onchange="toggleSelectAll(this)">
                        <span>Select All</span>
                    </label>
                    <form id="clearCartForm" action="{{ route('cart.clear') }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="button" class="cart-clear-btn"
                                onclick="if(confirm('Remove all items from cart?')) clearAllItems()">
                            Delete All
                        </button>
                    </form>
                </div>
                <div class="cart-sticky-right">
                    <div class="cart-summary-text">
                        Total (<span id="selectedCount">0</span> item(s))
                    </div>
                    <div class="cart-total-display">
                        <span class="cart-total-label-sm">Total Amount</span>
                        <span class="cart-total-amount" id="totalAmount">₱0.00</span>
                    </div>
                    <form method="POST" action="{{ route('cart.checkout-selected') }}" id="checkoutForm">
                        @csrf
                        <input type="hidden" name="selected_ids" id="selectedIdsInput" value="">
                        <button type="submit" class="btn-checkout" id="checkoutBtn" disabled>
                            Checkout
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
(function () {
    // Item data store: id => { price, qty, checked }
    const items = {};

    // Initialise from rendered rows
    document.querySelectorAll('.cart-item-card').forEach(row => {
        const id    = +row.dataset.id;
        const price = parseFloat(row.dataset.price);
        const qty   = parseInt(row.dataset.qty, 10);
        items[id]   = { price, qty, checked: true };
    });

    // ── Helpers ──────────────────────────────────────────────────────────────

    function fmt(n) {
        return '₱' + n.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    function refreshTotals() {
        let total = 0, count = 0;
        const selectedIds = [];

        Object.entries(items).forEach(([id, item]) => {
            if (item.checked) {
                total += item.price * item.qty;
                count += item.qty;
                selectedIds.push(id);
            }
        });

        document.getElementById('totalAmount').textContent   = fmt(total);
        document.getElementById('selectedCount').textContent = count;
        document.getElementById('selectedIdsInput').value    = selectedIds.join(',');

        const btn = document.getElementById('checkoutBtn');
        btn.disabled = selectedIds.length === 0;

        // Sync select-all checkbox state
        const allChecked  = Object.values(items).every(i => i.checked);
        const noneChecked = Object.values(items).every(i => !i.checked);
        const saEl        = document.getElementById('selectAll');
        if (saEl) {
            saEl.checked       = allChecked;
            saEl.indeterminate = !allChecked && !noneChecked;
        }

        // Show empty message if no items left
        const anyRows = document.querySelectorAll('.cart-item-card').length;
        document.getElementById('cartEmptyMsg').style.display = anyRows === 0 ? '' : 'none';
    }

    // ── Checkbox ─────────────────────────────────────────────────────────────

    window.onItemCheck = function (cb) {
        const id = +cb.dataset.id;
        items[id].checked = cb.checked;
        const row = document.getElementById('cart-row-' + id);
        row.classList.toggle('is-selected', cb.checked);
        refreshTotals();
    };

    window.toggleSelectAll = function (saEl) {
        document.querySelectorAll('.item-cb').forEach(cb => {
            cb.checked = saEl.checked;
            const id = +cb.dataset.id;
            if (items[id]) items[id].checked = saEl.checked;
            const row = document.getElementById('cart-row-' + id);
            row.classList.toggle('is-selected', saEl.checked);
        });
        refreshTotals();
    };

    // ── Quantity ─────────────────────────────────────────────────────────────

    window.changeQty = function (id, delta, maxStock) {
        const item = items[id];
        if (!item) return;

        const newQty = item.qty + delta;
        if (newQty < 1) { removeItem(id); return; }
        if (newQty > maxStock) { alert('Only ' + maxStock + ' in stock.'); return; }

        item.qty = newQty;
        document.getElementById('qty-' + id).textContent = newQty;
        document.getElementById('sub-' + id).textContent = fmt(item.price * newQty);

        // Update the data-qty attribute for server sync
        const row = document.getElementById('cart-row-' + id);
        if (row) row.dataset.qty = newQty;

        refreshTotals();

        // Persist to server (fire-and-forget)
        fetch(`/cart/update/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: `_method=PATCH&quantity=${newQty}`,
        });
    };

    // ── Remove ────────────────────────────────────────────────────────────────

    window.removeItem = function (id) {
        const row = document.getElementById('cart-row-' + id);
        if (!row) return;

        row.classList.add('is-removing');
        setTimeout(() => {
            row.remove();
            delete items[id];
            refreshTotals();
        }, 260);

        fetch(`/cart/remove/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: '_method=DELETE',
        });
    };

    window.clearAllItems = function () {
        document.querySelectorAll('.cart-item-card').forEach(row => {
            row.classList.add('is-removing');
        });
        setTimeout(() => {
            document.getElementById('clearCartForm').submit();
        }, 280);
    };

    // ── Checkout form guard ───────────────────────────────────────────────────

    document.getElementById('checkoutForm')?.addEventListener('submit', function (e) {
        const ids = document.getElementById('selectedIdsInput').value;
        if (!ids) { e.preventDefault(); alert('Please select at least one item to checkout.'); }
    });

    // ── Init ──────────────────────────────────────────────────────────────────
    refreshTotals();
})();
</script>
@endpush
