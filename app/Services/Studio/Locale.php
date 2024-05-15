<?php

namespace App\Services\Studio;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

class Locale
{
    public const LANGUAGES = ['en', 'pt-BR'];
    public const COOKIE_NAME = 'locale';

    public function setLanguage(string $language): void
    {
        $this->setAppLocale($language);

        Cookie::queue(Cookie::make(
            self::COOKIE_NAME,  // Cookie name
            $language,          // Cookie value
            60 * 24 * 365,      // Expiration time (in minutes, 1 year)
            null,                // Path (use default config)
            null,               // Secure (use default config)
            null,               // Secure (only transmitted over HTTPS)
            false,              // HttpOnly (false, so it can be accessed via JavaScript)
            false,              // Raw (whether the cookie value should be sent as is)
            'Strict'            // SameSite (same-site cookie policy)
        ));
    }

    public function validateLocale(?string $locale): bool
    {
        return in_array($locale, self::LANGUAGES);
    }

    public function availableLanguages(): array
    {
        return [
            'en' => __('English'),
            'pt-BR' => __('Português (Brasil)')
        ];
    }

    public function setAppLocale(string $language): void
    {
        App::setLocale($language);
    }
}
