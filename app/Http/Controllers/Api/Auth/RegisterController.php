<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Api\AbstractController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends AbstractController
{
    public function register(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 0,
            'status' => 1,
        ]);

        // Authenticate user immediately after registration
        Auth::login($user);

        // Generate token
        $token = $user->createToken('auth')->plainTextToken;

        return $this->success([
            'xtoken' => $token,
        ], __('Registered'));
    }
}
