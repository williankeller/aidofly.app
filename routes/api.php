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

    Route::controller(ChatController::class)->group(function () {
        Route::post('/chat/{uuid?}', 'handle');
        Route::post('/mock', function () {
            return response('event: token
data: "Hello"
id: 1716066797

event: token
data: "!"
id: 1716066797

event: token
data: " How"
id: 1716066797

event: token
data: " can"
id: 1716066797

event: token
data: " I"
id: 1716066798

event: token
data: " assist"
id: 1716066798

event: token
data: " you"
id: 1716066798

event: token
data: " today"
id: 1716066798

event: token
data: "?\n"
id: 1716066798

event: message
data: {"object":"message","id":"018f8d71-d4bc-73fc-9d7c-084440de5660","model":"gpt-3.5-turbo","role":"assistant","content":"Hello! How can I assist you today?","quote":null,"cost":"0.002125","created_at":1716064998,"assistant":null,"parent_id":"018f8d71-d2e9-73cc-9805-89a20094318e","user":null,"image":null}
id: 1716066798');
        });
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
