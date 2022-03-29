<?php

use App\Http\Controllers\GeneralController\ApplicantController;
use Illuminate\Support\Facades\Route;

Route::get('/applicants', [ApplicantController::class, 'applicant_view'])->name('applicant-lists');
Route::get('/applicant-profile/view', [ApplicantController::class, 'applicant_profile'])->name('applicant-profile');
Route::get('/applicant-profile/document-notification', [ApplicantController::class, 'applicant_document_notification'])->name('document-notification'); // Send Email for Document Attachment
Route::get('/applicant-profile/document-verification', [ApplicantController::class, 'applicant_document_review'])->name('document-verification');
