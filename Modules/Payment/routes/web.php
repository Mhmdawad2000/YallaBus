<?php

use Illuminate\Support\Facades\Route;
use Modules\Payment\Http\Controllers\PaymentController;



Route::middleware('auth')->group(function () {
    Route::get('exchange-rate', [PaymentController::class, 'exchangeRate']);
    Route::get('stripe', [PaymentController::class, 'index']);
    Route::post('stripe', [PaymentController::class, 'create'])->name('stripe.post');
});
