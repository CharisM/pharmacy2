@extends('layouts.app')

@section('content')
    <section class="admin-shell">
        <aside class="admin-sidebar">
            <div class="admin-profile">
                <img src="{{ auth()->user()->profile_picture_url }}" alt="{{ auth()->user()->name }}" />
                <div>
                    <h2>{{ auth()->user()->name }}</h2>
                    <p>Administrator</p>
                </div>
            </div>
        </aside>

        <main class="admin-main">
            <div class="page-heading">
                <div>
                    <h1>Stock Manager</h1>
                    <p>Update inventory and image path for each product from one place.</p>
                </div>
            </div>

            <div class="status-cards">
                <div class="admin-card">
                    <h3>Products</h3>
                    <strong>{{ $totalProducts }}</strong>
                    <span>Total SKUs</span>
                </div>

                <div class="admin-card">
                    <h3>Total Stock</h3>
                    <strong>{{ $totalStock }}</strong>
                    <span>Inventory quantity</span>
                </div>
            </div>

            <div class="stock-table-card">
                <h2>Stock Items</h2>
                <p>Edit each product's stock count and image path on a per-item basis.</p>

                <div class="stock-table-wrapper">
                    <table class="stock-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Stock</th>
                                <th>Image Path</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td class="product-cell">
                                        <img src="{{ $product->image ? (preg_match('/^https?:\/\//', $product->image) ? $product->image : asset($product->image)) : asset('images/default-avatar.svg') }}" alt="{{ $product->name }}" />
                                        <span>{{ $product->name }}</span>
                                    </td>
                                    <td>{{ $product->category }}</td>
                                    <td>
                                        <form action="{{ route('admin.products.update', $product) }}" method="POST" class="product-update-form">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="stock" min="0" value="{{ $product->stock }}" class="stock-input" />
                                    </td>
                                    <td>
                                            <input type="text" name="image" value="{{ $product->image }}" placeholder="image path or url" class="image-input" />
                                    </td>
                                    <td>
                                            <button type="submit" class="btn-save">Save</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">No products found in inventory.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-links">
                    {{ $products->links() }}
                </div>
            </div>
        </main>
    </section>

    <style>
        .admin-shell {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 24px;
            max-width: 1400px;
            margin: 0 auto;
            padding: 32px 20px;
            min-height: calc(100vh - 40px);
        }

        .admin-sidebar {
            display: flex;
            flex-direction: column;
            gap: 24px;
            background: #ffffff;
            border-radius: 24px;
            border: 1px solid #e5e7eb;
            padding: 24px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
            position: sticky;
            top: 24px;
            height: fit-content;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .admin-profile img {
            width: 72px;
            height: 72px;
            object-fit: cover;
            border-radius: 18px;
            border: 1px solid #e5e7eb;
        }

        .admin-profile h2 {
            margin: 0 0 4px;
            font-size: 1.2rem;
        }

        .admin-profile p {
            margin: 0;
            color: #6b7280;
            font-size: 0.95rem;
        }


        .admin-main {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .page-heading h1 {
            margin: 0 0 8px;
            font-size: 2rem;
        }

        .page-heading p {
            margin: 0;
            color: #475569;
        }

        .status-cards {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .admin-card {
            padding: 22px;
            border-radius: 20px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        }

        .admin-card h3 {
            margin: 0 0 8px;
            color: #111827;
            font-size: 1rem;
        }

        .admin-card strong {
            display: block;
            font-size: 2rem;
            margin-bottom: 6px;
        }

        .admin-card span {
            color: #6b7280;
            font-size: 0.95rem;
        }

        .stock-table-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 24px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        }

        .stock-table-card h2 {
            margin: 0 0 6px;
            font-size: 1.25rem;
        }

        .stock-table-card p {
            margin: 0 0 20px;
            color: #6b7280;
        }

        .stock-table-wrapper {
            overflow-x: auto;
        }

        .stock-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 860px;
        }

        .stock-table th,
        .stock-table td {
            padding: 16px 18px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }

        .stock-table th {
            text-align: left;
            color: #374151;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .product-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .product-cell img {
            width: 56px;
            height: 56px;
            object-fit: cover;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
        }

        .stock-input,
        .image-input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 14px;
            font-size: 0.95rem;
            color: #111827;
            background: #f8fafc;
        }

        .image-input {
            min-width: 240px;
        }

        .btn-save {
            padding: 10px 18px;
            border-radius: 14px;
            border: none;
            background: #16a34a;
            color: #ffffff;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-save:hover {
            background: #15803d;
        }

        .pagination-links {
            margin-top: 18px;
            display: flex;
            justify-content: flex-end;
        }

        @media (max-width: 1024px) {
            .admin-shell {
                grid-template-columns: 1fr;
            }

            .admin-sidebar {
                position: static;
                top: auto;
            }
        }

        @media (max-width: 720px) {
            .stock-table {
                min-width: 100%;
            }

            .admin-nav a {
                font-size: 0.95rem;
            }
        }
    </style>
@endsection
