<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Home\SearchController;

use App\Http\Controllers\Api\User\UsageController;

use App\Http\Controllers\Api\Library\LibraryController;

use App\Http\Controllers\Api\Agents\Writer\CompletionController;
use App\Http\Controllers\Api\Agents\Writer\PresetsController;

use App\Http\Controllers\Api\Agents\Voiceover\VoicesController;
use App\Http\Controllers\Api\Agents\Voiceover\SpeechController;

use App\Http\Controllers\Api\Agents\Chat\ChatController;
use App\Http\Controllers\Api\Agents\Chat\ConversationController;

Route::controller(SearchController::class)->group(function () {
    Route::get('/search', 'index');
});

Route::controller(LibraryController::class)->group(function () {
    Route::get('/library', 'index');
});

Route::prefix('/agent')->group(function () {
    Route::controller(CompletionController::class)->group(function () {
        Route::post('/completion/{uuid?}', 'handle')->where('uuid', '[a-z0-9-]+');
    });

    Route::prefix('/voiceover')->group(function () {
        Route::get('/voices', [VoicesController::class, 'index']);
        Route::post('/speech', [SpeechController::class, 'handle']);
    });

    Route::controller(ChatController::class)->group(function () {
        Route::post('/chat/{uuid?}', 'handle')->where('uuid', '[a-z0-9-]+');
    });
    Route::controller(ConversationController::class)->group(function () {
        Route::get('/chat/conversation/{uuid}', 'index')->where('uuid', '[a-z0-9-]+');
    });
});

Route::controller(PresetsController::class)->group(function () {
    Route::get('/presets', 'index');
    Route::get('/presets/mine', 'user');
    Route::get('/presets/discover', 'discover');
});

Route::controller(UsageController::class)->prefix('/user')->group(function () {
    Route::get('/usage', 'index');
});
