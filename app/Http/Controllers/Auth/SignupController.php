<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\AbstractController;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class SignupController extends AbstractController
{
    public function index()
    {
        return view('pages.auth.signup', [
            'metaTitle' => 'Begin your creative journey today',
            'metaDescription' => 'Our AI-driven content creator is your new partner in creativity, ready to elevate your concepts with precision and flair.'
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // Validate the request
        $data = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6'
        ]);

        // Create the user
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        // Sign in the user
        auth()->login($user);

        // Redirect the user
        return $this->redirect(
            'home.index',
            'success',
            __('Welcome, :name.', ['name' => $user->firstname])
        );
    }
}
