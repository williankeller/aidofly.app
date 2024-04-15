<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\SigninController;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\Auth\RecoverController;

use App\Http\Controllers\Library\LibraryController;

use App\Http\Controllers\Agents\CoderController;

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

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('pages.home.index');
    })->name('home.index');

    Route::name('agent.')->prefix('/agent')->group(function () {
        Route::controller(CoderController::class)->name('coder.')->group(function () {
            Route::get('/coder', 'index')->name('index');
        });
    });

    Route::name('library.')->group(function () {
        Route::controller(LibraryController::class)->name('agent.')->group(function () {
            Route::get('/library', 'index')->name('index');
            Route::get('/library/content', 'content')->name('content');
            Route::get('/library/coder', 'coder')->name('coder');
        });
    });
});
