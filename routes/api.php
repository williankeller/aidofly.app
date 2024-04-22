<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Agents\CompletionController;
use App\Http\Controllers\Api\Presets\PresetsController;

use App\Http\Controllers\Api\Library\LibraryController;

Route::prefix('/agent')->group(function () {
    Route::post('/completion/{uuid?}', [CompletionController::class, 'handle']);
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
