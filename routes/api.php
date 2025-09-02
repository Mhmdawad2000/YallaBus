<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripSuggestionController;

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/trips/suggestions', [TripSuggestionController::class, 'getSuggestedTrips']);
});
