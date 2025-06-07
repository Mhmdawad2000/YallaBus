<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserController;
use Modules\User\Http\Controllers\Auth\LoginController;
use Modules\User\Http\Controllers\Auth\SignUpController;
use Modules\User\Http\Controllers\Auth\PasswordController;


Route::post('sign-up', [SignUpController::class, 'signUp']);
Route::post('login', [LoginController::class, 'login']);
Route::get('verify', [SignUpController::class, 'verify']);
Route::post('/send-verify-email', [PasswordController::class, 'sendVerifyLinkEmail']);
Route::post('/forgot-password', [PasswordController::class, 'sendResetLinkEmail']);