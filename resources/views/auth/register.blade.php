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
            max-width: 440px;
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

        /* IMAGE LOGO */
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

        <!-- LOGO -->
        <div class="auth-logo">

            <!-- REPLACE logo.png WITH YOUR OWN IMAGE -->
            <img src="{{ asset('images/logopharmacy.png') }}"
                 alt="Healthcare Pharmacy Logo"
                 class="logo-image">

            <span class="logo-name">HEALTHCARE PHARMACY</span>
        </div>

        <h2>Create an <span>Account</span></h2>

        <p class="subtitle">
            Join Healthcare Pharmacy today
        </p>

        @if ($errors->any())
            <div class="alert alert-danger">
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
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
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
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Min 6 characters"
                    class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                    required
                >

                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">
                    Confirm Password
                </label>

                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Repeat password"
                    required
                >
            </div>

            <button
                type="submit"
                class="btn-primary"
                style="margin-top:8px;"
            >
                Create Account
            </button>
        </form>

        <div class="auth-divider">
            Already have an account?
            <a href="{{ route('login') }}">Log in</a>
        </div>

    </div>
</div>

</body>
</html>