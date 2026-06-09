<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register – Healthcare Pharmacy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Nunito', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #b7f0c8 0%, #DAFAE0 50%, #a8edbc 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .auth-page {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            background: #DAFAE0;
            border-radius: 20px;
            padding: 44px 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 8px 40px rgba(5, 150, 105, 0.18),
                        0 2px 8px rgba(0,0,0,0.06);
            border: 1.5px solid #a7f3d0;
        }

        .auth-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
            justify-content: center;
        }

        .logo-image {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 12px;
        }

        .logo-name {
            font-size: 13px;
            font-weight: 800;
            color: #065f46;
            letter-spacing: 0.5px;
        }

        .auth-card h2 {
            font-size: 24px;
            font-weight: 800;
            color: #064e3b;
            text-align: center;
            margin-bottom: 6px;
            line-height: 1.3;
        }

        .auth-card h2 span {
            color: #059669;
        }

        .subtitle {
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 28px;
        }

        .alert-danger {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            margin-bottom: 16px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            margin-bottom: 16px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #065f46;
            margin-bottom: 6px;
        }

        .form-group input {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #6ee7b7;
            border-radius: 10px;
            background: #f0fdf4;
            font-family: 'Nunito', sans-serif;
            font-size: 14px;
            color: #064e3b;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group input:focus {
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15);
            background: #fff;
        }

        .form-group input.is-invalid {
            border-color: #f87171;
        }

        .invalid-feedback {
            color: #dc2626;
            font-size: 12px;
            margin-top: 4px;
        }

        .password-wrap {
            position: relative;
        }

        .password-wrap input {
            padding-right: 44px;
        }

        .toggle-pw {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #6b7280;
            padding: 0;
            display: flex;
            align-items: center;
        }

        .toggle-pw:hover { color: #059669; }

        .strength-bar {
            height: 4px;
            border-radius: 4px;
            margin-top: 6px;
            background: #e5e7eb;
            overflow: hidden;
        }

        .strength-bar-fill {
            height: 100%;
            width: 0;
            border-radius: 4px;
            transition: width 0.3s, background 0.3s;
        }

        .strength-hint {
            font-size: 11px;
            margin-top: 4px;
            font-weight: 700;
        }

        .pw-rules {
            font-size: 11px;
            color: #6b7280;
            margin-top: 6px;
            line-height: 1.8;
        }

        .pw-rules span {
            display: block;
        }

        .pw-rules .ok  { color: #16a34a; }
        .pw-rules .bad { color: #9ca3af; }

        .btn-primary {
            width: 100%;
            padding: 13px;
            background: #059669;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'Nunito', sans-serif;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            letter-spacing: 0.3px;
            margin-top: 8px;
        }

        .btn-primary:hover {
            background: #047857;
            transform: translateY(-1px);
        }

        .auth-divider {
            text-align: center;
            margin-top: 22px;
            font-size: 13px;
            color: #6b7280;
        }

        .auth-divider a {
            color: #059669;
            font-weight: 700;
            text-decoration: none;
        }

        .auth-divider a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<div class="auth-page">
    <div class="auth-card">

        <div class="auth-logo">
            <img src="{{ asset('images/logopharmacy.png') }}"
                 alt="Healthcare Pharmacy Logo"
                 class="logo-image">
            <span class="logo-name">HEALTHCARE PHARMACY</span>
        </div>

        <h2>Create an <span>Account</span></h2>

        <p class="subtitle">Join Healthcare Pharmacy today</p>

        @if ($errors->any())
            <div class="alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Full Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="Your full name"
                    value="{{ old('name') }}"
                    class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                    required
                >
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Email address"
                    value="{{ old('email') }}"
                    class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                    required
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-wrap">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Min 8 characters"
                        class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                        oninput="checkStrength(this.value)"
                        required
                    >
                    <button type="button" class="toggle-pw" onclick="togglePw('password', this)">
                        <svg id="eye-password" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
                <div class="strength-bar"><div class="strength-bar-fill" id="strength-fill"></div></div>
                <div class="strength-hint" id="strength-hint"></div>
                <div class="pw-rules" id="pw-rules">
                    <span id="r-len"  class="bad">✗ At least 8 characters</span>
                    <span id="r-upper" class="bad">✗ At least one uppercase letter</span>
                    <span id="r-num"  class="bad">✗ At least one number</span>
                    <span id="r-sym"  class="bad">✗ At least one special character (@$!%*#?&)</span>
                </div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <div class="password-wrap">
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Repeat password"
                        required
                    >
                    <button type="button" class="toggle-pw" onclick="togglePw('password_confirmation', this)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-primary">Create Account</button>
        </form>

        <div class="auth-divider">
            Already have an account?
            <a href="{{ route('login') }}">Log in</a>
        </div>

    </div>
</div>

<script>
function togglePw(id, btn) {
    const input = document.getElementById(id);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    btn.innerHTML = isText
        ? '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>'
        : '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.223-3.592M6.53 6.53A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.423 5.276M3 3l18 18"/></svg>';
}

function checkStrength(val) {
    const rules = {
        len:   val.length >= 8,
        upper: /[A-Z]/.test(val),
        num:   /[0-9]/.test(val),
        sym:   /[@$!%*#?&]/.test(val),
    };

    document.getElementById('r-len').className   = rules.len   ? 'ok' : 'bad';
    document.getElementById('r-len').textContent  = (rules.len   ? '✓' : '✗') + ' At least 8 characters';
    document.getElementById('r-upper').className = rules.upper ? 'ok' : 'bad';
    document.getElementById('r-upper').textContent= (rules.upper ? '✓' : '✗') + ' At least one uppercase letter';
    document.getElementById('r-num').className   = rules.num   ? 'ok' : 'bad';
    document.getElementById('r-num').textContent  = (rules.num   ? '✓' : '✗') + ' At least one number';
    document.getElementById('r-sym').className   = rules.sym   ? 'ok' : 'bad';
    document.getElementById('r-sym').textContent  = (rules.sym   ? '✓' : '✗') + ' At least one special character (@$!%*#?&)';

    const score = Object.values(rules).filter(Boolean).length;
    const fill  = document.getElementById('strength-fill');
    const hint  = document.getElementById('strength-hint');
    const colors = ['', '#ef4444', '#f59e0b', '#3b82f6', '#16a34a'];
    const labels = ['', 'Weak', 'Fair', 'Good', 'Strong'];

    fill.style.width      = (score * 25) + '%';
    fill.style.background = colors[score] || '';
    hint.textContent      = val.length ? labels[score] : '';
    hint.style.color      = colors[score] || '';
}
</script>

</body>
</html>
