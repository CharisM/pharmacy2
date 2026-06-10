@extends('layouts.app')

@section('content')
<div class="admin-shell">
    @include('partials.admin-sidebar')

    <main class="admin-main">
        <div class="page-header">
            <div>
                <h1>User Management</h1>
                <p>All registered users and their verification status</p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;">
                <a href="{{ route('admin.dashboard') }}" class="btn-back">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                    Back to Dashboard
                </a>
                <button class="btn-reset" onclick="document.getElementById('resetModal').style.display='flex'">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15">
                        <polyline points="1 4 1 10 7 10"/>
                        <path d="M3.51 15a9 9 0 1 0 .49-4.95"/>
                    </svg>
                    Reset Active Users
                </button>
                <button class="btn-clear-all" onclick="document.getElementById('clearAllModal').style.display='flex'">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                        <path d="M10 11v6M14 11v6"/>
                        <path d="M9 6V4h6v2"/>
                    </svg>
                    Clear All Users
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success">✅ {{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert-error">⚠️ {{ session('error') }}</div>
        @endif

        <div class="content-card">
            <div class="card-header">
                <div>
                    <h2>All Users</h2>
                    <p>{{ $users->total() }} registered user(s)</p>
                </div>
                <button class="btn-force-logout" onclick="document.getElementById('forceLogoutModal').style.display='flex'">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    Force Logout All Users
                </button>
            </div>

            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Email Verified</th>
                            <th>Verified On</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $index => $user)
                            <tr>
                                <td class="td-num">{{ $users->firstItem() + $index }}</td>
                                <td>
                                    <div class="user-cell">
                                        <img src="{{ $user->profile_picture_url }}" alt="{{ $user->name }}" class="user-avatar" />
                                        <div>
                                            <span>{{ $user->name }}</span>
                                            @if(in_array($user->id, $activeUserIds))
                                                <span class="badge-online">● Online</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="td-email">{{ $user->email }}</td>
                                <td>
                                    @if($user->email_verified_at)
                                        <span class="badge-green">✓ Verified</span>
                                    @else
                                        <span class="badge-gray">✗ Unverified</span>
                                    @endif
                                </td>
                                <td class="td-date">
                                    {{ $user->email_verified_at ? $user->email_verified_at->format('M d, Y H:i') : '—' }}
                                </td>
                                <td>
                                    @if($user->is_admin)
                                        <span class="badge-blue">Admin</span>
                                    @else
                                        <span class="badge-slate">User</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="empty-state">No users found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrap">
                <span class="pag-info">
                    Showing {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }}
                </span>
                {{ $users->links() }}
            </div>
        </div>
    </main>
</div>

<!-- Clear All Users Confirmation Modal -->
<div id="clearAllModal" class="modal-overlay" style="display:none;" onclick="if(event.target===this)this.style.display='none'">
    <div class="modal-box">
        <div class="modal-header">
            <h3>🗑️ Clear All Users</h3>
            <button class="modal-close" onclick="document.getElementById('clearAllModal').style.display='none'">✕</button>
        </div>
        <p style="color:#475569;font-size:0.9rem;margin:0 0 20px;">This will <strong>permanently delete all non-admin user accounts</strong>, clear their cart items, and end all their sessions. Order history will be preserved. This action <strong>cannot be undone</strong>.</p>
        <form method="POST" action="{{ route('admin.users.clearAll') }}">
            @csrf
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="document.getElementById('clearAllModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn-confirm-clear">Yes, Delete All Users</button>
            </div>
        </form>
    </div>
</div>

<!-- Reset Active Users Confirmation Modal -->
<div id="resetModal" class="modal-overlay" style="display:none;" onclick="if(event.target===this)this.style.display='none'">
    <div class="modal-box">
        <div class="modal-header">
            <h3>🔄 Reset Active Users</h3>
            <button class="modal-close" onclick="document.getElementById('resetModal').style.display='none'">✕</button>
        </div>
        <p style="color:#475569;font-size:0.9rem;margin:0 0 20px;">This will clear all active user sessions and reset the Active Users list. Users will need to log in again to appear here. No accounts or data will be deleted.</p>
        <form method="POST" action="{{ route('admin.users.reset') }}">
            @csrf
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="document.getElementById('resetModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn-confirm-reset">Yes, Reset List</button>
            </div>
        </form>
    </div>
</div>

<!-- Force Logout Confirmation Modal -->
<div id="forceLogoutModal" class="modal-overlay" style="display:none;" onclick="if(event.target===this)this.style.display='none'">
    <div class="modal-box">
        <div class="modal-header">
            <h3>⚠️ Force Logout All Users</h3>
            <button class="modal-close" onclick="document.getElementById('forceLogoutModal').style.display='none'">✕</button>
        </div>
        <p style="color:#475569;font-size:0.9rem;margin:0 0 20px;">This will immediately invalidate all active user sessions. All users will be required to log in again. This action does not delete any accounts or data.</p>
        <form method="POST" action="{{ route('admin.users.forceLogout') }}">
            @csrf
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="document.getElementById('forceLogoutModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn-confirm-logout">Yes, Force Logout All</button>
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

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 16px;
        background: #fff;
        border: 1px solid #e2e8f0;
        color: #475569;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.2s;
    }

    .btn-back:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
    }

    .alert-success, .alert-error {
        padding: 12px 16px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .alert-error   { background: #fff1f2; color: #9f1239; border: 1px solid #fecdd3; }

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
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header h2 { margin: 0 0 3px; font-size: 1.1rem; color: #0f172a; font-weight: 700; }
    .card-header p  { margin: 0; color: #64748b; font-size: 0.85rem; }

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

    .td-num { color: #94a3b8; font-weight: 600; width: 48px; }
    .td-email { color: #64748b; font-size: 0.85rem; }

    .user-cell {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        color: #0f172a;
    }

    .user-avatar {
        width: 36px; height: 36px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid #e2e8f0;
        flex-shrink: 0;
    }

    .td-date { color: #64748b; font-size: 0.83rem; white-space: nowrap; }

    .badge-online {
        display: inline-block;
        font-size: 0.7rem;
        font-weight: 700;
        color: #16a34a;
        margin-left: 6px;
    }

    .badge-green, .badge-gray, .badge-blue, .badge-slate {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .badge-green { background: #f0fdf4; color: #16a34a; }
    .badge-gray  { background: #f8fafc; color: #94a3b8; }
    .badge-blue  { background: #eff6ff; color: #1d4ed8; }
    .badge-slate { background: #f1f5f9; color: #475569; }

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

    .btn-force-logout {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 16px;
        background: linear-gradient(135deg, #f43f5e, #e11d48);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-force-logout:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(244,63,94,0.4);
    }

    .btn-clear-all {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 16px;
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-clear-all:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220,38,38,0.4);
    }

    .btn-confirm-clear {
        padding: 9px 18px;
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.88rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-confirm-clear:hover { box-shadow: 0 4px 12px rgba(220,38,38,0.4); }

    .btn-reset {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 16px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-reset:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(245,158,11,0.4);
    }

    .btn-confirm-reset {
        padding: 9px 18px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.88rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-confirm-reset:hover { box-shadow: 0 4px 12px rgba(245,158,11,0.4); }

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
        margin-bottom: 16px;
    }

    .modal-header h3 { margin: 0; font-size: 1.05rem; color: #0f172a; }

    .modal-close {
        background: none; border: none;
        font-size: 1.1rem; cursor: pointer;
        color: #94a3b8; padding: 2px 6px;
        border-radius: 6px;
    }
    .modal-close:hover { background: #f1f5f9; color: #475569; }

    .modal-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
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

    .btn-confirm-logout {
        padding: 9px 18px;
        background: linear-gradient(135deg, #f43f5e, #e11d48);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.88rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-confirm-logout:hover { box-shadow: 0 4px 12px rgba(244,63,94,0.4); }

    @media (max-width: 768px) {
        .admin-main { margin-left: 0; padding: 16px; }
    }
</style>
@endsection
