<?php

namespace App\Http\Middleware\Studio;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class AppLocale
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
        // If locale from cookie is available, use it
        $locale = $request->cookie('locale');

        // Otherwise, try locale from user preferences (database)
        if (!$locale) {
            $preferences = auth()->user()->preferences;

            if ($preferences) {
                $locale = optional($preferences->locale);
            }
        }

        if ($locale) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
