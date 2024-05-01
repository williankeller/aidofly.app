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

        Cookie::queue(cookie(self::COOKIE_NAME, $language, 60 * 24 * 365 * 10));
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
