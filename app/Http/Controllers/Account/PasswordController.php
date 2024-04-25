<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\AbstractController;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class PasswordController extends AbstractController
{
    public function edit(): View
    {
        return $this->view(
            'pages.account.password',
            __('Change password'),
            __('Update your account password')
        );
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $this->getUser();

        $request->validate([
            'current' => 'required|string|min:6|max:255',
            'password' => 'required|string|min:6|max:255',
        ]);

        // Validate the current password
        if (!auth()->validate([
            'email' => $user->email,
            'password' => $request->current
        ])) {
            return $this->redirect('account.password.edit', __('The current password you entered is incorrect.'), 'error');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return $this->redirect('account.edit', __('Your password has been updated.'));
    }
}
