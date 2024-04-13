<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AbstractController;

class RecoverController extends AbstractController
{
    public function index()
    {
        return view('pages.auth.recover', [
            'metaTitle' => 'Recover Account',
            'metaDescription' => 'Recover your account'
        ]);
    }
}
