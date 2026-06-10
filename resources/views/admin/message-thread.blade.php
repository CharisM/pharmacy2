@extends('layouts.app')

@section('content')
<div class="admin-shell">
    @include('partials.admin-sidebar')

    <main class="admin-main">
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:8px;flex-wrap:wrap;">
            <a href="{{ route('admin.messages') }}" style="display:inline-flex;align-items:center;gap:6px;color:#6366f1;font-weight:700;text-decoration:none;font-size:0.9rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                Back to Messages
            </a>
        </div>

        <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;padding:24px;display:flex;flex-direction:column;gap:6px;">
            <div style="border-bottom:1px solid #f1f5f9;padding-bottom:16px;margin-bottom:16px;">
                <h2 style="margin:0 0 4px;font-size:1.2rem;font-weight:800;color:#0f172a;">{{ $message->subject ?: 'No Subject' }}</h2>
                <p style="margin:0;font-size:0.85rem;color:#64748b;">From: <strong>{{ $message->name }}</strong> &lt;{{ $message->email }}&gt; · {{ $message->created_at->format('M j, Y g:i A') }}</p>
            </div>

            @if(session('success'))
                <div style="margin-bottom:16px;padding:12px 16px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;color:#166534;font-weight:600;font-size:0.9rem;">✅ {{ session('success') }}</div>
            @endif

            {{-- Conversation thread --}}
            <div style="display:flex;flex-direction:column;gap:14px;">
                {{-- Original message --}}
                <div style="background:#f0fdf4;border:1px solid #86efac;border-radius:14px;padding:18px 20px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                        <span style="font-weight:800;color:#166534;font-size:0.85rem;">{{ $message->name }} (original message)</span>
                        <span style="font-size:0.78rem;color:#64748b;">{{ $message->created_at->format('M j, Y g:i A') }}</span>
                    </div>
                    <p style="margin:0;color:#0f172a;line-height:1.8;white-space:pre-wrap;">{{ $message->body }}</p>
                </div>

                {{-- Replies --}}
                @foreach($message->replies as $reply)
                <div style="
                    background:{{ $reply->is_admin ? '#eff6ff' : '#f0fdf4' }};
                    border:1px solid {{ $reply->is_admin ? '#bfdbfe' : '#86efac' }};
                    border-radius:14px;padding:18px 20px;
                    {{ !$reply->is_admin ? 'margin-left:32px;' : '' }}">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                        <span style="font-weight:800;color:{{ $reply->is_admin ? '#1d4ed8' : '#166534' }};font-size:0.85rem;">
                            {{ $reply->is_admin ? '💊 Admin (You)' : $message->name }}
                        </span>
                        <span style="font-size:0.78rem;color:#64748b;">{{ $reply->created_at->format('M j, Y g:i A') }}</span>
                    </div>
                    <p style="margin:0;color:#0f172a;line-height:1.8;white-space:pre-wrap;">{{ $reply->body }}</p>
                </div>
                @endforeach
            </div>

            {{-- Admin reply form --}}
            <div style="margin-top:20px;padding-top:20px;border-top:1px solid #f1f5f9;">
                <h3 style="margin:0 0 14px;font-size:1rem;font-weight:700;color:#0f172a;">Send Reply to User</h3>
                <form action="{{ route('admin.messages.reply', $message) }}" method="POST">
                    @csrf
                    <textarea name="body" rows="4" required placeholder="Type your reply…"
                        style="width:100%;padding:12px 16px;border:1.5px solid #e2e8f0;border-radius:12px;font-size:0.9rem;font-family:inherit;color:#0f172a;resize:vertical;margin-bottom:12px;box-sizing:border-box;"></textarea>
                    @error('body')<p style="color:#e11d48;font-size:0.82rem;margin:-8px 0 8px;">{{ $message }}</p>@enderror
                    <button type="submit"
                        style="display:inline-flex;align-items:center;gap:6px;padding:10px 22px;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;border:none;border-radius:9px;font-weight:700;font-size:0.88rem;cursor:pointer;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        Send Reply
                    </button>
                </form>
            </div>
        </div>
    </main>
</div>

<style>
    * { box-sizing: border-box; }
    body { margin: 0; font-family: 'Inter', system-ui, sans-serif; background: #f1f5f9; }
    .admin-shell { display: flex; min-height: 100vh; }
    .admin-main { margin-left: 260px; padding: 28px 30px; flex: 1; display: flex; flex-direction: column; gap: 16px; }
    @media (max-width: 768px) { .admin-main { margin-left: 0; padding: 16px; } }
</style>
@endsection
