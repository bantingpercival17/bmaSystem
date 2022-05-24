<?php

use App\Http\Controllers\GeneralController\ApplicantController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/applicants', [ApplicantController::class, 'applicant_view'])->name('applicant-lists');
    Route::get('/applicants/pre-register', [ApplicantController::class, 'pre_applicant_view'])->name('applicant.pre-register');
    Route::get('/applicant-profile/view', [ApplicantController::class, 'applicant_profile'])->name('applicant-profile');
    Route::get('/applicant-profile/document-notification', [ApplicantController::class, 'applicant_document_notification'])->name('document-notification'); // Send Email for Document Attachment
    Route::get('/applicant-profile/document-verification', [ApplicantController::class, 'applicant_document_review'])->name('document-verification');
    Route::get('/applicants/removed', [ApplicantController::class, 'applicant_removed'])->name('applicant-removed');
    Route::get('/applicants/verified', [ApplicantController::class, 'applicant_verified'])->name('applicant-verified');
    Route::get('/applicant/notification', [ApplicantController::class, 'send_email_notification'])->name('applicant-notification');
    Route::get('/applicant-list', [ApplicantController::class, 'applicant_list']);
    Route::get('/applicants/payment-verification', [ApplicantController::class, 'applicant_payment_verification'])->name('applicant-payment-verification');
    Route::get('/applicants/payment-verified', [ApplicantController::class, 'applicant_payment_verified'])->name('applicant-payment-verified');
    Route::get('/applicants/examination-notification', [ApplicantController::class, 'entrance_examination_notification'])->name('applicant-entrance-examination-notification');
    Route::get('/applicants/briefing-notification', [ApplicantController::class, 'briefing_notification'])->name('applicant.briefing-notification');
    /* Examination  */
    Route::get('/applicant/examination', [ApplicantController::class, 'applicant_entrance_examination'])->name('applicant-examination-status');
    Route::get('/applicant/examination-reset', [ApplicantController::class, 'applicant_examination_reset'])->name('applicant-examination-reset');
    ROute::get('/applicant/examination-remove', [ApplicantController::class, 'examination_remove'])->name('examination.remove');
});
