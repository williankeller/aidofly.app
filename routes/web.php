<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\SigninController;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\Auth\RecoverController;

use App\Http\Controllers\Library\LibraryController;

use App\Http\Controllers\Agents\WriterController;
use App\Http\Controllers\Preset\PresetsController;


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
});

/**
 * Authenticated routes only
 */
Route::middleware('auth')->group(function () {
    // Home route
    Route::get('/', function () {
        return view('pages.home.index');
    })->name('home.index');

    // Agent routes
    Route::name('agent.')->prefix('/agent')->group(function () {
        Route::controller(WriterController::class)->name('writer.')->group(function () {
            Route::get('/writer', 'create')->name('create');
            Route::get('/writer/{uuid}', 'edit')->where('uuid', '[a-z0-9-]+')->name('edit');
        });
    });

    // Preset routes
    Route::controller(PresetsController::class)->name('presets.')->group(function () {
        Route::get('/presets', 'index')->name('index');
        Route::get('/presets/mine', 'user')->name('user');
        Route::get('/presets/discover', 'discover')->name('discover');
        Route::get('/presets/create', 'create')->name('create');
        Route::get('/preset/{uuid}', 'show')->where('uuid', '[a-z0-9-]+')->name('show');
        Route::get('/preset/{uuid}/edit', 'edit')->where('uuid', '[a-z0-9-]+')->name('edit');
        Route::post('/presets', 'store')->name('store');
        Route::put('/preset/{uuid}', 'update')->where('uuid', '[a-z0-9-]+')->name('update');
        Route::delete('/preset/{uuid}', 'destroy')->where('uuid', '[a-z0-9-]+')->name('destroy');
    });

    // Library routes
    Route::name('library.')->group(function () {
        Route::controller(LibraryController::class)->name('agent.')->group(function () {
            Route::get('/library', 'index')->name('index');
            Route::get('/library/{uuid}', 'show')->where('uuid', '[a-z0-9-]+')->name('show');
        });
    });

    Route::post('/signout', [SigninController::class, 'signout'])->name('auth.signout');
});
