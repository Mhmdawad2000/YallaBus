<?php
use Illuminate\Support\Facades\Route;
use Modules\Company\Http\Controllers\Driver\DriverController;

Route::middleware(['auth:sanctum'])->prefix('{role}')->group(function () {
    Route::prefix('drivers')->group(function () {
        Route::get('', [DriverController::class, 'index']);
        Route::get('{id}', [DriverController::class, 'show']);
        Route::post('', [DriverController::class, 'store']);
        Route::post('{id}', [DriverController::class, 'update']);
        Route::delete('{id}', [DriverController::class, 'destroy']);
    });
});
