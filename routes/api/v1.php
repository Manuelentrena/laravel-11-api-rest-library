<?php

use App\Http\Controllers\API\V1\Library\GenreController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\Auth\{
    LoginController,
    LogoutController,
    RegisterController,
};
use App\Http\Controllers\API\V1\Library\AuthorController;

Route::prefix('auth')->group(function () {
    Route::post('register', RegisterController::class);
    Route::post('login', LoginController::class);
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('logout', LogoutController::class);
    });
});

Route::prefix('library')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('authors', AuthorController::class);
    Route::apiResource('genres', GenreController::class);
});
