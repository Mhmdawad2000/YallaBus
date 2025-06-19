<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Mangement_Users\UserMangementController;

Route::middleware(['auth:sanctum'])->group(function () {

    // User Mangement
    Route::prefix('users')->group(function () {
        Route::post('', [UserMangementController::class, 'store']);
        Route::get('/{userId}', [UserMangementController::class, 'show']);
        Route::get('', [UserMangementController::class, 'index']);
        Route::patch('/{userId}', [UserMangementController::class, 'update']);
        Route::delete('/{userId}', [UserMangementController::class, 'delete']);
    });




});