<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\SigninController;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\Auth\RecoverController;

Route::name('auth.')->group(function () {
    Route::get('/signin', [SigninController::class, 'index'])->name('signin');
    Route::get('/signup', [SignupController::class, 'index'])->name('signup');
    Route::get('/recover', [RecoverController::class, 'index'])->name('recover');
});
