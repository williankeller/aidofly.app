<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Api\AbstractController;
use App\Services\Auth\Token;

class AuthorizeController extends AbstractController
{

    private Token $token;

    public function __construct(
        Token $token
    ) {
        $this->token = $token;
    }

    public function authorize(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'remember' => 'boolean'
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // if remember me is checked, token will expire in 1 week
            $expires = $request->remember ? 604800 : 86400;

            $jwtToken = $this->token->generate($user->uuid, $expires);

            return $this->success([
                'token' => $jwtToken,
                'type' => 'Bearer',
                'expires_in' => 3600
            ], 'Authorized');
        }

        throw ValidationException::withMessages([
            'response' => __('The provided credentials are incorrect.')
        ]);
    }
}
