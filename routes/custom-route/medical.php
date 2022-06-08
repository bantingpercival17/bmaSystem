<?php

use App\Http\Controllers\GeneralController\ApplicantController;
use Illuminate\Support\Facades\Route;

Route::prefix('medical')->group(function () {
    Route::get('/overview', [ApplicantController::class, 'medical_overview'])->name('medical.overview');
    Route::get('/appointment-download', [ApplicantController::class, 'medical_schedule_download'])->name('medical.download-appointment');
});
