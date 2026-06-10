@extends('layouts.app')

@section('content')
<div class="admin-shell">
    @include('partials.admin-sidebar')

    <main class="admin-main">

        <div class="page-header">
            <div>
                <h1>Orders</h1>
                <p>Manage and track all customer orders</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success">✅ {{ session('success') }}</div>
        @endif

        {{-- Status Tabs --}}
        <div class="status-tabs">
            <a href="{{ route('admin.orders') }}"
               class="status-tab {{ !request('status') ? 'active' : '' }}">
                All <span class="tab-count">{{ $counts['all'] }}</span>
            </a>
            @foreach($statuses as $s)
            <a href="{{ route('admin.orders', ['status' => $s] + request()->except('status','page')) }}"
               class="status-tab {{ request('status') == $s ? 'active active-'.$s : '' }}">
                {{ $s }} <span class="tab-count">{{ $counts[$s] }}</span>
            </a>
            @endforeach
        </div>

        {{-- Search --}}
        <div class="content-card" style="padding:14px 18px;">
            <form method="GET" action="{{ route('admin.orders') }}" class="filters-form">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}" />
                @endif
                <div class="search-wrap">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search by customer name or order ID…" class="search-input" />
                </div>
                <button type="submit" class="btn-filter">Search</button>
                @if(request('search'))
                    <a href="{{ route('admin.orders', request()->only('status')) }}" class="btn-filter-clear">Clear</a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="content-card">
            <div class="card-header">
                <div>
                    <h2>All Orders</h2>
                    <p>{{ $orders->total() }} order(s) found</p>
                </div>
            </div>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Products</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th style="text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="td-id">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="td-customer">
                                <span class="customer-name">{{ $order->first_name }} {{ $order->last_name }}</span>
                                @if($order->user)
                                    <span class="customer-email">{{ $order->user->email }}</span>
                                @endif
                            </td>
                            <td class="td-products">
                                @foreach($order->items->take(2) as $item)
                                    <span class="product-pill">{{ $item->product->name ?? 'Deleted Product' }}</span>
                                @endforeach
                                @if($order->items->count() > 2)
                                    <span class="product-more">+{{ $order->items->count() - 2 }} more</span>
                                @endif
                            </td>
                            <td class="td-qty">{{ $order->items->sum('quantity') }}</td>
                            <td class="td-total">₱{{ number_format($order->total, 2) }}</td>
                            <td>
                                <span class="payment-badge payment-{{ strtolower(str_replace(' ','',$order->payment_method)) }}">
                                    {{ strtoupper($order->payment_method) }}
                                </span>
                            </td>
                            <td class="td-date">
                                <span>{{ $order->created_at->format('M d, Y') }}</span>
                                <span class="td-time">{{ $order->created_at->format('h:i A') }}</span>
                            </td>
                            <td>
                                <span class="order-status order-{{ strtolower($order->status) }}">{{ $order->status }}</span>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <button class="btn-view" onclick="openOrderView({{ $order->id }})">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        View
                                    </button>
                                    <button class="btn-status-upd" onclick="openStatusModal({{ $order->id }}, '{{ $order->status }}')">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                                        Update
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="td-empty">No orders found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($orders->hasPages())
            <div class="pagination-wrap">
                <span class="pag-info">Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }} of {{ $orders->total() }}</span>
                {{ $orders->withQueryString()->links() }}
            </div>
            @endif
        </div>

    </main>
</div>

{{-- Order Detail Modal --}}
<div id="orderViewModal" class="modal-overlay" style="display:none;" onclick="if(event.target===this)this.style.display='none'">
    <div class="order-modal-box">
        <div class="modal-header-bar">
            <div>
                <h3 id="ovm-title">Order Details</h3>
                <p id="ovm-date" class="ovm-subtitle"></p>
            </div>
            <button class="modal-close-x" onclick="document.getElementById('orderViewModal').style.display='none'">✕</button>
        </div>
        <div class="ovm-body">
            <div class="ovm-section">
                <div class="ovm-section-label">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Customer Information
                </div>
                <div class="ovm-grid-2">
                    <div class="ovm-item"><span>Full Name</span><strong id="ovm-name"></strong></div>
                    <div class="ovm-item"><span>Email</span><strong id="ovm-email"></strong></div>
                    <div class="ovm-item"><span>Phone</span><strong id="ovm-phone"></strong></div>
                    <div class="ovm-item ovm-full"><span>Delivery Address</span><strong id="ovm-address"></strong></div>
                </div>
            </div>
            <div class="ovm-section">
                <div class="ovm-section-label">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M9 17H5a2 2 0 0 0-2 2v0a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v0a2 2 0 0 0-2-2h-4"/><path d="M12 3v14"/><path d="M8 7l4-4 4 4"/></svg>
                    Order Information
                </div>
                <div class="ovm-grid-3">
                    <div class="ovm-item"><span>Payment Method</span><strong id="ovm-payment"></strong></div>
                    <div class="ovm-item"><span>Order Status</span><strong id="ovm-status"></strong></div>
                    <div class="ovm-item"><span>Order Date</span><strong id="ovm-orderdate"></strong></div>
                    <div class="ovm-item ovm-full"><span>Notes</span><strong id="ovm-notes"></strong></div>
                </div>
            </div>
            <div class="ovm-section">
                <div class="ovm-section-label">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M21 16V8a2 2 0 0 0-1-1.73L13 2.27a2 2 0 0 0-2 0L4 6.27A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                    Items Ordered
                </div>
                <table class="items-table">
                    <thead><tr><th>Product</th><th>Unit Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
                    <tbody id="ovm-items"></tbody>
                    <tfoot><tr><td colspan="3" class="total-label">Total</td><td class="total-val" id="ovm-total"></td></tr></tfoot>
                </table>
            </div>
        </div>
        <div class="ovm-footer">
            <button class="btn-cancel" onclick="document.getElementById('orderViewModal').style.display='none'">Close</button>
            <button class="btn-status-upd" id="ovm-update-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                Update Status
            </button>
        </div>
    </div>
</div>

{{-- Update Status Modal --}}
<div id="statusModal" class="modal-overlay" style="display:none;" onclick="if(event.target===this)this.style.display='none'">
    <div class="status-modal-box">
        <div class="modal-header-bar">
            <h3 id="sm-title">Update Order Status</h3>
            <button class="modal-close-x" onclick="document.getElementById('statusModal').style.display='none'">✕</button>
        </div>
        <form id="statusForm" method="POST">
            @csrf @method('PATCH')
            <div class="status-options" id="statusOptions"></div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="document.getElementById('statusModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn-save">Save Status</button>
            </div>
        </form>
    </div>
</div>

<script id="ordersJson" type="application/json">
{!! json_encode($orders->map(fn($o) => [
    'id'             => $o->id,
    'customer_name'  => $o->first_name . ' ' . $o->last_name,
    'email'          => $o->user->email ?? '—',
    'phone'          => $o->phone,
    'address'        => $o->address,
    'payment_method' => $o->payment_method,
    'notes'          => $o->notes,
    'total'          => $o->total,
    'status'         => $o->status,
    'created_at'     => $o->created_at->format('M d, Y h:i A'),
    'items'          => $o->items->map(fn($i) => [
        'name'     => $i->product->name ?? 'Deleted Product',
        'price'    => $i->price,
        'quantity' => $i->quantity,
    ])->values(),
])->values()) !!}
</script>

<style>
    * { box-sizing: border-box; }
    body { margin: 0; font-family: 'Inter', system-ui, sans-serif; background: #f1f5f9; }
    .admin-shell { display: flex; min-height: 100vh; }
    .admin-main  { margin-left: 260px; padding: 28px 30px; flex: 1; display: flex; flex-direction: column; gap: 22px; }

    .page-header { display: flex; justify-content: space-between; align-items: center; }
    .page-header h1 { margin: 0 0 4px; font-size: 1.8rem; font-weight: 800; color: #0f172a; }
    .page-header p  { margin: 0; color: #64748b; font-size: 0.9rem; }

    .alert-success { padding: 12px 16px; background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; border-radius: 10px; font-weight: 600; font-size: 0.9rem; }

    /* Status Tabs */
    .status-tabs { display: flex; flex-wrap: wrap; gap: 8px; }
    .status-tab  { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #fff; border: 1.5px solid #e2e8f0; border-radius: 20px; color: #475569; font-size: 0.84rem; font-weight: 600; text-decoration: none; transition: all 0.18s; }
    .status-tab:hover { border-color: #6366f1; color: #6366f1; }
    .status-tab.active { background: #6366f1; color: #fff; border-color: transparent; box-shadow: 0 2px 8px rgba(99,102,241,0.35); }
    .status-tab.active-Pending    { background: #f59e0b; box-shadow: 0 2px 8px rgba(245,158,11,0.35); }
    .status-tab.active-Processing { background: #6366f1; box-shadow: 0 2px 8px rgba(99,102,241,0.35); }
    .status-tab.active-Shipped    { background: #0ea5e9; box-shadow: 0 2px 8px rgba(14,165,233,0.35); }
    .status-tab.active-Delivered  { background: #10b981; box-shadow: 0 2px 8px rgba(16,185,129,0.35); }
    .status-tab.active-Cancelled  { background: #e11d48; box-shadow: 0 2px 8px rgba(225,29,72,0.35); }
    .tab-count { background: rgba(255,255,255,0.25); border-radius: 20px; padding: 1px 7px; font-size: 0.75rem; }
    .status-tab:not(.active) .tab-count { background: #f1f5f9; color: #64748b; }

    /* Search bar */
    .filters-form { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; }
    .search-wrap  { position: relative; flex: 1; min-width: 220px; }
    .search-icon  { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none; }
    .search-input { width: 100%; padding: 9px 12px 9px 36px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.88rem; color: #0f172a; background: #f8fafc; }
    .search-input:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
    .btn-filter       { padding: 9px 18px; background: #6366f1; color: #fff; border: none; border-radius: 8px; font-weight: 600; font-size: 0.88rem; cursor: pointer; }
    .btn-filter:hover { background: #4f46e5; }
    .btn-filter-clear { padding: 9px 14px; background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.88rem; text-decoration: none; font-weight: 600; }

    /* Content card */
    .content-card { background: #fff; border-radius: 14px; border: 1px solid #e2e8f0; box-shadow: 0 1px 6px rgba(0,0,0,0.04); overflow: hidden; }
    .card-header  { padding: 20px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
    .card-header h2 { margin: 0 0 3px; font-size: 1.1rem; color: #0f172a; font-weight: 700; }
    .card-header p  { margin: 0; color: #64748b; font-size: 0.85rem; }

    /* Table */
    .table-wrap  { overflow-x: auto; }
    .data-table  { width: 100%; border-collapse: collapse; font-size: 0.87rem; }
    .data-table th { padding: 13px 14px; text-align: left; background: #f8fafc; color: #475569; font-weight: 700; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; white-space: nowrap; }
    .data-table td { padding: 12px 14px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; color: #334155; }
    .data-table tbody tr:hover { background: #f8fafc; }
    .td-empty { text-align: center; color: #94a3b8; padding: 48px 0 !important; }

    .td-id { font-weight: 800; color: #6366f1; font-size: 0.85rem; white-space: nowrap; }
    .td-customer .customer-name  { display: block; font-weight: 700; color: #0f172a; }
    .td-customer .customer-email { display: block; font-size: 0.78rem; color: #94a3b8; }
    .td-products { max-width: 200px; }
    .product-pill { display: inline-block; background: #eef2ff; color: #4338ca; font-size: 0.74rem; font-weight: 600; padding: 2px 8px; border-radius: 20px; margin: 2px 2px 2px 0; white-space: nowrap; max-width: 120px; overflow: hidden; text-overflow: ellipsis; vertical-align: middle; }
    .product-more { font-size: 0.74rem; color: #94a3b8; font-weight: 600; }
    .td-qty   { font-weight: 700; color: #334155; text-align: center; }
    .td-total { font-weight: 800; color: #0f172a; white-space: nowrap; }
    .td-date span  { display: block; font-weight: 600; color: #334155; white-space: nowrap; }
    .td-time { font-size: 0.76rem; color: #94a3b8 !important; font-weight: 400 !important; }

    .payment-badge { font-size: 0.75rem; font-weight: 700; padding: 3px 9px; border-radius: 6px; white-space: nowrap; }
    .payment-cod  { background: #fef3c7; color: #92400e; }
    .payment-gcash { background: #dbeafe; color: #1d4ed8; }
    .payment-card, .payment-creditcard { background: #ede9fe; color: #5b21b6; }

    .order-status { font-size: 0.78rem; font-weight: 700; padding: 4px 11px; border-radius: 20px; white-space: nowrap; }
    .order-pending    { background: #fef3c7; color: #92400e; }
    .order-processing { background: #eef2ff; color: #4338ca; }
    .order-shipped    { background: #e0f2fe; color: #0369a1; }
    .order-delivered  { background: #dcfce7; color: #166534; }
    .order-cancelled  { background: #fee2e2; color: #991b1b; }

    .action-btns { display: flex; gap: 6px; }
    .btn-view { display:inline-flex;align-items:center;gap:5px;padding:6px 12px;background:#e0f2fe;color:#0369a1;border:none;border-radius:7px;font-weight:600;font-size:0.8rem;cursor:pointer;transition:all 0.2s;white-space:nowrap; }
    .btn-view:hover { background:#bae6fd; }
    .btn-status-upd { display:inline-flex;align-items:center;gap:5px;padding:6px 12px;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;border:none;border-radius:7px;font-weight:600;font-size:0.8rem;cursor:pointer;transition:all 0.2s;white-space:nowrap; }
    .btn-status-upd:hover { transform:translateY(-1px);box-shadow:0 4px 12px rgba(99,102,241,0.4); }

    .pagination-wrap { padding: 16px 20px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
    .pag-info { font-size: 0.8rem; color: #94a3b8; font-weight: 600; }

    /* Modals */
    .modal-overlay    { position:fixed;inset:0;background:rgba(0,0,0,0.45);display:flex;align-items:flex-start;justify-content:center;z-index:9999;backdrop-filter:blur(3px);overflow-y:auto;padding:32px 16px; }
    .order-modal-box  { background:#fff;border-radius:16px;width:100%;max-width:660px;box-shadow:0 20px 60px rgba(0,0,0,0.2);margin:auto;overflow:hidden;display:flex;flex-direction:column; }
    .status-modal-box { background:#fff;border-radius:16px;width:100%;max-width:400px;box-shadow:0 20px 60px rgba(0,0,0,0.2);margin:auto;overflow:hidden; }

    .modal-header-bar { display:flex;justify-content:space-between;align-items:flex-start;padding:18px 22px;border-bottom:1px solid #f1f5f9; }
    .modal-header-bar h3 { margin:0;font-size:1.05rem;font-weight:800;color:#0f172a; }
    .ovm-subtitle { margin:2px 0 0;font-size:0.8rem;color:#94a3b8; }
    .modal-close-x { background:none;border:none;font-size:1.1rem;cursor:pointer;color:#94a3b8;width:30px;height:30px;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0; }
    .modal-close-x:hover { background:#f1f5f9;color:#475569; }

    .ovm-body { padding:20px 22px;display:flex;flex-direction:column;gap:16px;max-height:68vh;overflow-y:auto; }
    .ovm-section { background:#f8fafc;border:1px solid #f1f5f9;border-radius:12px;padding:16px;display:flex;flex-direction:column;gap:12px; }
    .ovm-section-label { display:flex;align-items:center;gap:6px;font-size:0.72rem;font-weight:800;text-transform:uppercase;letter-spacing:0.06em;color:#6366f1; }
    .ovm-grid-2 { display:grid;grid-template-columns:1fr 1fr;gap:10px; }
    .ovm-grid-3 { display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px; }
    .ovm-item { display:flex;flex-direction:column;gap:2px; }
    .ovm-item.ovm-full, .ovm-full { grid-column:1/-1; }
    .ovm-item span   { font-size:0.72rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.5px; }
    .ovm-item strong { font-size:0.88rem;font-weight:700;color:#0f172a; }

    .items-table { width:100%;border-collapse:collapse;font-size:0.85rem; }
    .items-table th { padding:8px 12px;text-align:left;background:#f1f5f9;color:#475569;font-size:0.74rem;font-weight:700;text-transform:uppercase;letter-spacing:0.4px; }
    .items-table td { padding:9px 12px;border-bottom:1px solid #f1f5f9;color:#334155; }
    .items-table tfoot td { border-bottom:none;border-top:2px solid #e2e8f0;padding-top:10px; }
    .total-label { text-align:right;font-weight:700;color:#475569;font-size:0.85rem; }
    .total-val   { font-weight:800;color:#0f172a;font-size:1rem; }

    .ovm-footer    { display:flex;gap:10px;justify-content:flex-end;padding:14px 22px;border-top:1px solid #f1f5f9;background:#f8fafc; }
    .modal-actions { display:flex;gap:10px;justify-content:flex-end;padding:14px 22px;border-top:1px solid #f1f5f9; }
    .btn-cancel    { padding:9px 18px;background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;border-radius:8px;font-weight:600;font-size:0.88rem;cursor:pointer; }
    .btn-cancel:hover { background:#e2e8f0; }
    .btn-save      { padding:9px 20px;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;border:none;border-radius:8px;font-weight:700;font-size:0.88rem;cursor:pointer;transition:all 0.2s; }
    .btn-save:hover { box-shadow:0 4px 12px rgba(99,102,241,0.4); }

    .status-options { display:flex;flex-direction:column;gap:0;padding:8px 0; }
    .status-option  { display:flex;align-items:center;gap:12px;padding:13px 22px;cursor:pointer;transition:background 0.15s;border:none;background:none;width:100%;text-align:left; }
    .status-option:hover { background:#f8fafc; }
    .status-option input[type="radio"] { width:17px;height:17px;accent-color:#6366f1;flex-shrink:0; }
    .status-option-dot  { width:10px;height:10px;border-radius:50%;flex-shrink:0; }
    .dot-pending    { background:#f59e0b; }
    .dot-processing { background:#6366f1; }
    .dot-shipped    { background:#0ea5e9; }
    .dot-delivered  { background:#10b981; }
    .dot-cancelled  { background:#e11d48; }
    .status-option-label { font-weight:700;font-size:0.9rem;color:#0f172a; }
    .status-option-hint  { font-size:0.76rem;color:#94a3b8; }

    @media (max-width: 768px) {
        .admin-main { margin-left:0;padding:16px; }
        .ovm-grid-2, .ovm-grid-3 { grid-template-columns:1fr; }
    }
</style>

<script>
    const ordersData = JSON.parse(document.getElementById('ordersJson').textContent);

    const statusMeta = {
        'Pending':    { dot: 'dot-pending',    hint: 'Order received, awaiting processing' },
        'Processing': { dot: 'dot-processing', hint: 'Order is being prepared' },
        'Shipped':    { dot: 'dot-shipped',    hint: 'Order is on the way' },
        'Delivered':  { dot: 'dot-delivered',  hint: 'Order successfully delivered' },
        'Cancelled':  { dot: 'dot-cancelled',  hint: 'Order has been cancelled' },
    };

    function openOrderView(id) {
        const o = ordersData.find(x => x.id === id);
        if (!o) return;

        document.getElementById('ovm-title').textContent    = 'Order #' + String(o.id).padStart(5, '0');
        document.getElementById('ovm-date').textContent     = o.created_at;
        document.getElementById('ovm-name').textContent     = o.customer_name;
        document.getElementById('ovm-email').textContent    = o.email;
        document.getElementById('ovm-phone').textContent    = o.phone;
        document.getElementById('ovm-address').textContent  = o.address;
        document.getElementById('ovm-payment').textContent  = o.payment_method.toUpperCase();
        document.getElementById('ovm-orderdate').textContent = o.created_at;
        document.getElementById('ovm-notes').textContent    = o.notes || '—';

        const statusEl = document.getElementById('ovm-status');
        statusEl.textContent = o.status;
        statusEl.className = 'order-status order-' + o.status.toLowerCase();

        const tbody = document.getElementById('ovm-items');
        tbody.innerHTML = o.items.map(item => `
            <tr>
                <td>${item.name}</td>
                <td>₱${parseFloat(item.price).toFixed(2)}</td>
                <td style="text-align:center">${item.quantity}</td>
                <td style="font-weight:700">₱${(item.price * item.quantity).toFixed(2)}</td>
            </tr>
        `).join('');
        document.getElementById('ovm-total').textContent = '₱' + parseFloat(o.total).toFixed(2);

        document.getElementById('ovm-update-btn').onclick = () => {
            document.getElementById('orderViewModal').style.display = 'none';
            openStatusModal(o.id, o.status);
        };

        document.getElementById('orderViewModal').style.display = 'flex';
    }

    function openStatusModal(id, currentStatus) {
        document.getElementById('sm-title').textContent = 'Update Order #' + String(id).padStart(5, '0');
        document.getElementById('statusForm').action    = `/admin/orders/${id}/status`;

        const statuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
        document.getElementById('statusOptions').innerHTML = statuses.map(s => `
            <label class="status-option">
                <input type="radio" name="status" value="${s}" ${s === currentStatus ? 'checked' : ''} />
                <span class="status-option-dot ${statusMeta[s].dot}"></span>
                <span>
                    <span class="status-option-label">${s}</span><br>
                    <span class="status-option-hint">${statusMeta[s].hint}</span>
                </span>
            </label>
        `).join('');

        document.getElementById('statusModal').style.display = 'flex';
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            document.getElementById('orderViewModal').style.display = 'none';
            document.getElementById('statusModal').style.display = 'none';
        }
    });
</script>
@endsection
