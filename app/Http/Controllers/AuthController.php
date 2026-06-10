<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\EmailVerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ── Show pages ────────────────────────────────────────────────────────────

    public function showLogin()
    {
        if (Auth::guard('web')->check()) {
            if (! Auth::guard('web')->user()->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    public function showAdminLogin()
    {
        if (Auth::guard('admin')->check()) {
            if (! Auth::guard('admin')->user()->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }
            return redirect()->route('admin.dashboard');
        }

        return view('auth.admin-login');
    }

    public function showRegister()
    {
        if (Auth::guard('web')->check()) {
            if (! Auth::guard('web')->user()->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }
            return redirect()->route('home');
        }

        return view('auth.register');
    }

    // ── Login ─────────────────────────────────────────────────────────────────

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::guard('web')->user();

            // Block admin accounts from the user panel
            if ($user->is_admin) {
                Auth::guard('web')->logout();
                return back()->withErrors([
                    'email' => 'Admin accounts must use the admin login page.',
                ])->withInput($request->except('password'));
            }

            if (! $user->hasVerifiedEmail()) {
                $this->startEmailVerification($request, $user);
                Auth::guard('web')->logout();
                return redirect()->route('verification.notice')
                    ->with('status', 'verification-code-sent');
            }

            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->except('password'));
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::guard('admin')->user();

            // Block non-admin accounts from the admin panel
            if (! $user->is_admin) {
                Auth::guard('admin')->logout();
                return back()->withErrors([
                    'email' => 'You do not have admin access.',
                ])->withInput($request->except('password'));
            }

            if (! $user->hasVerifiedEmail()) {
                $this->startEmailVerification($request, $user);
                Auth::guard('admin')->logout();
                return redirect()->route('verification.notice')
                    ->with('status', 'verification-code-sent');
            }

            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->except('password'));
    }

    // ── Register ──────────────────────────────────────────────────────────────

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => ['required', 'confirmed', 'min:8', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'],
        ]);

        $code = (string) random_int(100000, 999999);

        $request->session()->put('pending_registration', [
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'code'       => Hash::make($code),
            'expires_at' => now()->addMinutes(10)->toDateTimeString(),
        ]);

        $temp = new User(['name' => $request->name, 'email' => $request->email]);
        $temp->notify(new EmailVerificationCode($code));

        return redirect()->route('verification.notice')
            ->with('status', 'verification-code-sent');
    }

    // ── Email verification ────────────────────────────────────────────────────

    public function showVerifyEmail(Request $request)
    {
        $pending = $request->session()->get('pending_registration');
        if ($pending) {
            return view('auth.verify-email', ['email' => $pending['email']]);
        }

        $user = $this->verificationUser($request);

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        return view('auth.verify-email', ['email' => $user->email]);
    }

    public function verifyEmailCode(Request $request)
    {
        $request->validate(['code' => 'required|digits:6']);

        // Pending registration (not yet in DB)
        $pending = $request->session()->get('pending_registration');
        if ($pending) {
            if (
                now()->gt($pending['expires_at'])
                || ! Hash::check($request->code, $pending['code'])
            ) {
                return back()->withErrors(['code' => 'The verification code is invalid or expired.']);
            }

            $user = User::create([
                'name'              => $pending['name'],
                'email'             => $pending['email'],
                'password'          => $pending['password'],
                'email_verified_at' => now(),
            ]);

            $request->session()->forget('pending_registration');

            Auth::guard('web')->login($user);
            $request->session()->regenerate();
            return redirect()->route('home');
        }

        // Existing unverified user
        $user = $this->verificationUser($request);

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        if (
            ! $user->email_verification_code
            || ! $user->email_verification_code_expires_at
            || $user->email_verification_code_expires_at->isPast()
            || ! Hash::check($request->code, $user->email_verification_code)
        ) {
            return back()->withErrors([
                'code' => 'The verification code is invalid or expired.',
            ]);
        }

        $user->forceFill([
            'email_verified_at'                  => now(),
            'email_verification_code'            => null,
            'email_verification_code_expires_at' => null,
        ])->save();

        $request->session()->forget('pending_verification_user_id');

        Auth::guard('web')->login($user);
        $request->session()->regenerate();
        return redirect()->route('home');
    }

    public function resendEmailCode(Request $request)
    {
        $pending = $request->session()->get('pending_registration');
        if ($pending) {
            $code = (string) random_int(100000, 999999);
            $pending['code']       = Hash::make($code);
            $pending['expires_at'] = now()->addMinutes(10)->toDateTimeString();
            $request->session()->put('pending_registration', $pending);

            $temp = new User(['name' => $pending['name'], 'email' => $pending['email']]);
            $temp->notify(new EmailVerificationCode($code));

            return back()->with('status', 'verification-code-sent');
        }

        $user = $this->verificationUser($request);

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        $this->startEmailVerification($request, $user);

        return back()->with('status', 'verification-code-sent');
    }

    // ── Logout ────────────────────────────────────────────────────────────────

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function adminLogout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function startEmailVerification(Request $request, User $user): void
    {
        $code = (string) random_int(100000, 999999);

        $user->forceFill([
            'email_verification_code'            => Hash::make($code),
            'email_verification_code_expires_at' => now()->addMinutes(10),
        ])->save();

        $request->session()->put('pending_verification_user_id', $user->id);
        $user->notify(new EmailVerificationCode($code));
    }

    private function verificationUser(Request $request): ?User
    {
        if (Auth::guard('web')->check()) {
            return Auth::guard('web')->user();
        }

        $userId = $request->session()->get('pending_verification_user_id');

        return $userId ? User::find($userId) : null;
    }
}
