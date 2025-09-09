<?php

use Illuminate\Support\Facades\Route;
use Modules\Payment\Http\Controllers\PaymentController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('exchange-rate', [PaymentController::class, 'exchangeRate']);
    Route::get('paymant', [PaymentController::class, 'index']);
    Route::post('paymant', [PaymentController::class, 'create']);
});
