<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\EmailVerificationCode;
use App\Notifications\PasswordResetNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

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

        // Clear any stale pending registration from a previous or deleted account
        $request->session()->forget('pending_registration');
        $request->session()->forget('pending_verification_user_id');

        $code = (string) random_int(100000, 999999);

        $request->session()->put('pending_registration', [
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'code'       => Hash::make($code),
            'expires_at' => now()->addMinutes(10)->toDateTimeString(),
        ]);

        // Use an anonymous notifiable — avoids serializing an unsaved model through the queue
        try {
            Notification::route('mail', $request->email)
                ->notify((new EmailVerificationCode($code))->onQueue(null)->afterCommit(false));
        } catch (\Throwable $e) {
            Log::error('Verification email failed: ' . $e->getMessage());
        }

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

            return redirect()->route('login')
                ->with('status', 'account-verified');
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

        return redirect()->route('login')
            ->with('status', 'account-verified');
    }

    public function resendEmailCode(Request $request)
    {
        $pending = $request->session()->get('pending_registration');
        if ($pending) {
            $code = (string) random_int(100000, 999999);
            $pending['code']       = Hash::make($code);
            $pending['expires_at'] = now()->addMinutes(10)->toDateTimeString();
            $request->session()->put('pending_registration', $pending);

            try {
                Notification::route('mail', $pending['email'])
                    ->notify((new EmailVerificationCode($code))->onQueue(null)->afterCommit(false));
            } catch (\Throwable $e) {
                Log::error('Verification email failed: ' . $e->getMessage());
            }

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

    // ── Forgot / Reset Password ──────────────────────────────────────────────

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors(['email' => 'No account found with that email address.']);
        }

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        $token = Str::random(64);

        DB::table('password_reset_tokens')->insert([
            'email'      => $request->email,
            'token'      => Hash::make($token),
            'created_at' => now(),
        ]);

        $resetUrl = url('/password/reset/' . $token . '?email=' . urlencode($request->email));

        try {
            $user->notify(new PasswordResetNotification($resetUrl));
        } catch (\Throwable $e) {
            Log::error('Password reset email failed: ' . $e->getMessage());
        }

        return back()->with('status', 'reset-link-sent');
    }

    public function showResetPassword(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', old('email', '')),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'token'    => 'required|string',
            'password' => ['required', 'confirmed', 'min:8', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'],
        ]);

        $redirectBack = redirect(
            route('password.reset', ['token' => $request->token]) . '?email=' . urlencode($request->email)
        )->withInput($request->only('email'));

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (! $record || ! Hash::check($request->token, $record->token)) {
            return $redirectBack->withErrors(['email' => 'This password reset link is invalid.']);
        }

        if (\Carbon\Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return redirect()->route('password.request')
                ->withErrors(['email' => 'This password reset link has expired. Please request a new one.']);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return $redirectBack->withErrors(['email' => 'No account found with that email address.']);
        }

        $user->forceFill(['password' => Hash::make($request->password)])->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'password-reset');
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
