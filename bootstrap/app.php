<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php', // Baris ini secara otomatis menerapkan middleware 'web'
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    ) 
    ->withMiddleware(function (Middleware $middleware) {
        // Daftarkan alias middleware untuk admin di sini
        $middleware->alias([
            'admin' => \App\Http\Middleware\AuthenticateAdmin::class,
        ]);
    })    
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
