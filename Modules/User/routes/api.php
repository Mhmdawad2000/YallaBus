<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserCRUD\UserCRUDController;

Route::middleware(['auth:sanctum'])->prefix('user/settings/')->group(function () {

    Route::patch('change-password', [UserCRUDController::class, 'changePassword']);
    Route::patch('update-profile', [UserCRUDController::class, 'updateProfile']);
    Route::patch('update-contact-info', [UserCRUDController::class, 'updateContactInfo']);
    Route::post('avatar', [UserCRUDController::class, 'avatar']);


});