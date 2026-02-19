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
        $forwardedProto = $request->header('x-forwarded-proto');
        $host = $request->getHost();
        $isLocal = in_array($host, ['localhost', '127.0.0.1'], true);

        if (!$isLocal && !$request->isSecure() && $forwardedProto !== 'https') {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
