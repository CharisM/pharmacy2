<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\EmailVerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            if (! auth()->user()->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            return auth()->user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('home');
        }

        return view('auth.login');
    }

    public function showAdminLogin()
    {
        if (Auth::check()) {
            if (! auth()->user()->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            if (auth()->user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
        }

        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            if (auth()->user()->isAdmin()) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Please use the admin login page.',
                ])->withInput($request->except('password'));
            }

            if (! auth()->user()->hasVerifiedEmail()) {
                $user = auth()->user();
                $this->startEmailVerification($request, $user);
                Auth::logout();

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
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (! auth()->user()->isAdmin()) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'You do not have admin access.',
                ])->withInput($request->except('password'));
            }

            if (! auth()->user()->hasVerifiedEmail()) {
                $user = auth()->user();
                $this->startEmailVerification($request, $user);
                Auth::logout();

                return redirect()->route('verification.notice')
                    ->with('status', 'verification-code-sent');
            }

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->except('password'));
    }

    public function showRegister()
    {
        if (Auth::check()) {
            if (! auth()->user()->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            return auth()->user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('home');
        }

        return view('auth.register');
    }

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

        // Send code via a temporary unsaved user object
        $temp = new User(['name' => $request->name, 'email' => $request->email]);
        $temp->notify(new EmailVerificationCode($code));

        return redirect()->route('verification.notice')
            ->with('status', 'verification-code-sent');
    }

    public function showVerifyEmail(Request $request)
    {
        // Pending registration (not yet in DB)
        $pending = $request->session()->get('pending_registration');
        if ($pending) {
            return view('auth.verify-email', ['email' => $pending['email']]);
        }

        $user = $this->verificationUser($request);

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->hasVerifiedEmail()) {
            return Auth::check()
                ? redirect()->route('home')
                : redirect()->route('login');
        }

        return view('auth.verify-email', [
            'email' => $user->email,
        ]);
    }

    public function verifyEmailCode(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        // Handle pending registration (user not yet in DB)
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
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('home')
                ->with('success', 'Your email address has been verified.');
        }

        // Handle existing user (e.g. admin or re-verification)
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

        if (Auth::check()) {
            return redirect()->intended(route('home'))
                ->with('success', 'Your email address has been verified.');
        }

        return redirect()->route('login')
            ->with('status', 'email-verified');
    }

    public function resendEmailCode(Request $request)
    {
        // Handle pending registration
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
            return Auth::check()
                ? redirect()->route('home')
                : redirect()->route('login');
        }

        $this->startEmailVerification($request, $user);

        return back()->with('status', 'verification-code-sent');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectAfterLogin(Request $request, string $routeName)
    {
        $request->session()->regenerate();

        if (! auth()->user()->hasVerifiedEmail()) {
            $user = auth()->user();
            $this->startEmailVerification($request, $user);
            Auth::logout();

            return redirect()->route('verification.notice')
                ->with('status', 'verification-code-sent');
        }

        return redirect()->intended(route($routeName));
    }

    private function startEmailVerification(Request $request, User $user): void
    {
        $code = (string) random_int(100000, 999999);

        $user->forceFill([
            'email_verification_code' => Hash::make($code),
            'email_verification_code_expires_at' => now()->addMinutes(10),
        ])->save();

        $request->session()->put('pending_verification_user_id', $user->id);
        $user->notify(new EmailVerificationCode($code));
    }

    private function verificationUser(Request $request): ?User
    {
        if (Auth::check()) {
            return $request->user();
        }

        $userId = $request->session()->get('pending_verification_user_id');

        if (! $userId) {
            return null;
        }

        return User::find($userId);
    }
}
