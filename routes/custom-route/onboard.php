<?php

use App\Http\Controllers\OnboardTrainingController;
use Illuminate\Support\Facades\Route;

Route::prefix('onboard')->group(function () {
    Route::get('/', [OnboardTrainingController::class, 'index']); // Dashboard View
    Route::get('/dashboard', [OnboardTrainingController::class, 'index']); // Dashboard View
    Route::get('/midship-man', [OnboardTrainingController::class, 'midshipman_view']);
});
