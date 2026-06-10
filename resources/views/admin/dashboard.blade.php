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

            <div class="stat-card" style="--accent:#f59e0b;">
                <div class="stat-icon" style="background:linear-gradient(135deg,#f59e0b,#f97316);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="26" height="26">
                        <path d="M9 17H5a2 2 0 0 0-2 2v0a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v0a2 2 0 0 0-2-2h-4"/>
                        <path d="M12 3v14"/><path d="M8 7l4-4 4 4"/>
                    </svg>
                </div>
                <div class="stat-body">
                    <p>Total Orders</p>
                    <strong>{{ $totalOrders }}</strong>
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
                    <p>Products organized by category — {{ $totalProducts }} total products</p>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-top:12px;">
                    <div class="category-tabs">
                        @foreach($products->keys() as $catKey)
                            <button class="cat-tab {{ $loop->first ? 'active' : '' }}" data-cat="{{ $catKey }}">{{ $catKey }}</button>
                        @endforeach
                    </div>
                    <button class="btn-add-product" onclick="document.getElementById('addModal').style.display='flex'">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Add Product
                    </button>
                </div>
            </div>

            @forelse ($products as $categoryName => $categoryProducts)
            <div class="category-section" data-category="{{ $categoryName }}" style="{{ $loop->first ? '' : 'display:none;' }}">
                <div class="category-section-header">
                    <span class="category-section-title">{{ $categoryName }}</span>
                    <span class="category-section-count">{{ $categoryProducts->count() }} {{ Str::plural('product', $categoryProducts->count()) }}</span>
                </div>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Image</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categoryProducts as $i => $product)
                                <tr id="row-{{ $product->id }}">
                                    <td style="color:#94a3b8;font-weight:600;font-size:0.82rem;">{{ $i + 1 }}</td>
                                    <td class="cell-product">
                                        <img src="{{ $product->image_url ?? asset('images/default-avatar.svg') }}"
                                             alt="{{ $product->name }}" />
                                        <span>{{ $product->name }}</span>
                                    </td>
                                    <td style="font-weight:600;color:#0f172a;">₱{{ number_format($product->price, 2) }}</td>
                                    <td>
                                        <div class="stock-edit-wrap" data-id="{{ $product->id }}">
                                            <input
                                                type="number"
                                                class="stock-input"
                                                value="{{ $product->stock }}"
                                                min="0"
                                                data-original="{{ $product->stock }}"
                                            />
                                            <button class="btn-stock-save" title="Save stock">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="12" height="12"><polyline points="20 6 9 17 4 12"/></svg>
                                            </button>
                                            <span class="stock-badge {{ $product->stock == 0 ? 'stock-zero' : ($product->stock < 10 ? 'stock-low' : 'stock-ok') }}">
                                                {{ $product->stock }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="img-path">{{ $product->image ? basename($product->image) : '—' }}</span>
                                    </td>
                                    <td>
                                        <button class="btn-edit" onclick="openEdit(
                                            {{ $product->id }},
                                            '{{ addslashes($product->name) }}',
                                            {{ $product->price }},
                                            {{ $product->old_price ?? 'null' }},
                                            {{ $product->stock }},
                                            '{{ addslashes($product->image ?? '') }}',
                                            {{ $product->is_featured ? 'true' : 'false' }},
                                            '{{ addslashes($product->description ?? '') }}'
                                        )">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                            </svg>
                                            Edit
                                        </button>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                              onsubmit="return confirm('Delete \'{{ addslashes($product->name) }}\'? This cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                                                    <polyline points="3 6 5 6 21 6"/>
                                                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                                    <path d="M10 11v6M14 11v6"/>
                                                    <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @empty
                <div style="padding:40px;text-align:center;color:#94a3b8;">No products found.</div>
            @endforelse
        </div>
    </main>
</div>

<!-- Add Product Modal -->
<div id="addModal" class="modal-overlay" style="display:none;" onclick="if(event.target===this)this.style.display='none'">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Add New Product</h3>
            <button class="modal-close" onclick="document.getElementById('addModal').style.display='none'">✕</button>
        </div>
        <form method="POST" action="{{ route('admin.products.store') }}">
            @csrf
            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="name" required class="form-input" />
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category" required class="form-input">
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Price (₱)</label>
                <input type="number" name="price" min="0" step="0.01" required class="form-input" />
            </div>
            <div class="form-group">
                <label>Old Price (₱) <span style="font-weight:400;color:#94a3b8">(optional)</span></label>
                <input type="number" name="old_price" min="0" step="0.01" class="form-input" />
            </div>
            <div class="form-group">
                <label>Stock</label>
                <input type="number" name="stock" min="0" required class="form-input" />
            </div>
            <div class="form-group">
                <label>Description <span style="font-weight:400;color:#94a3b8">(optional)</span></label>
                <textarea name="description" rows="3" class="form-input" style="resize:vertical"></textarea>
            </div>
            <div class="form-group">
                <label>Image URL <span style="font-weight:400;color:#94a3b8">(optional)</span></label>
                <input type="url" name="image" class="form-input" placeholder="https://example.com/image.jpg" />
            </div>
            <div class="form-group" style="display:flex;align-items:center;gap:10px">
                <input type="checkbox" name="is_featured" value="1" id="addFeatured" style="width:18px;height:18px;cursor:pointer" />
                <label for="addFeatured" style="margin:0;cursor:pointer">Show in Featured Products</label>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="document.getElementById('addModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn-save">Add Product</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal-overlay" style="display:none;" onclick="if(event.target===this)closeEdit()">
    <div class="edit-modal-box">

        <div class="edit-modal-header">
            <div class="edit-modal-header-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </div>
            <div>
                <h3 class="edit-modal-title">Edit Product</h3>
                <p class="edit-modal-subtitle">Update product information below</p>
            </div>
            <button class="edit-modal-close" onclick="closeEdit()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <div class="edit-modal-body">

                <div class="edit-section">
                    <div class="edit-section-label">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M21 16V8a2 2 0 0 0-1-1.73L13 2.27a2 2 0 0 0-2 0L4 6.27A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                        Product Details
                    </div>
                    <div class="edit-field">
                        <label class="edit-label" for="editName">Product Name <span class="req">*</span></label>
                        <div class="edit-input-wrap">
                            <svg class="edit-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M20 7H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                            <input type="text" name="name" id="editName" required class="edit-input" placeholder="e.g. Biogesic 500mg" />
                        </div>
                    </div>
                    <div class="edit-field">
                        <label class="edit-label" for="editDescription">Description <span class="opt">(optional)</span></label>
                        <textarea name="description" id="editDescription" rows="3" class="edit-input edit-textarea" placeholder="Brief product description…"></textarea>
                    </div>
                </div>

                <div class="edit-section">
                    <div class="edit-section-label">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        Pricing
                    </div>
                    <div class="edit-row-2">
                        <div class="edit-field">
                            <label class="edit-label" for="editPrice">Price (₱) <span class="req">*</span></label>
                            <div class="edit-input-wrap">
                                <span class="edit-input-prefix">₱</span>
                                <input type="number" name="price" id="editPrice" min="0" step="0.01" required class="edit-input edit-input-prefixed" placeholder="0.00" />
                            </div>
                        </div>
                        <div class="edit-field">
                            <label class="edit-label" for="editOldPrice">Old Price (₱) <span class="opt">(optional)</span></label>
                            <div class="edit-input-wrap">
                                <span class="edit-input-prefix">₱</span>
                                <input type="number" name="old_price" id="editOldPrice" min="0" step="0.01" class="edit-input edit-input-prefixed" placeholder="0.00" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="edit-section">
                    <div class="edit-section-label">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                        Stock
                    </div>
                    <div class="edit-field" style="max-width:160px">
                        <label class="edit-label" for="editStock">Quantity <span class="req">*</span></label>
                        <div class="edit-input-wrap">
                            <svg class="edit-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            <input type="number" name="stock" id="editStock" min="0" required class="edit-input" placeholder="0" />
                        </div>
                    </div>
                </div>

                <div class="edit-section">
                    <div class="edit-section-label">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        Product Image
                    </div>
                    <div id="editImgPreviewWrap" style="display:none;">
                        <img id="editImgPreview" src="" alt="preview"
                             style="max-height:100px;border-radius:10px;border:1px solid #e2e8f0;object-fit:cover;display:block;margin-bottom:10px;" />
                    </div>
                    <div class="edit-field">
                        <label class="edit-label" for="editImage">Image URL <span class="opt">(optional)</span></label>
                        <div class="edit-input-wrap">
                            <svg class="edit-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                            <input type="url" name="image" id="editImage" class="edit-input"
                                   placeholder="https://example.com/image.jpg"
                                   oninput="previewEditImage(this.value)" />
                        </div>
                    </div>
                </div>



            </div>

            <div class="edit-modal-footer">
                <button type="button" class="edit-btn-cancel" onclick="closeEdit()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Cancel
                </button>
                <button type="submit" class="edit-btn-save">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14"><polyline points="20 6 9 17 4 12"/></svg>
                    Save Changes
                </button>
            </div>

        </form>
    </div>
</div>

<style>
    * { box-sizing: border-box; }
    body { margin: 0; font-family: 'Inter', system-ui, sans-serif; background: #f1f5f9; }

    .admin-shell { display: flex; min-height: 100vh; }

    .admin-main {
        margin-left: 260px;
        padding: 28px 30px;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 22px;
    }

    .page-header { display: flex; justify-content: space-between; align-items: center; }
    .page-header h1 { margin: 0 0 4px; font-size: 1.8rem; font-weight: 800; color: #0f172a; }
    .page-header p { margin: 0; color: #64748b; font-size: 0.9rem; }

    .header-badge {
        display: flex; align-items: center; gap: 6px;
        background: #fff; border: 1px solid #e2e8f0;
        padding: 6px 14px; border-radius: 20px;
    }

    .live-dot {
        width: 8px; height: 8px; background: #10b981;
        border-radius: 50%; animation: pulse 1.5s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.4); }
    }

    .alert-success {
        padding: 12px 16px; background: #f0fdf4; color: #166534;
        border: 1px solid #bbf7d0; border-radius: 10px;
        font-weight: 600; font-size: 0.9rem;
    }

    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }

    .stat-card {
        display: flex; align-items: center; gap: 14px;
        padding: 20px; background: #fff; border-radius: 14px;
        border: 1px solid #e2e8f0; box-shadow: 0 1px 6px rgba(0,0,0,0.04);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }

    .stat-icon {
        width: 52px; height: 52px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: #fff; flex-shrink: 0;
    }
    .stat-body p { margin: 0 0 3px; color: #64748b; font-size: 0.82rem; font-weight: 500; }
    .stat-body strong { display: block; font-size: 1.7rem; font-weight: 800; color: #0f172a; line-height: 1; }

    .content-card {
        background: #fff; border-radius: 14px;
        border: 1px solid #e2e8f0; box-shadow: 0 1px 6px rgba(0,0,0,0.04); overflow: hidden;
    }

    .card-header {
        padding: 20px 24px; border-bottom: 1px solid #f1f5f9;
        display: flex; flex-direction: column; gap: 4px;
    }
    .card-header h2 { margin: 0 0 3px; font-size: 1.1rem; color: #0f172a; font-weight: 700; }
    .card-header p { margin: 0; color: #64748b; font-size: 0.85rem; }

    .table-wrap { overflow-x: auto; }

    .data-table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
    .data-table th {
        padding: 13px 18px; text-align: left; background: #f8fafc;
        color: #475569; font-weight: 700; font-size: 0.78rem;
        text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0;
    }
    .data-table td {
        padding: 14px 18px; border-bottom: 1px solid #f1f5f9;
        color: #334155; vertical-align: middle;
    }
    .data-table tbody tr:hover { background: #f8fafc; }

    .cell-product { display: flex; align-items: center; gap: 10px; font-weight: 600; color: #0f172a; }
    .cell-product img { width: 38px; height: 38px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0; }

    .stock-val { font-weight: 700; padding: 3px 10px; border-radius: 20px; font-size: 0.82rem; }
    .stock-ok   { background: #f0fdf4; color: #16a34a; }
    .stock-low  { background: #fffbeb; color: #b45309; }
    .stock-zero { background: #fff1f2; color: #e11d48; }

    /* Inline stock editor */
    .stock-edit-wrap { display: flex; align-items: center; gap: 5px; }
    .stock-input {
        width: 64px; padding: 4px 8px; border: 1.5px solid #e2e8f0;
        border-radius: 8px; font-size: 0.82rem; font-weight: 700;
        color: #0f172a; background: #f8fafc; text-align: center;
        transition: border-color 0.15s, box-shadow 0.15s;
        -moz-appearance: textfield;
    }
    .stock-input::-webkit-outer-spin-button,
    .stock-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    .stock-input:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.12); background: #fff; }
    .stock-input.changed { border-color: #f59e0b; background: #fffbeb; }
    .btn-stock-save {
        width: 26px; height: 26px; border-radius: 7px; border: none;
        background: #6366f1; color: #fff; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        opacity: 0; pointer-events: none; transition: opacity 0.15s, transform 0.15s;
        flex-shrink: 0;
    }
    .btn-stock-save.visible { opacity: 1; pointer-events: auto; }
    .btn-stock-save:hover { background: #4f46e5; transform: scale(1.08); }
    .btn-stock-save.saving { background: #10b981; }
    .stock-badge { font-weight: 700; padding: 3px 10px; border-radius: 20px; font-size: 0.82rem; white-space: nowrap; }

    .img-path {
        font-size: 0.8rem; color: #94a3b8;
        max-width: 160px; overflow: hidden;
        text-overflow: ellipsis; white-space: nowrap; display: block;
    }

    .btn-edit {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 7px 14px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: #fff; border: none; border-radius: 8px;
        font-weight: 600; font-size: 0.82rem; cursor: pointer; transition: all 0.2s;
    }
    .btn-edit:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(99,102,241,0.4); }

    .btn-delete {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 7px 14px;
        background: linear-gradient(135deg, #f43f5e, #e11d48);
        color: #fff; border: none; border-radius: 8px;
        font-weight: 600; font-size: 0.82rem; cursor: pointer; transition: all 0.2s;
    }
    .btn-delete:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(244,63,94,0.4); }

    .category-tabs { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px; }

    .cat-tab {
        padding: 7px 16px; border: 1.5px solid #e2e8f0; border-radius: 20px;
        background: #f8fafc; color: #475569; font-size: 0.82rem;
        font-weight: 600; cursor: pointer; transition: all 0.18s;
    }
    .cat-tab:hover { border-color: #6366f1; color: #6366f1; background: #eef2ff; }
    .cat-tab.active {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: #fff; border-color: transparent;
        box-shadow: 0 2px 8px rgba(99,102,241,0.35);
    }

    .category-section { border-top: 1px solid #f1f5f9; }
    .category-section-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 24px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;
    }
    .category-section-title { font-size: 0.92rem; font-weight: 800; color: #1e293b; }
    .category-section-count {
        font-size: 0.78rem; font-weight: 600; color: #64748b;
        background: #e2e8f0; padding: 3px 10px; border-radius: 20px;
    }

    /* Edit Modal */
    .edit-modal-box {
        background: #fff; border-radius: 20px; width: 100%; max-width: 560px;
        display: flex; flex-direction: column;
        box-shadow: 0 32px 80px rgba(0,0,0,0.22);
        animation: editSlideUp 0.22s ease; margin: auto;
        max-height: 90vh; overflow-y: auto;
    }
    @keyframes editSlideUp {
        from { opacity:0; transform:translateY(18px); }
        to   { opacity:1; transform:translateY(0); }
    }

    .edit-modal-header {
        display: flex; align-items: center; gap: 14px;
        padding: 22px 24px 18px; border-bottom: 1px solid #f1f5f9; flex-shrink: 0;
        position: sticky; top: 0; background: #fff; z-index: 1;
    }
    .edit-modal-header-icon {
        width: 42px; height: 42px; border-radius: 12px;
        background: linear-gradient(135deg,#6366f1,#8b5cf6);
        display: flex; align-items: center; justify-content: center;
        color: #fff; flex-shrink: 0;
    }
    .edit-modal-title { margin: 0 0 2px; font-size: 1.05rem; font-weight: 800; color: #0f172a; }
    .edit-modal-subtitle { margin: 0; font-size: 0.78rem; color: #94a3b8; }
    .edit-modal-close {
        margin-left: auto; width: 32px; height: 32px; border-radius: 8px;
        border: 1px solid #e2e8f0; background: #f8fafc; color: #64748b;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all 0.15s; flex-shrink: 0;
    }
    .edit-modal-close:hover { background: #fee2e2; border-color: #fca5a5; color: #dc2626; }

    .edit-modal-body { padding: 20px 24px; display: flex; flex-direction: column; gap: 6px; }

    .edit-section {
        background: #f8fafc; border: 1px solid #f1f5f9; border-radius: 12px;
        padding: 16px; display: flex; flex-direction: column; gap: 12px;
    }
    .edit-section-label {
        display: flex; align-items: center; gap: 6px;
        font-size: 0.72rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: 0.06em; color: #6366f1;
    }
    .edit-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .edit-field { display: flex; flex-direction: column; gap: 5px; }
    .edit-label { font-size: 0.8rem; font-weight: 600; color: #475569; }
    .req { color: #e11d48; }
    .opt { font-weight: 400; color: #94a3b8; font-size: 0.75rem; }

    .edit-input-wrap { position: relative; display: flex; align-items: center; }
    .edit-input-icon { position: absolute; left: 11px; color: #94a3b8; pointer-events: none; flex-shrink: 0; }
    .edit-input-prefix {
        position: absolute; left: 12px; font-size: 0.88rem;
        font-weight: 700; color: #64748b; pointer-events: none;
    }
    .edit-input {
        width: 100%; padding: 10px 12px 10px 36px;
        border: 1.5px solid #e2e8f0; border-radius: 9px;
        font-size: 0.88rem; color: #0f172a; background: #fff;
        transition: border-color 0.18s, box-shadow 0.18s; font-family: inherit;
    }
    .edit-input-prefixed { padding-left: 26px; }
    .edit-input:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.12); }
    .edit-textarea { padding-left: 12px; resize: vertical; min-height: 76px; }



    .edit-modal-footer {
        display: flex; gap: 10px; justify-content: flex-end;
        padding: 16px 24px; border-top: 1px solid #f1f5f9;
        background: #f8fafc; flex-shrink: 0;
        position: sticky; bottom: 0;
    }
    .edit-btn-cancel {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 10px 20px; background: #fff; color: #475569;
        border: 1.5px solid #e2e8f0; border-radius: 9px;
        font-weight: 600; font-size: 0.86rem; cursor: pointer; transition: all 0.15s; font-family: inherit;
    }
    .edit-btn-cancel:hover { background: #f1f5f9; border-color: #cbd5e1; }
    .edit-btn-save {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 10px 22px;
        background: linear-gradient(135deg,#6366f1,#8b5cf6);
        color: #fff; border: none; border-radius: 9px;
        font-weight: 700; font-size: 0.86rem; cursor: pointer; transition: all 0.2s; font-family: inherit;
    }
    .edit-btn-save:hover { box-shadow: 0 4px 14px rgba(99,102,241,0.45); transform: translateY(-1px); }

    /* Shared Modal Overlay */
    .modal-overlay {
        position: fixed; inset: 0; background: rgba(0,0,0,0.45);
        display: flex; align-items: flex-start; justify-content: center;
        z-index: 9999; backdrop-filter: blur(3px); overflow-y: auto; padding: 32px 16px;
    }
    .modal-box {
        background: #fff; border-radius: 16px; padding: 24px;
        width: 100%; max-width: 420px; box-shadow: 0 20px 60px rgba(0,0,0,0.2); margin: auto;
    }
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .modal-header h3 { margin: 0; font-size: 1.1rem; color: #0f172a; }
    .modal-close { background: none; border: none; font-size: 1.1rem; cursor: pointer; color: #94a3b8; padding: 2px 6px; border-radius: 6px; }
    .modal-close:hover { background: #f1f5f9; color: #475569; }
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 6px; }
    .form-input {
        width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px;
        font-size: 0.9rem; color: #0f172a; background: #f8fafc; transition: all 0.2s;
    }
    .form-input:focus { outline: none; border-color: #6366f1; background: #fff; box-shadow: 0 0 0 3px rgba(99,102,241,0.12); }
    .modal-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; }
    .btn-cancel { padding: 9px 18px; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 8px; font-weight: 600; font-size: 0.88rem; cursor: pointer; }
    .btn-cancel:hover { background: #e2e8f0; }
    .btn-save { padding: 9px 18px; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff; border: none; border-radius: 8px; font-weight: 700; font-size: 0.88rem; cursor: pointer; transition: all 0.2s; }
    .btn-save:hover { box-shadow: 0 4px 12px rgba(99,102,241,0.4); }
    .btn-add-product {
        display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff; border: none; border-radius: 8px;
        font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: all 0.2s; white-space: nowrap;
    }
    .btn-add-product:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(16,185,129,0.4); }

    @media (max-width: 1200px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 768px) { .admin-main { margin-left: 0; padding: 16px; } .stats-grid { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 600px) { .edit-modal-box { border-radius: 16px; } .edit-row-2 { grid-template-columns: 1fr; } }
</style>

<script>
    // ── Category tabs ───────────────────────────────────────────────────────
    document.querySelectorAll('.cat-tab').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.cat-tab').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const cat = btn.dataset.cat;
            document.querySelectorAll('.category-section').forEach(sec => {
                sec.style.display = sec.dataset.category === cat ? '' : 'none';
            });
        });
    });

    // ── Inline stock editor ─────────────────────────────────────────────────
    function stockClass(n) {
        return n == 0 ? 'stock-zero' : (n < 10 ? 'stock-low' : 'stock-ok');
    }

    document.querySelectorAll('.stock-edit-wrap').forEach(wrap => {
        const input  = wrap.querySelector('.stock-input');
        const saveBtn = wrap.querySelector('.btn-stock-save');
        const badge  = wrap.querySelector('.stock-badge');
        const productId = wrap.dataset.id;

        input.addEventListener('input', () => {
            const changed = input.value !== input.dataset.original;
            input.classList.toggle('changed', changed);
            saveBtn.classList.toggle('visible', changed);
        });

        saveBtn.addEventListener('click', async () => {
            const val = parseInt(input.value, 10);
            if (isNaN(val) || val < 0) {
                input.focus();
                return;
            }

            saveBtn.classList.add('saving');
            saveBtn.disabled = true;

            try {
                const res = await fetch(`/admin/products/${productId}/stock`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ stock: val }),
                });

                if (!res.ok) throw new Error('Server error');

                const data = await res.json();
                const newStock = data.stock;

                // Update badge
                badge.textContent = newStock;
                badge.className = 'stock-badge ' + stockClass(newStock);

                // Sync input state
                input.value = newStock;
                input.dataset.original = newStock;
                input.classList.remove('changed');
                saveBtn.classList.remove('visible', 'saving');
                saveBtn.disabled = false;

                // Brief green flash on the row
                const row = wrap.closest('tr');
                row.style.transition = 'background 0.3s';
                row.style.background = '#f0fdf4';
                setTimeout(() => row.style.background = '', 900);

            } catch (e) {
                saveBtn.classList.remove('saving');
                saveBtn.disabled = false;
                alert('Failed to update stock. Please try again.');
            }
        });

        // Also save on Enter key
        input.addEventListener('keydown', e => {
            if (e.key === 'Enter') { e.preventDefault(); saveBtn.click(); }
        });
    });

    // ── Edit product modal ──────────────────────────────────────────────────
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeEdit();
            document.getElementById('addModal').style.display = 'none';
        }
    });

    function openEdit(id, name, price, oldPrice, stock, image, isFeatured, description) {
        document.getElementById('editForm').action = `/admin/products/${id}`;
        document.getElementById('editName').value = name;
        document.getElementById('editPrice').value = price;
        document.getElementById('editOldPrice').value = oldPrice ?? '';
        document.getElementById('editStock').value = stock;
        document.getElementById('editImage').value = image;
        document.getElementById('editDescription').value = description;
        previewEditImage(image);
        document.getElementById('editModal').style.display = 'flex';
    }

    function previewEditImage(src) {
        const preview = document.getElementById('editImgPreview');
        const wrap    = document.getElementById('editImgPreviewWrap');
        if (src && src.trim()) {
            preview.src = src;
            wrap.style.display = 'block';
        } else {
            wrap.style.display = 'none';
            preview.src = '';
        }
    }

    function closeEdit() {
        document.getElementById('editModal').style.display = 'none';
    }
</script>
@endsection
