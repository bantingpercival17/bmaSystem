<?php

use App\Http\Controllers\GeneralController\ApplicantController;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/applicants', [ApplicantController::class, 'applicant_view'])->name('applicant-lists');
    Route::get('/applicants/applicant-profile/view', [ApplicantController::class, 'applicant_profile'])->name('applicant-profile');
    Route::get('/applicants/regirstration-form', [ApplicantController::class, 'applicant_registration_form'])->name('applicant-form');
    Route::get('/applicants/applicant-profile/document-notification', [ApplicantController::class, 'applicant_document_notification'])->name('document-notification'); // Send Email for Document Attachment
    Route::get('/applicants/applicant-profile/document-verification', [ApplicantController::class, 'applicant_document_review'])->name('document-verification');
    Route::get('/applicants/removed', [ApplicantController::class, 'applicant_removed'])->name('applicant-removed');
    Route::get('/applicant/notification', [ApplicantController::class, 'send_email_notification'])->name('applicant-notification');
    Route::get('/applicants/bma-alumnia', [ApplicantController::class, 'applicant_alumnia'])->name('applicant.bma-alumnia'); // Function for Bma Alumnia
    Route::get('/applicants/examination-notification', [ApplicantController::class, 'entrance_examination_notification'])->name('applicant-entrance-examination-notification');
    Route::get('/applicants/briefing-notification', [ApplicantController::class, 'briefing_notification'])->name('applicant.briefing-notification');
    /* Examination  */
    Route::get('/applicant/examination', [ApplicantController::class, 'applicant_entrance_examination'])->name('applicant-examination-status');
    Route::get('/applicant/examination-reset', [ApplicantController::class, 'applicant_examination_reset'])->name('applicant-examination-reset');
    Route::get('/applicant/examination-logs', [ApplicantController::class, 'applicant_examination_log'])->name('applicant-examination-log');
    Route::get('/applicant/examination-result', [ApplicantController::class, 'appllicant_examination_result'])->name('applicant-examination-result');
    ROute::get('/applicant/examination-remove', [ApplicantController::class, 'examination_remove'])->name('examination.remove');
    Route::get('/applicant/virtual-briefing', [ApplicantController::class, 'virtual_briefing_view'])->name('applicant-virtual-briefing');
    Route::post('/applicant/change-course', [ApplicantController::class, 'applicant_change_course'])->name('applicant.applicant-change-course');
    Route::get('/applicant/not-qualifed', [ApplicantController::class, 'applicant_not_qualified'])->name('applicant.applicant-not-qualified');
    Route::post('/applicant/orientation-scheduled', [ApplicantController::class, 'applicant_orientation_schedule'])->name('applicant.orientation-scheduled');
    Route::get('/applicant/orientation-attended', [ApplicantController::class, 'applicant_orientation_attended'])->name('applicant.orientation-attended');
});
