@extends('layouts.app')

@section('content')
<div class="admin-shell">
    @include('partials.admin-sidebar')

    <main class="admin-main">
        <div class="page-header">
            <div>
                <h1>Dashboard</h1>
                <p>Manage your pharmacy inventory and operations</p>
            </div>
            <div class="header-badge">
                <span id="liveIndicator" class="live-dot"></span>
                <span id="liveLabel" style="font-size:0.8rem;color:#64748b;font-weight:600;">Live</span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success">✅ {{ session('success') }}</div>
        @endif

        <div class="stats-grid">
            <div class="stat-card" style="--accent:#6366f1;">
                <div class="stat-icon" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="26" height="26">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73L13 2.27a2 2 0 0 0-2 0L4 6.27A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    </svg>
                </div>
                <div class="stat-body">
                    <p>Total Products</p>
                    <strong>{{ $totalProducts }}</strong>
                </div>
            </div>

            <div class="stat-card" style="--accent:#0ea5e9;">
                <div class="stat-icon" style="background:linear-gradient(135deg,#0ea5e9,#06b6d4);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="26" height="26">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/>
                    </svg>
                </div>
                <div class="stat-body">
                    <p>Total Stock</p>
                    <strong>{{ $totalStock }}</strong>
                </div>
            </div>

            <div class="stat-card" style="--accent:#10b981;">
                <div class="stat-icon" style="background:linear-gradient(135deg,#10b981,#34d399);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="26" height="26">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <div class="stat-body">
                    <p>Active Users</p>
                    <strong>{{ $totalUsers }}</strong>
                </div>
            </div>

            <div class="stat-card" style="--accent:#f43f5e;">
                <div class="stat-icon" style="background:linear-gradient(135deg,#f43f5e,#fb7185);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="26" height="26">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                </div>
                <div class="stat-body">
                    <p>Out of Stock</p>
                    <strong>{{ $outOfStock }}</strong>
                </div>
            </div>
        </div>

        <div class="content-card">
            <div class="card-header">
                <div>
                    <h2>Stock Inventory</h2>
                    <p>Manage product stock and image paths</p>
                </div>
            </div>

            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Stock</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr id="row-{{ $product->id }}">
                                <td class="cell-product">
                                    <img src="{{ $product->image_url ?? asset('images/default-avatar.svg') }}"
                                         alt="{{ $product->name }}" />
                                    <span>{{ $product->name }}</span>
                                </td>
                                <td><span class="badge">{{ $product->category }}</span></td>
                                <td>
                                    <span class="stock-val {{ $product->stock == 0 ? 'stock-zero' : ($product->stock < 10 ? 'stock-low' : 'stock-ok') }}">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td>
                                    <span class="img-path">{{ $product->image ? basename($product->image) : '—' }}</span>
                                </td>
                                <td>
                                    <button class="btn-edit" onclick="openEdit({{ $product->id }}, {{ $product->stock }}, '{{ addslashes($product->image ?? '') }}')">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="empty-state">No products found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrap">
                <span class="pag-info">
                    Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }}
                </span>
                {{ $products->links() }}
            </div>
        </div>
    </main>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal-overlay" style="display:none;" onclick="if(event.target===this)closeEdit()">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Edit Product</h3>
            <button class="modal-close" onclick="closeEdit()">✕</button>
        </div>
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Stock</label>
                <input type="number" name="stock" id="editStock" min="0" required class="form-input" />
            </div>
            <div class="form-group">
                <label>Upload Image</label>
                <input type="file" name="image_upload" id="editImageUpload" accept="image/*" class="form-input" />
            </div>
            <div class="form-group">
                <label>Or Image Path / URL</label>
                <input type="text" name="image" id="editImage" class="form-input" placeholder="e.g. images/product.jpg" />
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeEdit()">Cancel</button>
                <button type="submit" class="btn-save">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<style>
    * { box-sizing: border-box; }

    body { margin: 0; font-family: 'Inter', system-ui, sans-serif; background: #f1f5f9; }

    .admin-shell {
        display: flex;
        min-height: 100vh;
    }

    .admin-main {
        margin-left: 260px;
        padding: 28px 30px;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 22px;
    }

    /* Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-header h1 {
        margin: 0 0 4px;
        font-size: 1.8rem;
        font-weight: 800;
        color: #0f172a;
    }

    .page-header p { margin: 0; color: #64748b; font-size: 0.9rem; }

    .header-badge {
        display: flex;
        align-items: center;
        gap: 6px;
        background: #fff;
        border: 1px solid #e2e8f0;
        padding: 6px 14px;
        border-radius: 20px;
    }

    .live-dot {
        width: 8px; height: 8px;
        background: #10b981;
        border-radius: 50%;
        animation: pulse 1.5s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.4); }
    }

    /* Alert */
    .alert-success {
        padding: 12px 16px;
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    /* Stats */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }

    .stat-card {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 20px;
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 6px rgba(0,0,0,0.04);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    }

    .stat-icon {
        width: 52px; height: 52px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: #fff;
        flex-shrink: 0;
    }

    .stat-body p { margin: 0 0 3px; color: #64748b; font-size: 0.82rem; font-weight: 500; }

    .stat-body strong {
        display: block;
        font-size: 1.7rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
    }

    /* Content Card */
    .content-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 6px rgba(0,0,0,0.04);
        overflow: hidden;
    }

    .card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #f1f5f9;
    }

    .card-header h2 { margin: 0 0 3px; font-size: 1.1rem; color: #0f172a; font-weight: 700; }
    .card-header p { margin: 0; color: #64748b; font-size: 0.85rem; }

    /* Table */
    .table-wrap { overflow-x: auto; }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.88rem;
    }

    .data-table th {
        padding: 13px 18px;
        text-align: left;
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0;
    }

    .data-table td {
        padding: 14px 18px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        vertical-align: middle;
    }

    .data-table tbody tr:hover { background: #f8fafc; }

    .cell-product {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        color: #0f172a;
    }

    .cell-product img {
        width: 38px; height: 38px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .badge {
        padding: 3px 10px;
        background: #eff6ff;
        color: #1d4ed8;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
    }

    .stock-val {
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.82rem;
    }
    .stock-ok   { background: #f0fdf4; color: #16a34a; }
    .stock-low  { background: #fffbeb; color: #b45309; }
    .stock-zero { background: #fff1f2; color: #e11d48; }

    .img-path {
        font-size: 0.8rem;
        color: #94a3b8;
        max-width: 160px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: block;
    }

    .btn-edit {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 7px 14px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.82rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-edit:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(99,102,241,0.4);
    }

    .empty-state {
        text-align: center;
        padding: 40px !important;
        color: #94a3b8;
    }

    .pagination-wrap {
        padding: 16px 20px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .pag-info {
        font-size: 0.8rem;
        color: #94a3b8;
        font-weight: 600;
    }

    /* Modal */
    .modal-overlay {
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.45);
        display: flex; align-items: center; justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(3px);
    }

    .modal-box {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        width: 100%;
        max-width: 420px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .modal-header h3 { margin: 0; font-size: 1.1rem; color: #0f172a; }

    .modal-close {
        background: none; border: none;
        font-size: 1.1rem; cursor: pointer;
        color: #94a3b8; padding: 2px 6px;
        border-radius: 6px;
    }
    .modal-close:hover { background: #f1f5f9; color: #475569; }

    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 6px; }

    .form-input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.9rem;
        color: #0f172a;
        background: #f8fafc;
        transition: all 0.2s;
    }

    .form-input:focus {
        outline: none;
        border-color: #6366f1;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
    }

    .modal-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 20px;
    }

    .btn-cancel {
        padding: 9px 18px;
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.88rem;
        cursor: pointer;
    }
    .btn-cancel:hover { background: #e2e8f0; }

    .btn-save {
        padding: 9px 18px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.88rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-save:hover { box-shadow: 0 4px 12px rgba(99,102,241,0.4); }

    @media (max-width: 1200px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 768px) {
        .admin-main { margin-left: 0; padding: 16px; }
        .stats-grid { grid-template-columns: 1fr 1fr; }
    }
</style>

<script>
    function openEdit(id, stock, image) {
        document.getElementById('editForm').action = `/admin/products/${id}`;
        document.getElementById('editStock').value = stock;
        document.getElementById('editImage').value = image;
        document.getElementById('editModal').style.display = 'flex';
    }

    function closeEdit() {
        document.getElementById('editModal').style.display = 'none';
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeEdit();
    });
</script>
@endsection
