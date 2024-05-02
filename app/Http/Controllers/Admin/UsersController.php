<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Services\Studio\Locale;
use App\Http\Controllers\AbstractController;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class UsersController extends AbstractController
{
    public function __construct(
        private Locale $locale
    ) {
    }

    public function index(): View
    {
        return $this->view(
            view: 'admin.users.index',
            title: 'Users',
            data: [
                'users' => User::orderBy('created_at', 'desc')->paginate(10)
            ]
        );
    }

    public function show(string $uuid): View
    {
        $user = User::with(['library', 'presets'])->where('uuid', $uuid)->firstOrFail();

        return $this->view(
            view: 'admin.users.show',
            title: __('Viewing user: :firstname', ['firstname' => $user->firstname]),
            data: [
                'user' => $user
            ]
        );
    }

    public function edit(string $uuid): View
    {
        $user = User::where('uuid', $uuid)->firstOrFail();

        return $this->view(
            view: 'admin.users.edit',
            title: __('Editing user: :firstname', ['firstname' => $user->firstname]),
            data: [
                'user' => $user,
                'locales' => $this->locale->availableLanguages()
            ]
        );
    }

    public function update(Request $request, string $uuid): RedirectResponse
    {
        $user = User::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'firstname' => 'required|string|min:1|max:32',
            'lastname' => 'required|string|min:2|max:32',
            'email' => 'required|email',
        ]);

        $status = $request->input('status') === 'active' ? true : false;
        $request->merge(['status' => $status]);

        $role = $request->input('role') === 'admin' ? true : false;
        $request->merge(['role' => $role]);

        $user->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'status' => $request->status ?? true,
            'role' => $request->role ?? false,
            'preferences' => ['locale' => $request->locale]
        ]);

        return $this->redirect('back', __('User updated successfully'));
    }
}
