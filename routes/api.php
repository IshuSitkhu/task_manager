<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;

// Public route
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (login required)
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // laravel automatic create
    Route::apiResource('projects', ProjectController::class);


});
