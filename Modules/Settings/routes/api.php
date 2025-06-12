<?php

use Illuminate\Support\Facades\Route;
use Modules\Settings\Http\Controllers\API\City\CityController;
use Modules\Settings\Http\Controllers\API\Role\RoleController;
use Modules\Settings\Http\Controllers\API\Country\CountryController;
use Modules\Settings\Http\Controllers\API\Currency\CurrencyController;
use Modules\Settings\Http\Controllers\API\Permission\PermissionController;

Route::prefix('currencies')->group(function () {
    Route::get('/', [CurrencyController::class, 'index']);
    Route::get('/{id}', [CurrencyController::class, 'show']);
});
Route::prefix('countries')->group(function () {
    Route::get('/', [CountryController::class, 'index']);
    Route::get('/{id}', [CountryController::class, 'show']);
});
Route::prefix('cities')->group(function () {
    Route::get('/', [CityController::class, 'index']);
    Route::get('/{id}', [CityController::class, 'show']);
});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::post('/', [RoleController::class, 'store']);
        Route::get('/my-role', [RoleController::class, 'showMyRole']);
        Route::get('/{id}', [RoleController::class, 'show']);
        Route::patch('/{id}', [RoleController::class, 'update']);
        Route::delete('/{id}', [RoleController::class, 'destroy']);
    });

    Route::prefix('permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'index']);
        Route::get('/{id}', [PermissionController::class, 'show']);
        Route::put('/{id}', [PermissionController::class, 'update']);
    });
    Route::prefix('currencies')->group(function () {
        Route::post('/', [CurrencyController::class, 'store']);
        Route::get('/default', [CurrencyController::class, 'default']);
        Route::put('/{id}', [CurrencyController::class, 'update']);
        Route::delete('/{id}', [CurrencyController::class, 'destroy']);
        Route::put('/toggle-default/{id}', [CurrencyController::class, 'toggleDefault']);
    });
    Route::prefix('countries')->group(function () {
        Route::post('/', [CountryController::class, 'store']);
        Route::put('/{id}', [CountryController::class, 'update']);
        Route::delete('/{id}', [CountryController::class, 'destroy']);
    });
    Route::prefix('cities')->group(function () {
        Route::post('/', [CityController::class, 'store']);
        Route::put('/{id}', [CityController::class, 'update']);
        Route::delete('/{id}', [CityController::class, 'destroy']);
    });

});
