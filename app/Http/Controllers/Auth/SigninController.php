<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AbstractController;
use App\Services\Auth\Token;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SigninController extends AbstractController
{
    private Token $token;

    public function __construct(
        Token $token
    ) {
        $this->token = $token;
    }

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
            'email' => 'required|email',
            'password' => 'required|string',
            'remember' => 'boolean'
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->remember)) {
            $request->session()->regenerate();

            $this->setAuthToken($request);

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

    private function setAuthToken(Request $request): void
    {
        $expires = $request->remember ? 604800 : 86400;
        $jwtToken = $this->token->generate(auth()->user()->uuid, $expires);

        cookie()->queue('token', $jwtToken, $expires);
    }
}
