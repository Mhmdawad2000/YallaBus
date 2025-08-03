<?php

use Illuminate\Support\Facades\Route;
use Modules\Trip\Http\Controllers\Trip\TripController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('trips')->group(function () {
        Route::get('user/', [TripController::class, 'index']);
        Route::get('company/', [TripController::class, 'myTrips']);
        Route::post('/', [TripController::class, 'store']);
        Route::get('/{id}', [TripController::class, 'show']);
        Route::put('/{id}', [TripController::class, 'update']);
        Route::delete('/{id}', [TripController::class, 'destroy']);
        Route::post('/{id}/status', [TripController::class, 'updateStatus']);
    });
});
