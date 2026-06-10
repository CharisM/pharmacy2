@extends('layouts.app')
@section('title', 'Message Thread – Healthcare Pharmacy')

@section('content')
<div class="page-header">
    <div class="page-panel">
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:24px;flex-wrap:wrap;">
            <a href="{{ route('messages.index') }}" style="display:inline-flex;align-items:center;gap:6px;color:var(--accent);font-weight:700;text-decoration:none;font-size:0.9rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                Back to Messages
            </a>
            <span style="color:#cbd5e1;">|</span>
            <h1 style="margin:0;font-size:1.4rem;">{{ $message->subject ?: 'No Subject' }}</h1>
        </div>

        @if(session('success'))
            <div class="flash-message" style="margin-bottom:20px;">{{ session('success') }}</div>
        @endif

        {{-- Original message --}}
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div style="background:#f0fdf4;border:1px solid #86efac;border-radius:16px;padding:20px 22px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                    <span style="font-weight:800;color:#166534;font-size:0.88rem;">You (original message)</span>
                    <span style="font-size:0.78rem;color:#64748b;">{{ $message->created_at->format('M j, Y g:i A') }}</span>
                </div>
                <p style="margin:0;color:#0f172a;line-height:1.8;white-space:pre-wrap;">{{ $message->body }}</p>
            </div>

            {{-- Replies --}}
            @foreach($message->replies as $reply)
            <div style="
                background:{{ $reply->is_admin ? '#eff6ff' : '#f0fdf4' }};
                border:1px solid {{ $reply->is_admin ? '#bfdbfe' : '#86efac' }};
                border-radius:16px;padding:20px 22px;
                {{ $reply->is_admin ? 'margin-left:0;' : 'margin-left:32px;' }}">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                    <span style="font-weight:800;color:{{ $reply->is_admin ? '#1d4ed8' : '#166534' }};font-size:0.88rem;">
                        {{ $reply->is_admin ? '💊 Healthcare Pharmacy Support' : 'You' }}
                    </span>
                    <span style="font-size:0.78rem;color:#64748b;">{{ $reply->created_at->format('M j, Y g:i A') }}</span>
                </div>
                <p style="margin:0;color:#0f172a;line-height:1.8;white-space:pre-wrap;">{{ $reply->body }}</p>
            </div>
            @endforeach
        </div>

        {{-- Reply form --}}
        <div style="margin-top:28px;background:#fff;border:1.5px solid #e2e8f0;border-radius:18px;padding:22px;">
            <h3 style="margin:0 0 16px;font-size:1rem;color:#0f172a;">Reply to this conversation</h3>
            <form action="{{ route('messages.reply', $message) }}" method="POST">
                @csrf
                <textarea name="body" rows="4" required placeholder="Type your reply…"
                    style="width:100%;padding:12px 16px;border:1.5px solid #e2e8f0;border-radius:12px;font-size:0.9rem;font-family:inherit;color:#0f172a;resize:vertical;box-sizing:border-box;"></textarea>
                @error('body') <p style="color:#e11d48;font-size:0.82rem;margin:4px 0 0;">{{ $message }}</p> @enderror
                <button type="submit" class="btn-primary" style="margin-top:12px;padding:12px 24px;">Send Reply</button>
            </form>
        </div>
    </div>
</div>
@endsection
