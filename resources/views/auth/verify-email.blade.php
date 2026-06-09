<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Healthcare Pharmacy</title>

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
            max-width: 460px;
            box-shadow: 0 8px 40px rgba(5, 150, 105, 0.18),
                        0 2px 8px rgba(0,0,0,0.06);
            border: 1.5px solid #a7f3d0;
            text-align: center;
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

        h2 {
            font-size: 24px;
            font-weight: 800;
            color: #064e3b;
            margin-bottom: 10px;
        }

        h2 span {
            color: #059669;
        }

        .subtitle {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .email-address {
            color: #065f46;
            font-weight: 800;
            word-break: break-word;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            margin-bottom: 16px;
            text-align: left;
        }

        .alert-danger {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            margin-bottom: 16px;
            text-align: left;
        }

        .form-group {
            margin-bottom: 16px;
            text-align: left;
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
            padding: 13px 14px;
            border: 1.5px solid #6ee7b7;
            border-radius: 10px;
            background: #f0fdf4;
            font-family: 'Nunito', sans-serif;
            font-size: 18px;
            font-weight: 800;
            color: #064e3b;
            letter-spacing: 0;
            outline: none;
            text-align: center;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group input:focus {
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15);
            background: #fff;
        }

        .invalid-feedback {
            color: #dc2626;
            font-size: 12px;
            margin-top: 4px;
            text-align: left;
        }

        .actions {
            display: grid;
            gap: 12px;
            margin-top: 22px;
        }

        .btn-primary,
        .btn-link {
            width: 100%;
            padding: 13px;
            border-radius: 10px;
            font-family: 'Nunito', sans-serif;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            letter-spacing: 0.3px;
        }

        .btn-primary {
            background: #059669;
            color: #fff;
            border: none;
        }

        .btn-primary:hover {
            background: #047857;
            transform: translateY(-1px);
        }

        .btn-link {
            background: transparent;
            color: #059669;
            border: 1.5px solid #6ee7b7;
            display: block;
            text-decoration: none;
        }

        .btn-link:hover {
            background: #ecfdf5;
        }
    </style>
</head>

<body>
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-logo">
            <img
                src="{{ asset('images/logopharmacy.png') }}"
                alt="Healthcare Pharmacy Logo"
                class="logo-image"
            >

            <span class="logo-name">HEALTHCARE PHARMACY</span>
        </div>

        <h2>Verify Your <span>Email</span></h2>

        @if (session('status') === 'verification-code-sent')
            <div class="alert-success">
                A new verification code has been sent to your email address.
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <p class="subtitle">
            We sent a 6-digit verification code to
            <span class="email-address">{{ $email }}</span>.
            Enter the code below to verify your account.
        </p>

        <div class="actions">
            <form action="{{ route('verification.verify') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="code">Verification Code</label>
                    <input
                        type="text"
                        id="code"
                        name="code"
                        inputmode="numeric"
                        maxlength="6"
                        minlength="6"
                        pattern="[0-9]{6}"
                        placeholder="000000"
                        value="{{ old('code') }}"
                        oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                        required
                        autofocus
                    >

                    @error('code')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn-primary">
                    Verify Code
                </button>
            </form>

            <form action="{{ route('verification.send') }}" method="POST">
                @csrf
                <button type="submit" class="btn-primary">
                    Resend Code
                </button>
            </form>

            @if (auth()->check())
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-link">
                        Log Out
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-link">
                    Back to Login
                </a>
            @endif
        </div>
    </div>
</div>
</body>
</html>
