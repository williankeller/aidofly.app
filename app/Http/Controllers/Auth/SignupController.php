<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AbstractController;

class SignupController extends AbstractController
{
    public function index()
    {
        return view('pages.auth.signup', [
            'metaTitle' => 'Sign Up',
            'metaDescription' => 'Sign up to access your account'
        ]);
    }
}