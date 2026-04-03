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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn () => route('loginForm'));
        $middleware->redirectUsersTo(function () {
            $user = auth()->user();
            if ($user?->role === 'vendor') return route('vendor.dashboard');
            if ($user?->role === 'delivery') return route('delivery.dashboard');
            return route('home');
        });
        $middleware->alias([
            'customer' => \App\Http\Middleware\CustomerMiddleware::class,
            'vendor'   => \App\Http\Middleware\VendorMiddleware::class,
            'delivery' => \App\Http\Middleware\DeliveryMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
