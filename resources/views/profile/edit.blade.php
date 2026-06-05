@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
    <div class="page-header">
        <div class="page-panel">
            <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:24px; flex-wrap:wrap;">
                <div>
                    <p style="margin:0 0 12px;color:var(--accent);font-size:13px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;">Account</p>
                    <h1 style="margin:0;font-size:clamp(2rem,2.5vw,2.75rem);">Edit Your Profile</h1>
                    <p style="margin:16px 0 0;max-width:640px;line-height:1.8;color:var(--muted);">Update your name, email, and password here.</p>
                </div>
                <a href="{{ route('profile') }}" class="btn-outline" style="white-space:nowrap;">Back to profile</a>
            </div>

            <div class="profile-card" style="margin-top:32px; max-width:720px;">
                @if ($errors->any())
                    <div style="margin-bottom:20px; padding:16px; border:1px solid #e74c3c; border-radius:8px; background:#fee; color:#7f1a1a;">
                        <strong>Whoops! Something went wrong.</strong>
                        <ul style="margin:12px 0 0 18px; padding:0; list-style-type:disc;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" style="display:grid; gap:16px;">
                    @csrf
                    @method('PUT')

                    <div style="display:flex; align-items:center; gap:18px; flex-wrap:wrap;">
                        <img src="{{ auth()->user()->profile_picture_url }}" alt="Profile picture" style="width:110px; height:110px; object-fit:cover; border-radius:50%; border:1px solid #e2e8f0;" />
                        <div>
                            <p style="margin:0 0 8px; font-weight:700;">Current Profile Picture</p>
                            <p style="margin:0;color:var(--muted);">Upload a new image to replace it.</p>
                        </div>
                    </div>

                    <label style="display:block; position:relative;">
                        <span style="display:block; margin-bottom:8px; font-weight:700;">Profile Picture</span>
                        <div style="display:flex; flex-wrap:wrap; gap:12px; align-items:center;">
                            <button type="button" class="btn-outline" style="padding:10px 18px;" onclick="document.getElementById('profile_picture_input').click();">Choose file</button>
                        </div>
                        <input id="profile_picture_input" type="file" name="profile_picture" accept="image/*" style="position:absolute; top:0; left:0; opacity:0; width:100%; height:100%; pointer-events:none;" />
                    </label>

                    <label style="display:block;">
                        <span style="display:block; margin-bottom:8px; font-weight:700;">Name</span>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required style="width:100%; padding:12px; border:1px solid #d2d6dc; border-radius:8px;" />
                    </label>

                    <label style="display:block;">
                        <span style="display:block; margin-bottom:8px; font-weight:700;">Email</span>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required style="width:100%; padding:12px; border:1px solid #d2d6dc; border-radius:8px;" />
                    </label>

                    <label style="display:block;">
                        <span style="display:block; margin-bottom:8px; font-weight:700;">New Password <small style="color:var(--muted); font-weight:400;">(leave blank to keep current password)</small></span>
                        <input type="password" name="password" autocomplete="new-password" style="width:100%; padding:12px; border:1px solid #d2d6dc; border-radius:8px;" />
                    </label>

                    <label style="display:block;">
                        <span style="display:block; margin-bottom:8px; font-weight:700;">Confirm Password</span>
                        <input type="password" name="password_confirmation" autocomplete="new-password" style="width:100%; padding:12px; border:1px solid #d2d6dc; border-radius:8px;" />
                    </label>

                    <div style="display:flex; flex-wrap:wrap; gap:12px; align-items:center; margin-top:8px;">
                        <button type="submit" class="btn-primary" style="padding:12px 20px;">Save changes</button>
                        <a href="{{ route('profile') }}" class="btn-outline" style="padding:12px 20px;">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
