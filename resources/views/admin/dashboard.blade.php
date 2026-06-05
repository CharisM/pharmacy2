@extends('layouts.app')

@section('content')
    <section class="admin-dashboard">
        <div class="page-heading">
            <h1>Admin Dashboard</h1>
            <p>Welcome back, {{ $adminName }}. Manage your store and review site metrics below.</p>
        </div>

        <div class="admin-grid">
            <div class="admin-card">
                <h2>Users</h2>
                <p>{{ $totalUsers }}</p>
                <span>Registered customers</span>
            </div>

            <div class="admin-card">
                <h2>Products</h2>
                <p>{{ $totalProducts }}</p>
                <span>Items available in the catalog</span>
            </div>
        </div>
    </section>
@endsection
