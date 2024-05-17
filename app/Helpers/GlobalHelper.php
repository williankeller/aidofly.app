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
    function nonce(int $size = 16): string
    {
        $nonce = Str::take(csrf_token(), $size);

        return $nonce ?? Str::random($size);
    }
}

if (!function_exists('filestorage')) {

    /**
     * Get the library file storage path
     *
     * @param string $filename
     * @param string|null $extension
     * @return string
     */
    function filestorage(string $filename, ?string $extension = '.mp3'): string
    {
        return route('library.filestorage.index', $filename) . $extension;
    }
}

if (!function_exists('numberFormat')) {

    /**
     * Format the number
     *
     * @param float|int $value
     * @param boolean $showFraction
     * @return string
     */
    function formatNumber(float|int $value, bool $showFraction = true): string
    {
        $amount = floatval($value);

        // Base options for decimal places
        $decimals = 2;

        if (!$showFraction) {
            $decimals = 0;
        }

        // Formatting the number
        $formatted = number_format($amount, $decimals, '.', '');

        // Check if number needs compact notation
        if (strlen($formatted) >= 7) {
            $thousands = intval($amount / 1000);
            $formatted = $thousands . 'k';
        }

        return $formatted;
    }
}
