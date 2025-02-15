<?php

namespace App\Http\Controllers\Auth;

use App\Services\Studio\Locale;
use App\Services\Auth\AuthToken;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;

class SigninController extends AbstractAuthController
{
    public function __construct(
        private Locale $locale,
        private AuthToken $token
    ) {
        parent::__construct($token);
    }

    public function index(): View
    {
        return $this->view(
            'pages.auth.signin',
            __('Transform your brainstorm ideas into reality'),
            __('Welcome back to the threshold of innovation. Log in now to begin shaping the future with every word you generate!'),
            [
                'locales' => $this->locale->availableLanguages(),
            ]
        );
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

    public function signout(): RedirectResponse
    {
        $this->removeAuthCookieToken();

        auth()->logout();

        return redirect()->route('auth.signin');
    }
}
