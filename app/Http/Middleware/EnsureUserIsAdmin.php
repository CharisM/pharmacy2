<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (! auth('admin')->check() || ! auth('admin')->user()->is_admin) {
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
