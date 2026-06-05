@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="page-header">
        <div class="page-panel">
            <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:24px; flex-wrap:wrap;">
                <div>
                    <p style="margin:0 0 12px;color:var(--accent);font-size:13px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;">Account</p>
                    <h1 style="margin:0;font-size:clamp(2rem,2.5vw,2.75rem);">Your Profile</h1>
                    <p style="margin:16px 0 0;max-width:640px;line-height:1.8;color:var(--muted);">Manage your account details, access order history, and keep your profile information up to date.</p>
                </div>
                <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:center;">
                    <a href="{{ route('profile.edit') }}" class="btn-primary" style="white-space:nowrap; padding:12px 20px;">Edit profile</a>
                    <a href="{{ route('home') }}" class="btn-outline" style="white-space:nowrap;">Back to store</a>
                </div>
            </div>

            @if (session('success'))
                <div style="margin-top:24px; padding:16px; border:1px solid #1f9d55; border-radius:8px; background:#ecfdf5; color:#166534;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="profile-card" style="margin-top:32px;">
                <div class="profile-grid">
                    <div style="display:flex; flex-direction:column; gap:16px;">
                        <div style="display:flex; align-items:center; gap:16px;">
                            <img src="{{ auth()->user()->profile_picture_url }}" alt="Profile picture" style="width:120px; height:120px; border-radius:50%; object-fit:cover; border:1px solid #e2e8f0;" />
                            <div>
                                <h2>Personal details</h2>
                                <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
                                <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h2>Account status</h2>
                        <p><strong>Member since:</strong> {{ auth()->user()->created_at->format('F j, Y') }}</p>
                        <p><strong>Role:</strong> {{ auth()->user()->is_admin ? 'Administrator' : 'Customer' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
