<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\EnsureMfaVerified;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Percayai semua proxy (diperlukan untuk hosting seperti Railway/Render agar HTTPS terdeteksi benar)
        $middleware->trustProxies(at: '*');
        // Daftarkan alias middleware
        $middleware->alias([
            'role' => CheckRole::class,
            'mfa' => EnsureMfaVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
