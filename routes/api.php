<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Agents\CoderController as CoderAgentController;
use App\Http\Controllers\Api\Agents\ContentController as ContentAgentController;
use App\Http\Controllers\Api\Agents\PresetController;

use App\Http\Controllers\Api\Library\ContentController as ContentLibraryController;
use App\Http\Controllers\Api\Library\CoderController as CoderLibraryController;

Route::prefix('/agent')->group(function () {
    Route::post('/completion/{uuid?}', [ContentAgentController::class, 'handle']);

    Route::get('/content/presets', [PresetController::class, 'handle']);
    Route::get('/content/presets/count', function () {
        return response()->json(['count' => 10]);
    });
});

Route::prefix('/library')->group(function () {
    Route::get('/content', [ContentLibraryController::class, 'handle']);
    Route::get('/content/count', function () {
        return response()->json(['count' => 10]);
    });
});
