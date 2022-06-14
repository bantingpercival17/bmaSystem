<?php

use App\Http\Controllers\GeneralController\ApplicantController;
use Illuminate\Support\Facades\Route;

Route::prefix('medical')->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::get('/overview', [ApplicantController::class, 'medical_overview'])->name('medical.overview');
        Route::get('/appointment-approval',[ApplicantController::class,'medical_appointment_approved'])->name('medical.applicant-appointment');
        Route::get('/appointment-download', [ApplicantController::class, 'medical_schedule_download'])->name('medical.download-appointment');
        Route::get('/applicant/medical-result',[ApplicantController::class,'meidcal_result'])->name('medical.applicant-medical-result');
    });
});
