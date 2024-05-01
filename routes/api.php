<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Home\SearchController;


use App\Http\Controllers\Api\Library\LibraryController;

use App\Http\Controllers\Api\Agents\Writer\CompletionController;
use App\Http\Controllers\Api\Agents\Writer\PresetsController;

use App\Http\Controllers\Api\Agents\Voiceover\VoicesController;
use App\Http\Controllers\Api\Agents\Voiceover\SpeechController;

Route::controller(SearchController::class)->group(function () {
    Route::get('/search', 'index');
});

Route::controller(LibraryController::class)->group(function () {
    Route::get('/library', 'index');
});

Route::prefix('/agent')->group(function () {
    Route::post('/completion/{uuid?}', [CompletionController::class, 'handle']);

    Route::prefix('/voiceover')->group(function () {
        Route::get('/voices', [VoicesController::class, 'index']);
        Route::post('/speech', [SpeechController::class, 'handle']);
    });
});

Route::controller(PresetsController::class)->group(function () {
    Route::get('/presets', 'index');
    Route::get('/presets/mine', 'user');
    Route::get('/presets/discover', 'discover');
});
