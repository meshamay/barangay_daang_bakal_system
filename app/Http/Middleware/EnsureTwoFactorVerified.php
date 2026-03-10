<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class EnsureTwoFactorVerified
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !Session::get('2fa_passed')) {
            return redirect()->route('2fa.form');
        }
        return $next($request);
    }
}
