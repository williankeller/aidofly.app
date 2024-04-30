<?php

namespace App\Services\Studio;

use Illuminate\Support\Facades\App;

class Locale
{
    public const LANGUAGES = ['en', 'pt-BR'];

    public function setLanguage(string $language): void
    {
        cookie()->queue('locale', $language, 60 * 24 * 365 * 10, null, null, null, false);

        App::setLocale($language);
    }

    public function availableLanguages(): array
    {
        return [
            'en' => __('English'),
            'pt-BR' => __('Português (Brasil)')
        ];
    }
}
