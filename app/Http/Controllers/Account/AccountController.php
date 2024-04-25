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
                'user' => $user
            ]
        );
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $this->getUser();

        $request->validate([
            'firstname' => 'required|string|min:1|max:32',
            'lastname' => 'required|string|min:2|max:32',
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
            'role' => $request->role ?? false
        ]);

        return redirect()->route('account.edit')->with('success', __('Your account has been updated.'));
    }
}
