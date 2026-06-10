@extends('layouts.app')

@section('content')
<div class="admin-shell">
    @include('partials.admin-sidebar')

    <main class="admin-main">

        <div class="page-header">
            <div>
                <a href="{{ route('admin.messages') }}" class="back-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="15" height="15"><polyline points="15 18 9 12 15 6"/></svg>
                    Back to Messages
                </a>
                <h1>{{ $message->subject ?: 'No Subject' }}</h1>
                <p>From <strong>{{ $message->name }}</strong> &lt;{{ $message->email }}&gt; · {{ $message->created_at->format('M j, Y g:i A') }}</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success">✅ {{ session('success') }}</div>
        @endif

        {{-- Thread --}}
        <div class="content-card thread-card">

            {{-- Original message --}}
            <div class="bubble bubble-user">
                <div class="bubble-header">
                    <span class="bubble-author">{{ $message->name }} <span class="bubble-tag">original message</span></span>
                    <span class="bubble-time">{{ $message->created_at->format('M j, Y g:i A') }}</span>
                </div>
                <p class="bubble-body">{{ $message->body }}</p>
            </div>

            {{-- Replies --}}
            @foreach($message->replies as $reply)
            <div class="bubble {{ $reply->is_admin ? 'bubble-admin' : 'bubble-user' }}">
                <div class="bubble-header">
                    <span class="bubble-author">
                        {{ $reply->is_admin ? '💊 Admin (You)' : $message->name }}
                    </span>
                    <span class="bubble-time">{{ $reply->created_at->format('M j, Y g:i A') }}</span>
                </div>
                <p class="bubble-body">{{ $reply->body }}</p>
            </div>
            @endforeach

            {{-- Reply form --}}
            <div class="reply-section">
                <h3 class="reply-title">Send Reply</h3>
                <form action="{{ route('admin.messages.reply', $message) }}" method="POST">
                    @csrf
                    <textarea name="body" rows="4" required placeholder="Type your reply to {{ $message->name }}…"
                              class="reply-textarea"></textarea>
                    @error('body')
                        <p class="reply-error">{{ $message }}</p>
                    @enderror
                    <button type="submit" class="btn-send">
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
    .admin-main  { margin-left: 260px; padding: 28px 30px; flex: 1; display: flex; flex-direction: column; gap: 22px; }

    .page-header h1 { margin: 6px 0 4px; font-size: 1.5rem; font-weight: 800; color: #0f172a; }
    .page-header p  { margin: 0; color: #64748b; font-size: 0.88rem; }

    .back-link { display: inline-flex; align-items: center; gap: 5px; color: #6366f1; font-weight: 700; font-size: 0.85rem; text-decoration: none; margin-bottom: 4px; }
    .back-link:hover { color: #4f46e5; }

    .alert-success { padding: 12px 16px; background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; border-radius: 10px; font-weight: 600; font-size: 0.9rem; }

    /* Content card */
    .content-card { background: #fff; border-radius: 14px; border: 1px solid #e2e8f0; box-shadow: 0 1px 6px rgba(0,0,0,0.04); overflow: hidden; }
    .thread-card  { display: flex; flex-direction: column; gap: 0; }

    /* Bubbles */
    .bubble { padding: 20px 24px; border-bottom: 1px solid #f1f5f9; }
    .bubble:last-child { border-bottom: none; }

    .bubble-user  { background: #f0fdf4; border-left: 3px solid #86efac; }
    .bubble-admin { background: #eff6ff; border-left: 3px solid #bfdbfe; }

    .bubble-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; flex-wrap: wrap; gap: 6px; }
    .bubble-author { font-weight: 800; font-size: 0.88rem; color: #0f172a; }
    .bubble-tag    { font-weight: 500; font-size: 0.78rem; color: #94a3b8; margin-left: 6px; }
    .bubble-time   { font-size: 0.78rem; color: #94a3b8; }
    .bubble-body   { margin: 0; color: #0f172a; line-height: 1.8; white-space: pre-wrap; font-size: 0.9rem; }

    /* Reply form */
    .reply-section { padding: 22px 24px; background: #f8fafc; border-top: 1px solid #e2e8f0; }
    .reply-title   { margin: 0 0 14px; font-size: 1rem; font-weight: 700; color: #0f172a; }
    .reply-textarea {
        width: 100%; padding: 12px 16px; border: 1.5px solid #e2e8f0;
        border-radius: 10px; font-size: 0.9rem; font-family: inherit;
        color: #0f172a; resize: vertical; margin-bottom: 12px;
        transition: border-color 0.18s, box-shadow 0.18s;
        background: #fff;
    }
    .reply-textarea:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.12); }
    .reply-error { color: #e11d48; font-size: 0.82rem; margin: -8px 0 8px; }
    .btn-send { display: inline-flex; align-items: center; gap: 6px; padding: 10px 22px; background: linear-gradient(135deg,#6366f1,#8b5cf6); color: #fff; border: none; border-radius: 9px; font-weight: 700; font-size: 0.88rem; cursor: pointer; transition: all 0.2s; font-family: inherit; }
    .btn-send:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(99,102,241,0.45); }

    @media (max-width: 768px) {
        .admin-main { margin-left: 0; padding: 16px; }
    }
</style>
@endsection
