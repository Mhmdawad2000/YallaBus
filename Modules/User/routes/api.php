<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserCRUD\UserCRUDController;

Route::middleware(['auth:sanctum'])->prefix('user/')->group(function () {

    Route::patch('settings/change-password', [UserCRUDController::class, 'changePassword']);
    Route::patch('settings/update-profile', [UserCRUDController::class, 'updateProfile']);
    Route::patch('settings/update-contact-info', [UserCRUDController::class, 'updateContactInfo']);
    Route::post('settings/avatar', [UserCRUDController::class, 'avatar']);
    Route::post('settings/company/logo', [UserCRUDController::class, 'logo']);
    Route::get('me', [UserCRUDController::class, 'me']);


});