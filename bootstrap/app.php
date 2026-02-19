<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

$appUrl = getenv('APP_URL') ?: 'http://localhost';
if (!filter_var($appUrl, FILTER_VALIDATE_URL)) {
    $appUrl = 'http://localhost';
    putenv('APP_URL=' . $appUrl);
    $_ENV['APP_URL'] = $appUrl;
    $_SERVER['APP_URL'] = $appUrl;
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) { // <-- The ': void' is removed for compatibility, but the function is the same

        // Trust Railway/Proxy headers so HTTPS is detected correctly
        $middleware->trustProxies(
            at: '*',
            headers: SymfonyRequest::HEADER_X_FORWARDED_FOR
                | SymfonyRequest::HEADER_X_FORWARDED_HOST
                | SymfonyRequest::HEADER_X_FORWARDED_PORT
                | SymfonyRequest::HEADER_X_FORWARDED_PROTO
        );

        // Force HTTPS in production (redirect http -> https)
        $middleware->append(\App\Http\Middleware\ForceHttps::class);

        // THIS IS THE ONLY LINE YOU NEED TO ADD.
        // It registers 'check.admin' as a shortcut for your CheckAdmin class.
        $middleware->alias([
            'check.admin' => \App\Http\Middleware\CheckAdmin::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) { // <-- The ': void' is removed for compatibility
        //
    })->create();