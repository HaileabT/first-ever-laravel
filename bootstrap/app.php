<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )->withProviders([App\Providers\AppServiceProvider::class, App\Providers\AuthServiceProvider::class])->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth.identify' => \App\Http\Middleware\AuthMiddleware::class,
            'auth.role' => \App\Http\Middleware\AuthorizeMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
