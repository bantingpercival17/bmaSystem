<?php

use App\Http\Controllers\GeneralController\ApplicantController;
use App\Http\Controllers\MedicalController;
use App\Http\Livewire\Medical\ApplicantView;
use App\Http\Livewire\Medical\StudentView;
use Illuminate\Support\Facades\Route;

Route::prefix('medical')->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::get('/overview', [ApplicantController::class, 'medical_overview'])->name('medical.overview');
        Route::get('/appointment-approval', [ApplicantController::class, 'medical_appointment_approved'])->name('medical.applicant-appointment');
        Route::post('/appointment-medical', [ApplicantController::class, 'medical_appointment'])->name('medical.applicant-schedule');
        Route::get('/appointment-download', [ApplicantController::class, 'medical_schedule_download'])->name('medical.download-appointment');
        Route::get('/applicant/medical-result', [ApplicantController::class, 'medical_result'])->name('medical.applicant-medical-result');

        Route::get('/student/medical-appointment', [MedicalController::class, 'student_medical_appointment'])->name('medical.student-medical-appointment');
        Route::get('/student/medical-appointment/approval', [MedicalController::class, 'student_medical_appointment_approved'])->name('medical.student-appointment');
        Route::get('/student/medical-appointment/result', [MedicalController::class, 'student_medical_result'])->name('medical.student-medical-result');

        Route::get('/applicant/medical-appointment/report', [MedicalController::class, 'applicant_medical_list_report'])->name('medical.export-medical-applicant-list');
        Route::get('/student/medical-appointment/report', [MedicalController::class, 'student_medical_list_report'])->name('medical.export-medical-student-list');
        Route::get('/appointment-schedule', [MedicalController::class, 'appointment_view'])->name('medical.appoitnment-schedule');
        Route::post('/appointment-schedule', [MedicalController::class, 'appointment_store'])->name('medical.appoitnment-schedule-store');

        // Livewire
        Route::get('/applicant/overview', ApplicantView::class)->name('medical.applicant-view');
        Route::get('/student/overview', StudentView::class)->name('medical.student-view');


        // Report 
        Route::get('applicant/medical-report', [MedicalController::class, 'applicant_medical_report'])->name('medical.applicant-report');
        Route::get('student/medical-report', [MedicalController::class, 'student_medical_report'])->name('medical.student-report');
    });
});
