<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSessionDriverAvailable
{
    public function handle(Request $request, Closure $next): Response
    {
        if (config('session.driver') !== 'database') {
            return $next($request);
        }

        if (config('database.default') !== 'sqlite') {
            return $next($request);
        }

        $sqlitePath = config('database.connections.sqlite.database');

        if ($sqlitePath && $sqlitePath !== ':memory:' && !file_exists($sqlitePath)) {
            config(['session.driver' => 'file']);
        }

        return $next($request);
    }
}
