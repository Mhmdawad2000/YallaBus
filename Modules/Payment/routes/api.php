<?php

use Illuminate\Support\Facades\Route;
use Modules\Payment\Http\Controllers\PaymentController;


Route::get('paymant', [PaymentController::class, 'index']);
Route::post('paymant', [PaymentController::class, 'create']);
