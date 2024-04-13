<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthorizeController;
use App\Http\Controllers\Api\Auth\RegisterController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/auth/authorize', [AuthorizeController::class, 'authorize']);
Route::post('/auth/register', [RegisterController::class, 'register']);
