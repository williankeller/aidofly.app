<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\AbstractController;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class EmailController extends AbstractController
{
    public function edit(): View
    {
        return $this->view(
            'pages.account.email',
            __('Change email'),
            __('Area to update your email address')
        );
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $this->getUser();

        if ($request->email === $user->email) {
            return $this->redirect(
                'account.email.edit',
                __('The email you entered is the same as your current email.'),
                'error'
            );
        }

        $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                'regex:/^[^@]+@[^@]+\.[a-z]{2,}$/'
            ],
            'password' => 'required|string|min:6|max:255'
        ]);

        // Check if the password is correct
        if (!auth()->validate([
            'email' => $user->email,
            'password' => $request->password
        ])) {
            return $this->redirect('account.email.edit', __('The password you entered is incorrect.'), 'error');
        }

        $user->update([
            'email' => $request->email,
        ]);

        return $this->redirect('account.edit', __('Your password has been updated.'));
    }
}
