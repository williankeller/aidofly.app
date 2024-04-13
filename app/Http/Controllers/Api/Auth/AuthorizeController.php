<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Api\AbstractController;

class AuthorizeController extends AbstractController
{
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
            $token = $user->createToken('authToken')->plainTextToken;

            return $this->success([
                'user' => $user,
                'token' => $token
            ], 'Authorized');
        }

        throw ValidationException::withMessages([
            'response' => __('The provided credentials are incorrect.')
        ]);
    }
}
