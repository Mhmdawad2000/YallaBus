<?php

use Illuminate\Support\Facades\Route;
use Modules\Booking\Http\Controllers\Booking\BookingController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('bookings')->group(function () {
        Route::get('/', [BookingController::class, 'index']);
        Route::get('user/', [BookingController::class, 'meIndex']);
        Route::post('/', [BookingController::class, 'store']);
        Route::get('/{id}', [BookingController::class, 'show']);
        // Route::put('/{id}', [BookingController::class, 'update']);
        Route::delete('/{id}', [BookingController::class, 'destroy']);
        Route::post('/{id}/cancel', [BookingController::class, 'cancel']);
        // Route::post('/{id}/status', [BookingController::class, 'status']);
    });
});
