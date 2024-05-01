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
            'pages.account.edit',
            __('Edit your account'),
            __('Update your account information'),
            [
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

        if ($user->isAdmin()) {
            $status = $request->input('status') === 'active' ? true : false;
            $request->merge(['status' => $status]);

            $role = $request->input('role') === 'admin' ? true : false;
            $request->merge(['role' => $role]);
        }

        $user->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'status' => $request->status ?? true,
            'role' => $request->role ?? false,
            'preferences' => ['locale' => $request->locale]
        ]);

        $this->locale->setLanguage($request->locale);

        return redirect()->route('account.edit')->with('success', __('Your account has been updated.'));
    }
}
