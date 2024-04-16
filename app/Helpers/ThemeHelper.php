<?php

use Illuminate\Support\Str;

if (!function_exists('versioning')) {

    /**
     * Generate a version system pattern
     *
     * @param string|null $version
     * @param string|null $prefix
     */
    function versioning(?string $prefix = 'v'): string
    {
        $version = config('app.version');

        if (app()->environment() !== 'production') {
            return $prefix . Str::uuid();
        }
        return $prefix . $version;
    }
}

if (!function_exists('stylesheet')) {

    /**
     * Generate stylesheet tag
     * 
     * @param string $href
     * @param string|null $preload
     * @return string
     */
    function stylesheet(string $href, ?string $preload = null): string
    {
        $tags = [];
        $asset = asset($href) . versioning('?v=');

        if ($preload) {
            $tags[] = preload($asset, 'style');
        }

        $tags[] = '<link rel="stylesheet" type="text/css" href="' . $asset . '">';

        return implode(PHP_EOL, $tags);
    }
}

if (!function_exists('preconnect')) {

    /**
     * Generate preconnect tag link
     *
     * @param string $asset
     * @param boolean $crossorigin
     */
    function preconnect(string $asset, ?bool $crossorigin = false): string
    {
        $asset = asset($asset);

        if ($crossorigin) {
            return '<link rel="preconnect" href="' . $asset . '" crossorigin>';
        }
        return '<link rel="preconnect" href="' . $asset . '">';
    }
}

if (!function_exists('preload')) {

    /**
     * Generate preload tag
     *
     * @param string $asset
     * @param string|null $as
     * @param string|null $type
     */
    function preload(string $asset, ?string $as = 'style', ?string $type = null): string
    {
        $asset = asset($asset);

        if ($type) {
            return '<link rel="preload" as="' . $as . '" type="' . $type . '" href="' . $asset . '">';
        }
        return '<link rel="preload" as="' . $as . '" href="' . $asset . '">';
    }
}


if (!function_exists('javascript')) {

    /**
     * Return cost per hour base on time.
     *
     * @param string $asset
     * @param bool|null $defer
     */
    function javascript(string $asset, ?bool $defer = false): string
    {
        $asset = asset($asset) . versioning('?v=');
        $nonce = nonce();

        if ($defer) {
            return "<script nonce=\"{$nonce}\" src=\"{$asset}\" defer></script>";
        }
        return "<script nonce=\"{$nonce}\" src=\"{$asset}\"></script>";
    }
}
