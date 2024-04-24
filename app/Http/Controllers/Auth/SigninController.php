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
        $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'regex:/^[^@]+@[^@]+\.[a-z]{2,}$/'
            ],
            'password' => 'required|string|min:6'
        ]);

        if (Auth::attempt($request->only('email', 'password'), true)) {
            $request->session()->regenerate();

            $this->setAuthCookieToken();

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
