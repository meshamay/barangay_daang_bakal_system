<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) { // <-- The ': void' is removed for compatibility, but the function is the same

        // THIS IS THE ONLY LINE YOU NEED TO ADD.
        // It registers 'check.admin' as a shortcut for your CheckAdmin class.
        $middleware->alias([
            'check.admin' => \App\Http\Middleware\CheckAdmin::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) { // <-- The ': void' is removed for compatibility
        //
    })->create();