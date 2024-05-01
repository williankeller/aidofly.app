<?php

namespace App\Http\Middleware\Api;

use Closure;
use App\Services\Studio\Locale;
use Illuminate\Http\Request;

class ApiLocale
{
    public function __construct(
        private Locale $locale
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param Closure $next
     * @param  string|null  ...$guards
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $locale = $request->header('Accept-Language');

        if ($this->locale->validateLocale($locale)) {
            $this->locale->setAppLocale($locale);
        }

        return $next($request);
    }
}
