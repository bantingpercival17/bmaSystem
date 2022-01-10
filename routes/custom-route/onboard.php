<?php

use App\Http\Controllers\OnboardTrainingController;
use App\Http\Middleware\OnboardTraining;
use Illuminate\Support\Facades\Route;

Route::prefix('onboard')->group(function () {
    Route::get('/', [OnboardTrainingController::class, 'index'])->name('onboard.dashboard'); // Dashboard View
    Route::get('/dashboard', [OnboardTrainingController::class, 'index'])->name('onboard.dashboard'); // Dashboard View
    Route::get('/midship-man', [OnboardTrainingController::class, 'midshipman_view'])->name('onboard.midshipman'); // Midship Man Profile
    Route::post('/midship-man/certificates', [OnboardTrainingController::class, 'midshipman_certificate_store']);
    /* Shipboard */

    Route::get('/shipboard-monitoring', [OnboardTrainingController::class, 'onboard_training_view'])->name('onboard.shipboard'); // Midship Man Profile
    Route::get('/shiboard-monitoring/journal', [OnboardTrainingController::class, 'onboard_journal_view'])->name('onboard.journal'); // Journal View
    Route::get('/shiboard-monitoring/narative/approved', [OnboardTrainingController::class, 'onboard_narative_approved'])->name('onboard.narative-report-approved'); // Approved Narative report
    Route::post('/shiboard-monitoring/narative/disapproved',[OnboardTrainingController::class,'onboard_narative_disapproved'])->name('onboard.narative-report-disapproved');
});
