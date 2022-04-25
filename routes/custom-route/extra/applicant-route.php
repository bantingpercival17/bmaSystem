<?php

use App\Http\Controllers\GeneralController\ApplicantController;
use Illuminate\Support\Facades\Route;

Route::get('/applicants', [ApplicantController::class, 'applicant_view'])->name('applicant-lists');
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
/* Examination  */
Route::get('/applicant/examinaiton', [ApplicantController::class, 'applicant_entrance_examination'])->name('applicant-examination-status');
