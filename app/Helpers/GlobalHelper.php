<?php

use Illuminate\Support\Str;

if (!function_exists('locale')) {

    /**
     * Get application locale
     *
     * @param boolean|null $normalize
     * @return string
     */
    function locale(?bool $normalize = false): string
    {
        if ($normalize) {
            return str_replace('_', '-', app()->getLocale());
        }

        return app()->getLocale();
    }
}


if (!function_exists('isRoute')) {

    /**
     * Check provided route is the current route.
     *
     * @param mixed $route
     * @param boolean $multiple
     * @return boolean
     */
    function isRoute($route, $multiple = false): bool
    {
        if ($multiple === true) {
            $route = explode(',', $route);
        }
        return request()->routeIs($route);
    }
}


if (!function_exists('nonce')) {

    /**
     * Generate CSP nonce
     * @return string
     */
    function nonce(): string
    {
        return csrf_token() ?? Str::random(12);
    }
}