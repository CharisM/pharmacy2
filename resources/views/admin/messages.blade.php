@extends('layouts.app')

@section('content')
<div class="admin-shell">
    @include('partials.admin-sidebar')

    <main class="admin-main">
        <div class="page-header" style="display:flex;justify-content:space-between;align-items:center;">
            <div>
                <h1>Messages</h1>
                <p>User inquiries and support conversations</p>
            </div>
            @if($unreadCount > 0)
                <span style="background:#ef4444;color:#fff;font-weight:800;padding:6px 16px;border-radius:999px;font-size:0.85rem;">{{ $unreadCount }} Unread</span>
            @endif
        </div>

        @if(session('success'))
            <div class="alert-success">✅ {{ session('success') }}</div>
        @endif

        {{-- Filters --}}
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:14px 18px;">
            <form method="GET" style="display:flex;flex-wrap:wrap;gap:10px;align-items:center;">
                <div style="position:relative;flex:1;min-width:200px;">
                    <svg style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#94a3b8;pointer-events:none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, subject…"
                        style="width:100%;padding:9px 12px 9px 36px;border:1px solid #e2e8f0;border-radius:8px;font-size:0.88rem;box-sizing:border-box;" />
                </div>
                <select name="filter" style="padding:9px 12px;border:1px solid #e2e8f0;border-radius:8px;font-size:0.88rem;color:#334155;background:#f8fafc;">
                    <option value="">All Messages</option>
                    <option value="unread" {{ request('filter') == 'unread' ? 'selected' : '' }}>Unread</option>
                    <option value="replied" {{ request('filter') == 'replied' ? 'selected' : '' }}>Replied</option>
                </select>
                <button type="submit" style="padding:9px 18px;background:#6366f1;color:#fff;border:none;border-radius:8px;font-weight:600;font-size:0.88rem;cursor:pointer;">Filter</button>
                @if(request()->hasAny(['search','filter']))
                    <a href="{{ route('admin.messages') }}" style="padding:9px 14px;background:#f1f5f9;color:#64748b;border:1px solid #e2e8f0;border-radius:8px;font-size:0.88rem;text-decoration:none;font-weight:600;">Clear</a>
                @endif
            </form>
        </div>

        {{-- Messages List --}}
        <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;">
            @forelse($messages as $msg)
            <div style="display:flex;align-items:center;gap:16px;padding:18px 22px;border-bottom:1px solid #f1f5f9;{{ $msg->admin_read ? '' : 'background:#fefce8;' }}transition:background 0.2s;">
                <div style="flex:1;min-width:0;">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px;flex-wrap:wrap;">
                        @if(!$msg->admin_read)
                            <span style="width:8px;height:8px;background:#ef4444;border-radius:50%;flex-shrink:0;"></span>
                        @endif
                        <span style="font-weight:800;color:#0f172a;font-size:0.95rem;">{{ $msg->name }}</span>
                        <span style="font-size:0.8rem;color:#64748b;">{{ $msg->email }}</span>
                        @if($msg->subject)
                            <span style="font-size:0.8rem;color:#475569;background:#f1f5f9;padding:2px 8px;border-radius:999px;">{{ $msg->subject }}</span>
                        @endif
                    </div>
                    <p style="margin:0;font-size:0.85rem;color:#64748b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:500px;">{{ Str::limit($msg->body, 90) }}</p>
                </div>
                <div style="text-align:right;flex-shrink:0;display:flex;flex-direction:column;align-items:flex-end;gap:8px;">
                    <span style="font-size:0.78rem;color:#94a3b8;">{{ $msg->created_at->diffForHumans() }}</span>
                    <div style="display:flex;gap:8px;">
                        <a href="{{ route('admin.messages.thread', $msg) }}"
                           style="display:inline-flex;align-items:center;gap:5px;padding:6px 14px;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;border-radius:8px;font-size:0.8rem;font-weight:700;text-decoration:none;">
                            View & Reply
                        </a>
                        <form method="POST" action="{{ route('admin.messages.destroy', $msg) }}" onsubmit="return confirm('Delete this message?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="padding:6px 12px;background:#fff1f2;color:#e11d48;border:1px solid #fecdd3;border-radius:8px;font-size:0.8rem;font-weight:700;cursor:pointer;">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:52px 24px;color:#94a3b8;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin:0 auto 14px;display:block;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                No messages found.
            </div>
            @endforelse
        </div>

        @if($messages->hasPages())
            <div style="padding:14px 0;">{{ $messages->withQueryString()->links() }}</div>
        @endif
    </main>
</div>

<style>
    * { box-sizing: border-box; }
    body { margin: 0; font-family: 'Inter', system-ui, sans-serif; background: #f1f5f9; }
    .admin-shell { display: flex; min-height: 100vh; }
    .admin-main { margin-left: 260px; padding: 28px 30px; flex: 1; display: flex; flex-direction: column; gap: 20px; }
    .page-header h1 { margin: 0 0 4px; font-size: 1.8rem; font-weight: 800; color: #0f172a; }
    .page-header p { margin: 0; color: #64748b; font-size: 0.9rem; }
    .alert-success { padding: 12px 16px; background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; border-radius: 10px; font-weight: 600; font-size: 0.9rem; }
    @media (max-width: 768px) { .admin-main { margin-left: 0; padding: 16px; } }
</style>
@endsection
