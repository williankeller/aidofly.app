<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\SigninController;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\Auth\RecoverController;

Route::name('auth.')->group(function () {
    Route::controller(SigninController::class)->group(function () {
        Route::get('/signin', 'index')->name('signin');
        Route::post('/authenticate', 'authorize')->name('signin.authorize');
    });
    // Signup routes
    Route::controller(SignupController::class)->name('signup.')->group(function () {
        Route::get('/signup', 'index')->name('index');
        Route::post('/signup', 'store')->name('store');
    });
    Route::get('/recover', [RecoverController::class, 'index'])->name('recover');

    // Redirect /login to /signin
    Route::redirect('/login', '/signin');
});

Route::middleware('auth')->name('auth.')->group(function () {
    Route::post('/signout', [SigninController::class, 'signout'])->name('signout');
});

Route::get('/', function () {
    return view('pages.home.index');
})->name('home.index');

Route::middleware('auth')->group(function () {
   
});
