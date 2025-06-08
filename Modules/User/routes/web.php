<?php

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserController;
use Modules\User\Http\Controllers\Auth\LoginController;
use Modules\User\Http\Controllers\Auth\PasswordController;

// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::resource('users', UserController::class)->names('user');
// });
Route::get('/forgot-password', [PasswordController::class, 'showLinkRequestForm'])->name('password.request');

Route::get('/reset-password/{token}', [PasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordController::class, 'reset'])->name('password.update');
Route::post('/login', [LoginController::class, 'login'])->name('login');

