<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Agents\CompletionController;
use App\Http\Controllers\Api\Agents\PresetsController;

use App\Http\Controllers\Api\Library\LibraryController;

Route::prefix('/agent')->group(function () {
    Route::post('/completion/{uuid?}', [CompletionController::class, 'handle']);

    Route::get('/content/presets', [PresetsController::class, 'handle']);
    Route::get('/content/presets/count', function () {
        return response()->json(['count' => 10]);
    });
});

Route::controller(LibraryController::class)->group(function () {
    Route::get('/library', 'index');
    Route::get('/library/count', function () {
        return response()->json(['count' => 10]);
    });
});
