@extends('layouts.app')
@section('title', 'My Messages – Healthcare Pharmacy')

@section('content')
<div class="page-header">
    <div class="page-panel">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
            <div>
                <p style="margin:0 0 8px;color:var(--accent);font-size:13px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;">Account</p>
                <h1 style="margin:0;">My Messages</h1>
                <p style="margin:10px 0 0;color:var(--muted);">View your conversations with our support team.</p>
            </div>
            <a href="{{ route('contact') }}" class="btn-primary" style="padding:12px 22px;white-space:nowrap;">+ New Message</a>
        </div>

        @if(session('success'))
            <div class="flash-message" style="margin-top:20px;">{{ session('success') }}</div>
        @endif

        <div style="margin-top:28px;display:flex;flex-direction:column;gap:12px;">
            @forelse($messages as $msg)
            <a href="{{ route('messages.thread', $msg) }}"
               style="display:flex;align-items:center;justify-content:space-between;gap:16px;padding:18px 22px;background:{{ $msg->has_unread_reply ? '#f0fdf4' : '#fff' }};border:1.5px solid {{ $msg->has_unread_reply ? '#86efac' : 'rgba(15,23,42,0.08)' }};border-radius:18px;text-decoration:none;color:var(--text);transition:box-shadow 0.2s;box-shadow:0 2px 10px rgba(0,0,0,0.04);">
                <div style="flex:1;min-width:0;">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px;">
                        <span style="font-weight:800;font-size:1rem;color:#0f172a;">{{ $msg->subject ?: 'No Subject' }}</span>
                        @if($msg->has_unread_reply)
                            <span style="background:#ef4444;color:#fff;font-size:0.7rem;font-weight:800;padding:2px 8px;border-radius:999px;">New Reply</span>
                        @endif
                    </div>
                    <p style="margin:0;font-size:0.85rem;color:#64748b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ Str::limit($msg->body, 80) }}</p>
                </div>
                <div style="text-align:right;flex-shrink:0;">
                    <div style="font-size:0.78rem;color:#94a3b8;">{{ $msg->created_at->diffForHumans() }}</div>
                    <div style="font-size:0.78rem;color:#64748b;margin-top:3px;">{{ $msg->replies->count() }} {{ Str::plural('reply', $msg->replies->count()) }}</div>
                </div>
            </a>
            @empty
            <div style="text-align:center;padding:52px 24px;background:#f8fafc;border-radius:20px;border:1px solid #e2e8f0;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" style="margin:0 auto 16px;display:block;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                <p style="color:#64748b;margin:0;font-size:0.95rem;">No messages yet. <a href="{{ route('contact') }}" style="color:var(--accent);font-weight:700;">Send us a message</a></p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
