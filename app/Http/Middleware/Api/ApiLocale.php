<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ApiLocale
{
    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param Closure $next
     * @param  string|null  ...$guards
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $locale = $request->header('Accept-Language') ?? config('app.locale');

        App::setLocale($locale);

        return $next($request);
    }
}
