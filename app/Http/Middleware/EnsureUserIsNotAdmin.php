<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsNotAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Block admin accounts from accessing user-only routes
        if (auth('web')->check() && auth('web')->user()->is_admin) {
            auth('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login');
        }

        return $next($request);
    }
}
