<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // SMF cookies are not Laravel-encrypted; exclude them so Laravel won't null them out.
        $smfCookie = env('SMF_COOKIE_NAME', 'SMFCookie477');
        $middleware->encryptCookies(except: [
            $smfCookie,
            $smfCookie . '_tfa',
        ]);

        $middleware->append(\App\Http\Middleware\SmfAuthMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
