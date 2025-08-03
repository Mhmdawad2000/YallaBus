<?php
use Illuminate\Support\Facades\Route;
use Modules\Company\Http\Controllers\Bus\BusController;
use Modules\Company\Http\Controllers\Seat\SeatController;
use Modules\Company\Http\Controllers\Driver\DriverController;

Route::middleware(['auth:sanctum'])->prefix('{role}')->group(function () {
    Route::prefix('/drivers')->group(function () {
        Route::get('', [DriverController::class, 'index']);
        Route::get('{id}', [DriverController::class, 'show']);
        Route::post('', [DriverController::class, 'store']);
        Route::post('{id}', [DriverController::class, 'update']);
        Route::delete('{id}', [DriverController::class, 'destroy']);
    });
    Route::prefix('buses')->group(function () {
        Route::get('', [BusController::class, 'index']);
        Route::post('', [BusController::class, 'store']);
        Route::get('/{id}', [BusController::class, 'show']);
        Route::patch('/{id}', [BusController::class, 'update']);
        Route::delete('/{id}', [BusController::class, 'destroy']);
    });

    Route::prefix('seats')->group(function () {
        Route::get('', [SeatController::class, 'index']);
        Route::post('', [SeatController::class, 'store']);
        Route::get('/{id}', [SeatController::class, 'show']);
        Route::put('/{id}', [SeatController::class, 'update']);
        Route::delete('/{id}', [SeatController::class, 'destroy']);
    });
});
