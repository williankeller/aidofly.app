<?php

namespace App\Http\Controllers\Studio;

use App\Http\Controllers\AbstractController;
use App\Services\Studio\Locale;
use Illuminate\Http\Request;

/**
 * Class LanguageController
 * Handles language switching
 * @package App\Http\Controllers\Studio
 */
class LocaleController extends AbstractController
{
    public function __construct(
        private Locale $locale
    ) {
    }

    /**
     * Switches the language
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch(Request $request): \Illuminate\Http\RedirectResponse
    {
        $language = $request->input('locale');

        if (!in_array($language, Locale::LANGUAGES)) {
            abort(400);
        }
        $this->locale->setLanguage($language);

        return redirect()->back();
    }
}
