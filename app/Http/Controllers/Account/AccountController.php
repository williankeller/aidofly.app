<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\AbstractController;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class AccountController extends AbstractController
{
    public function edit(): View
    {
        $user = $this->getUser();

        return $this->view(
            'pages.account.edit',
            __('Edit your account'),
            __('Update your account information'),
            [
                'locales' => $this->getLanguages(),
                'user' => $user,
                'selectedLocale' => $user->preferences->locale
            ]
        );
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $this->getUser();

        $request->validate([
            'firstname' => 'required|string|min:1|max:32',
            'lastname' => 'required|string|min:2|max:32',
            'locale' => 'required|string|in:' . implode(',', array_keys($this->getLanguages()))
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

        cookie()->queue('locale', $request->locale, 60 * 24 * 365 * 10, null, null, null, false);
        app()->setLocale($request->locale);

        return redirect()->route('account.edit')->with('success', __('Your account has been updated.'));
    }

    private function getLanguages(): array
    {
        return [
            'en' => __('English'),
            'pt-BR' => __('Português (Brasil)')
        ];
    }
}
