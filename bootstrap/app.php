<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 🚀 BYPASS KETAT: Izinkan jalur sinkronisasi lokal lewat tanpa cegatan CSRF
        $middleware->validateCsrfTokens(except: [
            'checkout/callback', // Bantuan Midtrans jika ada
            'sync/receive'       // Jalur Push Data dari laptop lokalmu
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();