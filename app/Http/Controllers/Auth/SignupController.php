<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Services\Auth\Token;
use App\Http\Controllers\AbstractController;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class SignupController extends AbstractController
{
    private Token $token;

    public function __construct(
        Token $token
    ) {
        $this->token = $token;
    }

    public function index()
    {
        return view('pages.auth.signup', [
            'metaTitle' => 'Begin your creative journey today',
            'metaDescription' => 'Our AI-driven content creator is your new partner in creativity, ready to elevate your concepts with precision and flair.'
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6'
        ]);


        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        try {
            auth()->login($user);

            $this->setAuthToken();

            return $this->redirect(
                'home.index',
                __('Welcome, :name.', ['name' => $user->firstname])
            );
        } catch (\Exception $e) {
            return $this->redirect(
                'auth.signup.index',
                __('An error occurred while signing up. Please try again.'),
                'danger',
            );
        }
    }

    private function setAuthToken($expires = 86400): void
    {
        $jwtToken = $this->token->generate(auth()->user()->uuid, $expires);

        cookie()->queue('token', $jwtToken, $expires);
    }
}
