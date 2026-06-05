@extends('layouts.app')

@section('content')
    <section class="admin-dashboard">
        <div class="page-heading">
            <div>
                <h1>User Management</h1>
                <p>Review all accounts and assign admin access to approved users.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn-secondary">Back to Dashboard</a>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        <div class="stock-table-card">
            <table class="stock-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->is_admin ? 'Admin' : 'User' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination-links">
                {{ $users->links() }}
            </div>
        </div>
    </section>

    <style>
        .alert-success,
        .alert-error {
            padding: 14px 18px;
            border-radius: 16px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .alert-success {
            background: #ecfdf5;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .stock-table thead {
            background: #f9fafb;
        }

        .pagination-links {
            padding-top: 16px;
        }

        .text-muted {
            color: #6b7280;
            font-weight: 600;
        }
    </style>
@endsection
