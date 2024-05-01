<?php

namespace App\Http\Middleware\Studio;

use Closure;
use App\Services\Studio\Locale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class AppLocale
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
        $locale = $request->cookie(Locale::COOKIE_NAME) ?? $this->getUserLocale($request);

        if ($this->locale->validateLocale($locale)) {
            $this->locale->setLanguage($locale);
        }

        return $next($request);
    }

    private function getUserLocale(Request $request): ?string
    {
        return auth()->user()?->preferences?->locale;
    }
}
