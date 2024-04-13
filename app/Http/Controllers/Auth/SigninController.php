<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AbstractController;

class SigninController extends AbstractController
{
    public function index()
    {
        return view('pages.auth.signin', [
            'metaTitle' => 'Sign In',
            'metaDescription' => 'Sign in to access your account'
        ]);
    }
}