<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SigninController extends AbstractAuthController
{
    public function index()
    {
        return view('pages.auth.signin', [
            'metaTitle' => 'Transform your brainstorm ideas into reality',
            'metaDescription' => 'Welcome back to the threshold of innovation. Log in now to begin shaping the future with every word you generate!'
        ]);
    }

    public function authorize(Request $request): RedirectResponse
    {
        // Convert remember checkbox input to boolean and merge it back into the request
        $remember = filter_var($request->input('remember', false), FILTER_VALIDATE_BOOLEAN);
        $request->merge(['remember' => $remember]);

        $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'regex:/^[^@]+@[^@]+\.[a-z]{2,}$/'
            ],
            'password' => 'required|string|min:6',
            'remember' => 'boolean'
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->remember)) {
            $request->session()->regenerate();

            $expires = (int) $request->remember ? 1209600 : 86400;

            $this->setAuthCookieToken($expires);

            return $this->redirect(
                'home.index',
                __('Welcome back, :name.', ['name' => auth()->user()->firstname])
            );
        }

        return $this->redirect(
            'auth.signin',
            __('The provided credentials are incorrect.'),
            'danger',
        );
    }
}
