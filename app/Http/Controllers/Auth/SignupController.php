<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Services\Auth\AuthToken;
use App\Services\Studio\Locale;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;


class SignupController extends AbstractAuthController
{
    public function __construct(
        private Locale $locale,
        private AuthToken $token
    ) {
        parent::__construct($token);
    }

    public function index()
    {
        return $this->view(
            'pages.auth.signup',
            __('Begin your creative journey today'),
            __('Our AI-driven content creator is your new partner in creativity, ready to elevate your concepts with precision and flair.'),
            [
                'locales' => $this->locale->availableLanguages(),
            ]
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'firstname' => 'required|string|min:1|max:32',
            'lastname' => 'required|string|min:2|max:32',
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

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        try {
            auth()->login($user);

            $this->setAuthCookieToken();

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
}
