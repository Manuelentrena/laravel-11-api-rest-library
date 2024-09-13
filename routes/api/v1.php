<?php

use App\Http\Controllers\API\V1\Library\BookController;
use App\Http\Controllers\API\V1\Library\GenreController;
use App\Http\Controllers\API\V1\Library\LoanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\Auth\{
    LoginController,
    LogoutController,
    RegisterController,
};
use App\Http\Controllers\API\V1\Library\AuthorController;

Route::prefix('auth')->group(function () {
    Route::post('register', RegisterController::class)->name('auth.register');
    Route::post('login', LoginController::class)->name('auth.login');
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('logout', LogoutController::class)->name('auth.logout');
    });
});

Route::prefix('library')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('authors', AuthorController::class);
    Route::apiResource('genres', GenreController::class);
    Route::apiResource('books', BookController::class);
    Route::patch('books/{book}/stock', [BookController::class, 'updateStock'])->name('books.update_stock');
    Route::apiResource('loans', LoanController::class)
        ->only(['index', 'store', 'show']);
    Route::patch('loans/{loan}/return', [LoanController::class, 'returnLoan'])->name('loans.return_loan');
});
