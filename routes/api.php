<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Agents\Writer\CompletionController;
use App\Http\Controllers\Api\Agents\Writer\PresetsController;

use App\Http\Controllers\Api\Agents\Voiceover\VoicesController;
use App\Http\Controllers\Api\Agents\Voiceover\SpeechController;

use App\Http\Controllers\Api\Library\LibraryController;

Route::prefix('/agent')->group(function () {
    Route::post('/completion/{uuid?}', [CompletionController::class, 'handle']);

    Route::prefix('/voiceover')->group(function () {
        Route::get('/voices', [VoicesController::class, 'index']);
        Route::get('/voices/count', function () {
            return response()->json(['count' => 10]);
        });
        Route::post('/speech', [SpeechController::class, 'handle']);
    });
});

Route::get('/presets', [PresetsController::class, 'index']);
Route::get('/presets/mine', [PresetsController::class, 'user']);
Route::get('/presets/discover', [PresetsController::class, 'discover']);
Route::get('/presets/discover/count', function () {
    return response()->json(['count' => 10]);
});

Route::get('/presets/count', function () {
    return response()->json(['count' => 10]);
});

Route::get('/presets/mine/count', function () {
    return response()->json(['count' => 10]);
});

Route::controller(LibraryController::class)->group(function () {
    Route::get('/library', 'index');
    Route::get('/library/count', function () {
        return response()->json(['count' => 10]);
    });
});
