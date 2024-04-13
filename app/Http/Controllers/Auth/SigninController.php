<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AbstractController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SigninController extends AbstractController
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
            'email' => 'required|email',
            'password' => 'required|string',
            'remember' => 'boolean'
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->remember)) {
            $request->session()->regenerate();

            return $this->redirect(
                'home.index',
                'success',
                __('Welcome back, :name.', ['name' => auth()->user()->firstname])
            );
        }

        return $this->message(
            'auth.signin',
            'danger',
            __('The provided credentials are incorrect.')
        );
    }
}
