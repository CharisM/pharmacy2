@extends('layouts.app')

@section('content')
<div class="admin-shell">
    @include('partials.admin-sidebar')

    <main class="admin-main">
        <div class="page-header">
            <div>
                <h1>Products</h1>
                <p>Manage your pharmacy product inventory</p>
            </div>
            <button class="btn-add-product" onclick="document.getElementById('addModal').style.display='flex'">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Product
            </button>
        </div>

        @if(session('success'))
            <div class="alert-success">✅ {{ session('success') }}</div>
        @endif

        <!-- Filters -->
        <div class="filters-bar">
            <form method="GET" action="{{ route('admin.products') }}" class="filters-form">
                <div class="search-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" class="search-icon"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products…" class="search-input" />
                </div>
                <select name="category" class="filter-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                <select name="stock_status" class="filter-select">
                    <option value="">All Stock</option>
                    <option value="in"   {{ request('stock_status') == 'in'   ? 'selected' : '' }}>In Stock</option>
                    <option value="low"  {{ request('stock_status') == 'low'  ? 'selected' : '' }}>Low Stock (&lt;10)</option>
                    <option value="out"  {{ request('stock_status') == 'out'  ? 'selected' : '' }}>Out of Stock</option>
                </select>
                <button type="submit" class="btn-filter">Filter</button>
                @if(request()->hasAny(['search','category','stock_status']))
                    <a href="{{ route('admin.products') }}" class="btn-clear">Clear</a>
                @endif
            </form>
        </div>

        <!-- Stats Row -->
        <div class="mini-stats">
            <div class="mini-stat"><span>Total</span><strong>{{ $totalProducts }}</strong></div>
            <div class="mini-stat in-stock"><span>In Stock</span><strong>{{ $inStock }}</strong></div>
            <div class="mini-stat low-stock"><span>Low Stock</span><strong>{{ $lowStock }}</strong></div>
            <div class="mini-stat out-stock"><span>Out of Stock</span><strong>{{ $outOfStock }}</strong></div>
        </div>

        <!-- Products Table -->
        <div class="content-card">
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th style="text-align:center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $i => $product)
                        <tr>
                            <td class="td-num">{{ $products->firstItem() + $i }}</td>
                            <td class="cell-product">
                                <img src="{{ $product->image_url ?? asset('images/default-avatar.svg') }}" alt="{{ $product->name }}" />
                                <div>
                                    <span class="product-name">{{ $product->name }}</span>
                                    @if($product->description)
                                        <span class="product-desc">{{ Str::limit($product->description, 50) }}</span>
                                    @endif
                                </div>
                            </td>
                            <td><span class="badge-cat">{{ $product->category }}</span></td>
                            <td class="td-price">
                                ₱{{ number_format($product->price, 2) }}
                                @if($product->old_price)
                                    <span class="old-price">₱{{ number_format($product->old_price, 2) }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="stock-val {{ $product->stock == 0 ? 'stock-zero' : ($product->stock < 10 ? 'stock-low' : 'stock-ok') }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td>
                                @if($product->stock == 0)
                                    <span class="status-badge status-out">Out of Stock</span>
                                @elseif($product->stock < 10)
                                    <span class="status-badge status-low">Low Stock</span>
                                @else
                                    <span class="status-badge status-in">In Stock</span>
                                @endif
                            </td>
                            <td style="text-align:center">
                                @if($product->is_featured)
                                    <span class="badge-featured">⭐ Featured</span>
                                @else
                                    <span class="badge-normal">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-btns">
                                    <button class="btn-view" onclick="openView({{ $product->id }})">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        View
                                    </button>
                                    <button class="btn-edit" onclick="openEdit(
                                        {{ $product->id }},
                                        '{{ addslashes($product->name) }}',
                                        '{{ addslashes($product->category) }}',
                                        {{ $product->price }},
                                        {{ $product->old_price ?? 'null' }},
                                        {{ $product->stock }},
                                        '{{ addslashes($product->image ?? '') }}',
                                        {{ $product->is_featured ? 'true' : 'false' }},
                                        '{{ addslashes($product->description ?? '') }}'
                                    )">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        Edit
                                    </button>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                          onsubmit="return confirm('Delete \'{{ addslashes($product->name) }}\'? This cannot be undone.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-delete">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="td-empty">No products found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($products->hasPages())
            <div class="pagination-wrap">{{ $products->withQueryString()->links() }}</div>
            @endif
        </div>
    </main>
</div>

<!-- View Product Modal -->
<div id="viewModal" class="modal-overlay" style="display:none;" onclick="if(event.target===this)this.style.display='none'">
    <div class="view-modal-box">
        <div class="modal-header-bar">
            <h3>Product Details</h3>
            <button class="modal-close-x" onclick="document.getElementById('viewModal').style.display='none'">✕</button>
        </div>
        <div class="view-body">
            <div class="view-img-wrap">
                <img id="viewImg" src="" alt="" />
            </div>
            <div class="view-details">
                <h2 id="viewName"></h2>
                <p id="viewDesc" class="view-desc"></p>
                <div class="view-grid">
                    <div class="view-item"><span>Category</span><strong id="viewCategory"></strong></div>
                    <div class="view-item"><span>Price</span><strong id="viewPrice"></strong></div>
                    <div class="view-item"><span>Old Price</span><strong id="viewOldPrice"></strong></div>
                    <div class="view-item"><span>Stock</span><strong id="viewStock"></strong></div>
                    <div class="view-item"><span>Status</span><strong id="viewStatus"></strong></div>
                    <div class="view-item"><span>Featured</span><strong id="viewFeatured"></strong></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<div id="addModal" class="modal-overlay" style="display:none;" onclick="if(event.target===this)this.style.display='none'">
    <div class="modal-box">
        <div class="modal-header-bar">
            <h3>Add New Product</h3>
            <button class="modal-close-x" onclick="document.getElementById('addModal').style.display='none'">✕</button>
        </div>
        <form method="POST" action="{{ route('admin.products.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group full">
                    <label>Product Name <span class="req">*</span></label>
                    <input type="text" name="name" required class="form-input" placeholder="e.g. Biogesic 500mg" />
                </div>
                <div class="form-group">
                    <label>Category <span class="req">*</span></label>
                    <select name="category" required class="form-input">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Stock <span class="req">*</span></label>
                    <input type="number" name="stock" min="0" required class="form-input" placeholder="0" />
                </div>
                <div class="form-group">
                    <label>Price (₱) <span class="req">*</span></label>
                    <input type="number" name="price" min="0" step="0.01" required class="form-input" placeholder="0.00" />
                </div>
                <div class="form-group">
                    <label>Old Price (₱) <span class="opt">(optional)</span></label>
                    <input type="number" name="old_price" min="0" step="0.01" class="form-input" placeholder="0.00" />
                </div>
                <div class="form-group full">
                    <label>Description <span class="opt">(optional)</span></label>
                    <textarea name="description" rows="3" class="form-input" style="resize:vertical"></textarea>
                </div>
                <div class="form-group full" id="addImgPreviewWrap" style="display:none;">
                    <img id="addImgPreview" src="" alt="preview" style="max-height:90px;border-radius:8px;border:1px solid #e2e8f0;object-fit:cover;" />
                </div>
                <div class="form-group full">
                    <label>Image URL <span class="opt">(optional)</span></label>
                    <input type="url" name="image" id="addImage" class="form-input" placeholder="https://example.com/image.jpg" oninput="previewImage('add', this.value)" />
                </div>
                <div class="form-group full toggle-row">
                    <input type="checkbox" name="is_featured" value="1" id="addFeatured" />
                    <label for="addFeatured">⭐ Show in Featured Products</label>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="document.getElementById('addModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn-save">Add Product</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Product Modal -->
<div id="editModal" class="modal-overlay" style="display:none;" onclick="if(event.target===this)closeEdit()">
    <div class="modal-box">
        <div class="modal-header-bar">
            <h3>Edit Product</h3>
            <button class="modal-close-x" onclick="closeEdit()">✕</button>
        </div>
        <form id="editForm" method="POST">
            @csrf @method('PUT')
            <div class="form-grid">
                <div class="form-group full">
                    <label>Product Name <span class="req">*</span></label>
                    <input type="text" name="name" id="editName" required class="form-input" />
                </div>
                <div class="form-group">
                    <label>Category <span class="req">*</span></label>
                    <select name="category" id="editCategory" required class="form-input">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Stock <span class="req">*</span></label>
                    <input type="number" name="stock" id="editStock" min="0" required class="form-input" />
                </div>
                <div class="form-group">
                    <label>Price (₱) <span class="req">*</span></label>
                    <input type="number" name="price" id="editPrice" min="0" step="0.01" required class="form-input" />
                </div>
                <div class="form-group">
                    <label>Old Price (₱) <span class="opt">(optional)</span></label>
                    <input type="number" name="old_price" id="editOldPrice" min="0" step="0.01" class="form-input" />
                </div>
                <div class="form-group full">
                    <label>Description <span class="opt">(optional)</span></label>
                    <textarea name="description" id="editDescription" rows="3" class="form-input" style="resize:vertical"></textarea>
                </div>
                <div class="form-group full" id="editImgPreviewWrap" style="display:none;">
                    <img id="editImgPreview" src="" alt="preview" style="max-height:90px;border-radius:8px;border:1px solid #e2e8f0;object-fit:cover;" />
                </div>
                <div class="form-group full">
                    <label>Image URL <span class="opt">(optional)</span></label>
                    <input type="url" name="image" id="editImage" class="form-input" placeholder="https://example.com/image.jpg" oninput="previewImage('edit', this.value)" />
                </div>
                <div class="form-group full toggle-row">
                    <input type="checkbox" name="is_featured" id="editFeatured" value="1" />
                    <label for="editFeatured">⭐ Show in Featured Products</label>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeEdit()">Cancel</button>
                <button type="submit" class="btn-save">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Product data for View modal (JSON) -->
<script id="productsJson" type="application/json">
{!! json_encode($products->map(fn($p) => [
    'id'          => $p->id,
    'name'        => $p->name,
    'category'    => $p->category,
    'price'       => $p->price,
    'old_price'   => $p->old_price,
    'stock'       => $p->stock,
    'is_featured' => $p->is_featured,
    'description' => $p->description,
    'image_url'   => $p->image_url,
])->values()) !!}
</script>

<style>
    * { box-sizing: border-box; }
    body { margin: 0; font-family: 'Inter', system-ui, sans-serif; background: #f1f5f9; }
    .admin-shell { display: flex; min-height: 100vh; }
    .admin-main { margin-left: 260px; padding: 28px 30px; flex: 1; display: flex; flex-direction: column; gap: 20px; }

    .page-header { display: flex; justify-content: space-between; align-items: center; }
    .page-header h1 { margin: 0 0 4px; font-size: 1.8rem; font-weight: 800; color: #0f172a; }
    .page-header p { margin: 0; color: #64748b; font-size: 0.9rem; }

    .alert-success { padding: 12px 16px; background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; border-radius: 10px; font-weight: 600; font-size: 0.9rem; }

    /* Filters */
    .filters-bar { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px 18px; }
    .filters-form { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; }
    .search-wrap { position: relative; flex: 1; min-width: 200px; }
    .search-icon { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none; }
    .search-input { width: 100%; padding: 9px 12px 9px 36px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.88rem; color: #0f172a; }
    .search-input:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
    .filter-select { padding: 9px 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.88rem; color: #334155; background: #f8fafc; }
    .filter-select:focus { outline: none; border-color: #6366f1; }
    .btn-filter { padding: 9px 18px; background: #6366f1; color: #fff; border: none; border-radius: 8px; font-weight: 600; font-size: 0.88rem; cursor: pointer; }
    .btn-filter:hover { background: #4f46e5; }
    .btn-clear { padding: 9px 14px; background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.88rem; text-decoration: none; font-weight: 600; }
    .btn-clear:hover { background: #e2e8f0; }

    /* Mini stats */
    .mini-stats { display: flex; gap: 12px; flex-wrap: wrap; }
    .mini-stat { flex: 1; min-width: 120px; background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 18px; display: flex; flex-direction: column; gap: 2px; }
    .mini-stat span { font-size: 0.78rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
    .mini-stat strong { font-size: 1.6rem; font-weight: 800; color: #0f172a; }
    .mini-stat.in-stock strong { color: #16a34a; }
    .mini-stat.low-stock strong { color: #b45309; }
    .mini-stat.out-stock strong { color: #e11d48; }

    /* Table */
    .content-card { background: #fff; border-radius: 14px; border: 1px solid #e2e8f0; overflow: hidden; }
    .table-wrap { overflow-x: auto; }
    .data-table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
    .data-table th { padding: 13px 16px; text-align: left; background: #f8fafc; color: #475569; font-weight: 700; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; white-space: nowrap; }
    .data-table td { padding: 13px 16px; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: middle; }
    .data-table tbody tr:hover { background: #f8fafc; }
    .td-num { color: #94a3b8; font-weight: 600; font-size: 0.82rem; }
    .td-empty { text-align: center; color: #94a3b8; padding: 48px 0 !important; font-size: 0.95rem; }

    .cell-product { display: flex; align-items: center; gap: 10px; }
    .cell-product img { width: 42px; height: 42px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0; flex-shrink: 0; }
    .product-name { display: block; font-weight: 700; color: #0f172a; }
    .product-desc { display: block; font-size: 0.78rem; color: #94a3b8; }

    .badge-cat { background: #eef2ff; color: #4338ca; font-size: 0.78rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; white-space: nowrap; }
    .td-price { font-weight: 700; color: #0f172a; white-space: nowrap; }
    .old-price { display: block; font-size: 0.78rem; font-weight: 400; color: #94a3b8; text-decoration: line-through; }

    .stock-val { font-weight: 700; padding: 3px 10px; border-radius: 20px; font-size: 0.82rem; }
    .stock-ok   { background: #f0fdf4; color: #16a34a; }
    .stock-low  { background: #fffbeb; color: #b45309; }
    .stock-zero { background: #fff1f2; color: #e11d48; }

    .status-badge { font-size: 0.78rem; font-weight: 700; padding: 4px 10px; border-radius: 20px; white-space: nowrap; }
    .status-in  { background: #f0fdf4; color: #16a34a; }
    .status-low { background: #fffbeb; color: #b45309; }
    .status-out { background: #fff1f2; color: #e11d48; }

    .badge-featured { font-size: 0.78rem; font-weight: 700; color: #854d0e; background: #fef9c3; padding: 3px 9px; border-radius: 20px; }
    .badge-normal { color: #cbd5e1; }

    .action-btns { display: flex; gap: 6px; flex-wrap: wrap; }
    .btn-view { display:inline-flex;align-items:center;gap:5px;padding:6px 12px;background:#e0f2fe;color:#0369a1;border:none;border-radius:7px;font-weight:600;font-size:0.8rem;cursor:pointer;transition:all 0.2s; }
    .btn-view:hover { background:#bae6fd; }
    .btn-edit { display:inline-flex;align-items:center;gap:5px;padding:6px 12px;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;border:none;border-radius:7px;font-weight:600;font-size:0.8rem;cursor:pointer;transition:all 0.2s; }
    .btn-edit:hover { transform:translateY(-1px);box-shadow:0 4px 12px rgba(99,102,241,0.4); }
    .btn-delete { display:inline-flex;align-items:center;gap:5px;padding:6px 12px;background:linear-gradient(135deg,#f43f5e,#e11d48);color:#fff;border:none;border-radius:7px;font-weight:600;font-size:0.8rem;cursor:pointer;transition:all 0.2s; }
    .btn-delete:hover { transform:translateY(-1px);box-shadow:0 4px 12px rgba(244,63,94,0.4); }

    .btn-add-product { display:inline-flex;align-items:center;gap:6px;padding:10px 20px;background:linear-gradient(135deg,#10b981,#059669);color:#fff;border:none;border-radius:9px;font-weight:700;font-size:0.88rem;cursor:pointer;transition:all 0.2s;white-space:nowrap; }
    .btn-add-product:hover { transform:translateY(-1px);box-shadow:0 4px 12px rgba(16,185,129,0.4); }

    .pagination-wrap { padding: 14px 18px; border-top: 1px solid #f1f5f9; }

    /* Modals */
    .modal-overlay { position:fixed;inset:0;background:rgba(0,0,0,0.45);display:flex;align-items:flex-start;justify-content:center;z-index:9999;backdrop-filter:blur(3px);overflow-y:auto;padding:32px 16px; }
    .modal-box { background:#fff;border-radius:16px;padding:24px;width:100%;max-width:540px;box-shadow:0 20px 60px rgba(0,0,0,0.2);margin:auto; }
    .view-modal-box { background:#fff;border-radius:16px;padding:0;width:100%;max-width:600px;box-shadow:0 20px 60px rgba(0,0,0,0.2);margin:auto;overflow:hidden; }

    .modal-header-bar { display:flex;justify-content:space-between;align-items:center;padding:18px 22px;border-bottom:1px solid #f1f5f9; }
    .modal-header-bar h3 { margin:0;font-size:1.05rem;font-weight:800;color:#0f172a; }
    .modal-close-x { background:none;border:none;font-size:1.1rem;cursor:pointer;color:#94a3b8;width:30px;height:30px;border-radius:6px;display:flex;align-items:center;justify-content:center; }
    .modal-close-x:hover { background:#f1f5f9;color:#475569; }

    .form-grid { display:grid;grid-template-columns:1fr 1fr;gap:14px;padding:20px 0 4px; }
    .form-group { display:flex;flex-direction:column;gap:5px; }
    .form-group.full { grid-column:1/-1; }
    .form-group label { font-size:0.82rem;font-weight:600;color:#475569; }
    .req { color:#e11d48; }
    .opt { font-weight:400;color:#94a3b8;font-size:0.75rem; }
    .form-input { width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:0.88rem;color:#0f172a;background:#f8fafc;transition:all 0.2s;font-family:inherit; }
    .form-input:focus { outline:none;border-color:#6366f1;background:#fff;box-shadow:0 0 0 3px rgba(99,102,241,0.12); }
    .toggle-row { flex-direction:row;align-items:center;gap:8px; }
    .toggle-row input { width:16px;height:16px;cursor:pointer; }
    .toggle-row label { font-size:0.88rem;font-weight:600;color:#475569;cursor:pointer;margin:0; }

    .modal-actions { display:flex;gap:10px;justify-content:flex-end;margin-top:20px;padding-top:16px;border-top:1px solid #f1f5f9; }
    .btn-cancel { padding:9px 18px;background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;border-radius:8px;font-weight:600;font-size:0.88rem;cursor:pointer; }
    .btn-cancel:hover { background:#e2e8f0; }
    .btn-save { padding:9px 20px;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;border:none;border-radius:8px;font-weight:700;font-size:0.88rem;cursor:pointer;transition:all 0.2s; }
    .btn-save:hover { box-shadow:0 4px 12px rgba(99,102,241,0.4); }

    /* View Modal Body */
    .view-body { display:flex;gap:0;flex-direction:column; }
    .view-img-wrap { background:#f8fafc;padding:24px;display:flex;justify-content:center;border-bottom:1px solid #f1f5f9; }
    .view-img-wrap img { max-height:180px;max-width:100%;object-fit:contain;border-radius:10px; }
    .view-details { padding:22px; }
    .view-details h2 { margin:0 0 6px;font-size:1.2rem;font-weight:800;color:#0f172a; }
    .view-desc { margin:0 0 16px;font-size:0.88rem;color:#64748b;line-height:1.5; }
    .view-grid { display:grid;grid-template-columns:1fr 1fr;gap:12px; }
    .view-item { display:flex;flex-direction:column;gap:2px;background:#f8fafc;border:1px solid #f1f5f9;border-radius:8px;padding:10px 14px; }
    .view-item span { font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.5px; }
    .view-item strong { font-size:0.9rem;font-weight:700;color:#0f172a; }

    @media (max-width: 768px) { .admin-main { margin-left:0;padding:16px; } .form-grid { grid-template-columns:1fr; } .action-btns { flex-direction:column; } }
</style>

<script>
    const productsData = JSON.parse(document.getElementById('productsJson').textContent);

    function openView(id) {
        const p = productsData.find(x => x.id === id);
        if (!p) return;
        document.getElementById('viewImg').src = p.image_url || '{{ asset("images/default-avatar.svg") }}';
        document.getElementById('viewImg').alt = p.name;
        document.getElementById('viewName').textContent = p.name;
        document.getElementById('viewDesc').textContent = p.description || 'No description provided.';
        document.getElementById('viewCategory').textContent = p.category;
        document.getElementById('viewPrice').textContent = '₱' + parseFloat(p.price).toFixed(2);
        document.getElementById('viewOldPrice').textContent = p.old_price ? '₱' + parseFloat(p.old_price).toFixed(2) : '—';
        document.getElementById('viewStock').textContent = p.stock;
        document.getElementById('viewStatus').textContent = p.stock == 0 ? 'Out of Stock' : (p.stock < 10 ? 'Low Stock' : 'In Stock');
        document.getElementById('viewFeatured').textContent = p.is_featured ? '⭐ Yes' : 'No';
        document.getElementById('viewModal').style.display = 'flex';
    }

    function openEdit(id, name, category, price, oldPrice, stock, image, isFeatured, description) {
        document.getElementById('editForm').action = `/admin/products/${id}`;
        document.getElementById('editName').value = name;
        document.getElementById('editCategory').value = category;
        document.getElementById('editPrice').value = price;
        document.getElementById('editOldPrice').value = oldPrice ?? '';
        document.getElementById('editStock').value = stock;
        document.getElementById('editImage').value = image;
        document.getElementById('editFeatured').checked = isFeatured;
        document.getElementById('editDescription').value = description;
        previewImage('edit', image);
        document.getElementById('editModal').style.display = 'flex';
    }

    function closeEdit() { document.getElementById('editModal').style.display = 'none'; }

    function previewImage(prefix, url) {
        const wrap = document.getElementById(prefix + 'ImgPreviewWrap');
        const img  = document.getElementById(prefix + 'ImgPreview');
        if (url && url.trim()) {
            img.src = url;
            wrap.style.display = 'block';
        } else {
            wrap.style.display = 'none';
        }
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeEdit();
            document.getElementById('addModal').style.display = 'none';
            document.getElementById('viewModal').style.display = 'none';
        }
    });
</script>
@endsection
