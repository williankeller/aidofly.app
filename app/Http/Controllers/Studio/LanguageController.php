<?php

namespace App\Http\Controllers\Studio;

use App\Http\Controllers\AbstractController;

/**
 * Class LanguageController
 * Handles language switching
 * @package App\Http\Controllers\Studio
 */
class LanguageController extends AbstractController
{
    private const LANGUAGES = ['en', 'pt-BR'];

    /**
     * Switches the language
     * @param string $language
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch(string $language)
    {
        if (!in_array($language, self::LANGUAGES)) {
            abort(400);
        }
        cookie()->queue('locale', $language, 60 * 24 * 365 * 10, null, null, null, false);

        return redirect()->back();
    }
}
