@extends('layouts.app')

@section('content')
<div class="admin-shell">
    @include('partials.admin-sidebar')

    <main class="admin-main">

        <div class="page-header">
            <div>
                <h1>Messages</h1>
                <p>User inquiries and support conversations</p>
            </div>
            @if($unreadCount > 0)
                <span class="unread-badge">{{ $unreadCount }} Unread</span>
            @endif
        </div>

        @if(session('success'))
            <div class="alert-success">✅ {{ session('success') }}</div>
        @endif

        {{-- Filters --}}
        <div class="content-card" style="padding:14px 18px;">
            <form method="GET" action="{{ route('admin.messages') }}" class="filters-form">
                <div class="search-wrap">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search by name, email or subject…" class="search-input" />
                </div>
                <select name="filter" class="filter-select">
                    <option value="">All Messages</option>
                    <option value="unread"  {{ request('filter') == 'unread'  ? 'selected' : '' }}>Unread</option>
                    <option value="replied" {{ request('filter') == 'replied' ? 'selected' : '' }}>Replied</option>
                </select>
                <button type="submit" class="btn-filter">Filter</button>
                @if(request()->hasAny(['search','filter']))
                    <a href="{{ route('admin.messages') }}" class="btn-filter-clear">Clear</a>
                @endif
            </form>
        </div>

        {{-- Messages list --}}
        <div class="content-card">
            <div class="card-header">
                <div>
                    <h2>All Messages</h2>
                    <p>{{ $messages->total() }} message(s)</p>
                </div>
            </div>

            @forelse($messages as $msg)
            <div class="msg-row {{ $msg->admin_read ? '' : 'msg-unread' }}">
                <div class="msg-left">
                    <div class="msg-meta-row">
                        @if(!$msg->admin_read)
                            <span class="unread-dot"></span>
                        @endif
                        <span class="msg-sender">{{ $msg->name }}</span>
                        <span class="msg-email">{{ $msg->email }}</span>
                        @if($msg->subject)
                            <span class="msg-subject-pill">{{ $msg->subject }}</span>
                        @endif
                    </div>
                    <p class="msg-preview">{{ Str::limit($msg->body, 100) }}</p>
                </div>
                <div class="msg-right">
                    <span class="msg-time">{{ $msg->created_at->diffForHumans() }}</span>
                    <div class="msg-actions">
                        <a href="{{ route('admin.messages.thread', $msg) }}" class="btn-view-msg">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            View & Reply
                        </a>
                        <form method="POST" action="{{ route('admin.messages.destroy', $msg) }}"
                              onsubmit="return confirm('Delete this message thread?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete-msg">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="msg-empty">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                <p>No messages found.</p>
            </div>
            @endforelse

            @if($messages->hasPages())
            <div class="pagination-wrap">
                <span class="pag-info">Showing {{ $messages->firstItem() }}–{{ $messages->lastItem() }} of {{ $messages->total() }}</span>
                {{ $messages->withQueryString()->links() }}
            </div>
            @endif
        </div>

    </main>
</div>

<style>
    * { box-sizing: border-box; }
    body { margin: 0; font-family: 'Inter', system-ui, sans-serif; background: #f1f5f9; }
    .admin-shell { display: flex; min-height: 100vh; }
    .admin-main  { margin-left: 260px; padding: 28px 30px; flex: 1; display: flex; flex-direction: column; gap: 22px; }

    .page-header { display: flex; justify-content: space-between; align-items: center; }
    .page-header h1 { margin: 0 0 4px; font-size: 1.8rem; font-weight: 800; color: #0f172a; }
    .page-header p  { margin: 0; color: #64748b; font-size: 0.9rem; }

    .unread-badge { background: #ef4444; color: #fff; font-weight: 800; padding: 6px 16px; border-radius: 999px; font-size: 0.85rem; }

    .alert-success { padding: 12px 16px; background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; border-radius: 10px; font-weight: 600; font-size: 0.9rem; }

    /* Filters */
    .filters-form  { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; }
    .search-wrap   { position: relative; flex: 1; min-width: 220px; }
    .search-icon   { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none; }
    .search-input  { width: 100%; padding: 9px 12px 9px 36px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.88rem; color: #0f172a; background: #f8fafc; }
    .search-input:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
    .filter-select { padding: 9px 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.88rem; color: #334155; background: #f8fafc; }
    .btn-filter       { padding: 9px 18px; background: #6366f1; color: #fff; border: none; border-radius: 8px; font-weight: 600; font-size: 0.88rem; cursor: pointer; }
    .btn-filter:hover { background: #4f46e5; }
    .btn-filter-clear { padding: 9px 14px; background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.88rem; text-decoration: none; font-weight: 600; }

    /* Content card */
    .content-card { background: #fff; border-radius: 14px; border: 1px solid #e2e8f0; box-shadow: 0 1px 6px rgba(0,0,0,0.04); overflow: hidden; }
    .card-header  { padding: 20px 24px; border-bottom: 1px solid #f1f5f9; }
    .card-header h2 { margin: 0 0 3px; font-size: 1.1rem; color: #0f172a; font-weight: 700; }
    .card-header p  { margin: 0; color: #64748b; font-size: 0.85rem; }

    /* Message rows */
    .msg-row { display: flex; align-items: center; gap: 16px; padding: 18px 22px; border-bottom: 1px solid #f1f5f9; transition: background 0.18s; }
    .msg-row:last-child { border-bottom: none; }
    .msg-row:hover { background: #f8fafc; }
    .msg-unread { background: #fefce8; }
    .msg-unread:hover { background: #fef9c3; }

    .msg-left { flex: 1; min-width: 0; }
    .msg-meta-row { display: flex; align-items: center; gap: 8px; margin-bottom: 5px; flex-wrap: wrap; }
    .unread-dot   { width: 8px; height: 8px; background: #ef4444; border-radius: 50%; flex-shrink: 0; }
    .msg-sender   { font-weight: 800; color: #0f172a; font-size: 0.95rem; }
    .msg-email    { font-size: 0.8rem; color: #64748b; }
    .msg-subject-pill { font-size: 0.78rem; color: #475569; background: #f1f5f9; padding: 2px 9px; border-radius: 999px; font-weight: 600; }
    .msg-preview  { margin: 0; font-size: 0.85rem; color: #64748b; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 520px; }

    .msg-right { text-align: right; flex-shrink: 0; display: flex; flex-direction: column; align-items: flex-end; gap: 8px; }
    .msg-time  { font-size: 0.78rem; color: #94a3b8; }
    .msg-actions { display: flex; gap: 8px; }

    .btn-view-msg { display:inline-flex;align-items:center;gap:5px;padding:6px 14px;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;border-radius:8px;font-size:0.8rem;font-weight:700;text-decoration:none;transition:all 0.2s;white-space:nowrap; }
    .btn-view-msg:hover { transform:translateY(-1px);box-shadow:0 4px 12px rgba(99,102,241,0.4); }

    .btn-delete-msg { display:inline-flex;align-items:center;gap:5px;padding:6px 12px;background:#fff1f2;color:#e11d48;border:1px solid #fecdd3;border-radius:8px;font-size:0.8rem;font-weight:700;cursor:pointer;transition:all 0.2s;white-space:nowrap; }
    .btn-delete-msg:hover { background:#fee2e2;border-color:#fca5a5; }

    .msg-empty { text-align: center; padding: 52px 24px; color: #94a3b8; }
    .msg-empty svg { display: block; margin: 0 auto 14px; }
    .msg-empty p { margin: 0; font-size: 0.9rem; }

    .pagination-wrap { padding: 16px 20px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
    .pag-info { font-size: 0.8rem; color: #94a3b8; font-weight: 600; }

    @media (max-width: 768px) {
        .admin-main { margin-left: 0; padding: 16px; }
        .msg-row { flex-direction: column; align-items: flex-start; }
        .msg-right { align-items: flex-start; }
    }
</style>
@endsection
