<?php

use App\Http\Middleware\Api\Authenticate;
use App\Http\Middleware\Studio\Locale;
use App\Services\Auth\AuthToken;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Redirect unauthenticated users to the signin page
        $middleware->redirectGuestsTo('/signin');

        // Encrypt all cookies except the auth token
        $middleware->encryptCookies(except: [
            AuthToken::COOKIE_NAME,
            'locale'
        ]);

        // API middleware for authenticating requests
        $middleware->api(append: [
            Authenticate::class
        ]);

        // Web middleware for handling the locale
        $middleware->web(append: [
            Locale::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
