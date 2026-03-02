<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Session expired'], 401);
            }
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }

        $user = Auth::user();
        
        $allowedTypes = ['admin', 'super admin', 'super_admin', 'superadmin'];

        $userType = strtolower($user->user_type ?? '');
        $userRole = strtolower($user->role ?? '');
        $userStatus = strtolower($user->status ?? '');

        $isSuperAdmin = in_array($userType, ['super admin', 'super_admin', 'superadmin'], true)
            || in_array($userRole, ['super admin', 'super_admin', 'superadmin'], true);

        if (!$isSuperAdmin && !in_array($userStatus, ['approved', 'active'], true)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Your account is deactivated. Please contact the administrator.');
        }

        if (in_array($userType, $allowedTypes) || in_array($userRole, $allowedTypes)) {
            return $next($request);
        }

        return redirect()->route('home')->with('error', 'Unauthorized access.');
    }
}