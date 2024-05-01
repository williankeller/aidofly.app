<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AbstractController;

class RecoverController extends AbstractController
{
    public function index()
    {
        return view('pages.auth.recover', [
            'metaTitle' => 'Recover password',
            'metaDescription' => 'Recover your account'
        ]);
    }

    public function send()
    {
        // Send email with token
    }

    public function reset($token)
    {
        return view('pages.auth.reset', [
            'metaTitle' => 'Reset password',
            'metaDescription' => 'Reset your account password'
        ]);
    }

    public function update($token)
    {
        // Update password
    }
}
