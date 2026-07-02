<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
     //   namespace: 'App\\Http\\Controllers',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth_gates' => \App\Http\Middleware\AuthGates::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'queue/api/join',
            'queue/api/save-subscription',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
