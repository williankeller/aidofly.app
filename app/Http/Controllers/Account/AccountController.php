<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\AbstractController;
use App\Services\Studio\Locale;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class AccountController extends AbstractController
{
    public function __construct(
        private Locale $locale,
    ) {
    }

    public function edit(): View
    {
        return $this->view(
            view: 'pages.account.edit',
            title: __('Edit your account'),
            description: __('Update your account information'),
            data: [
                'locales' => $this->locale->availableLanguages(),
            ]
        );
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $this->getUser();

        $request->validate([
            'firstname' => 'required|string|min:1|max:32',
            'lastname' => 'required|string|min:2|max:32',
            'locale' => 'required|string|in:' . implode(',', Locale::LANGUAGES)
        ]);

        $user->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'preferences' => ['locale' => $request->locale]
        ]);

        $this->locale->setLanguage($request->locale);

        return $this->redirect(
            route: 'account.edit',
            message: __('Your account has been updated.')
        );
    }
}
