<?php

use App\Http\Middleware\CustomerMiddleware;
use App\Http\Middleware\DeliveryMiddleware;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\VendorMiddleware;
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
            'customer'   => CustomerMiddleware::class,
            'vendor'     => VendorMiddleware::class,
            'delivery'   => DeliveryMiddleware::class,
            'set-locale' => SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
