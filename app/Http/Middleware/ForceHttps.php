<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $appUrl = config('app.url');
        $appScheme = parse_url($appUrl, PHP_URL_SCHEME) ?: 'http';
        $forwardedProto = $request->header('x-forwarded-proto');

        if ($appScheme === 'https' && (!$request->isSecure() && $forwardedProto !== 'https')) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
