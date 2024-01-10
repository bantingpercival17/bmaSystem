<?php

use App\Http\Controllers\OnboardTrainingController;
use App\Http\Middleware\OnboardTraining;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\OnboardTraining\MidshipmanView;

Route::prefix('onboard')->group(function () {
    Route::get('/', [OnboardTrainingController::class, 'index'])->name('onboard.dashboard'); // Dashboard View
    Route::get('/dashboard', [OnboardTrainingController::class, 'index'])->name('onboard.dashboard'); // Dashboard View
    Route::get('/embarked-monitoring', [OnboardTrainingController::class, 'embarked_monitoring_view'])->name('onboard.embarked-list'); // Embarked View


    //Route::get('/midship-man', [OnboardTrainingController::class, 'midshipman_view'])->name('onboard.midshipman'); // Midship Man Profile
    Route::get('/midshipman/v2', [OnboardTrainingController::class, 'midshipman_view_v2'])->name('onboard.midshipman'); // Midship Man Profile
    Route::get('miship-man/shipboard-application/document-verification', [OnboardTrainingController::class, 'shipboard_application_verification'])->name('onboard.midshipman-shipboard-application');

    Route::post('/midship-man/shipboard-training', [OnboardTrainingController::class, 'onboard_info_store'])->name('onboard.onboard-info-store');
    Route::post('/midship-man/shipboard-training-update', [OnboardTrainingController::class, 'onboard_info_store'])->name('onboard.onboard-info-update');

    Route::post('/midship-man/certificates', [OnboardTrainingController::class, 'midshipman_certificate_store']);
    /* Shipboard */

    Route::get('/shipboard-monitoring', [OnboardTrainingController::class, 'onboard_training_view'])->name('onboard.shipboard'); // Midship Man Profile
    Route::get('/shipboard-monitoring/journal', [OnboardTrainingController::class, 'onboard_journal_view'])->name('onboard.journal'); // Journal View
    Route::get('/shipboard-monitoring/narative/approved', [OnboardTrainingController::class, 'onboard_narative_approved'])->name('onboard.narative-report-approved'); // Approved Narative report
    Route::post('/shipboard-monitoring/narative/disapproved', [OnboardTrainingController::class, 'onboard_narative_disapproved'])->name('onboard.narative-report-disapproved'); // Disapproved Narative Report;
    Route::get('/shipboard-monitoring/narative/generate-summay-report', [OnboardTrainingController::class, 'onboard_narative_summary_report'])->name('onboard.narative-summary-report');
    Route::get('/shipboard-monitoring/narative/generate-summay-report-v2', [OnboardTrainingController::class, 'onboard_narative_summary_report_v2'])->name('onboard.narative-summary-report-v2');
    Route::get('/shipboard-monitoring/narative/monthly-summay-report', [OnboardTrainingController::class, 'onboard_monthly_summary_report'])->name('onboard.narative-report-monthly-summary');
    Route::get('/shipboard-monitoring/narative/monthly-summay-report-v2', [OnboardTrainingController::class, 'onboard_monthly_summary_report_v2'])->name('onboard.narative-report-monthly-summary-v2');
    Route::get('/shipboard-monitoring/performance-report', [OnboardTrainingController::class, 'onboard_performance_report'])->name('onboard.performance-report');

    Route::get('/shipboard-monitoring/examination', [OnboardTrainingController::class, 'onboard_examination'])->name('onboard.examination');
    Route::post('/shipboard-monitoring/assessment-report', [OnboardTrainingController::class, 'onboard_assessment_report'])->name('onboard.assessment-report');
    Route::get('/shipboard-monitoring/assessment-report-v2', [OnboardTrainingController::class, 'onboard_assessment_report_v2'])->name('onboard.assessment-report-v2');

    /* Liveview Components */
    Route::get('/midshipman', MidshipmanView::class)->name('onboard.midshipman');
});
