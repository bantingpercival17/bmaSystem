<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApplicantController;
use App\Http\Controllers\Api\ApplicantEnrollmentController;
use App\Http\Controllers\Api\AuthController;

Route::post('/applicant/login', [AuthController::class, 'applicant_login']);
Route::post('/applicant/register', [AuthController::class, 'applicant_registration']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/applicant/token', function () {
        return 'valid';
    });
    Route::get('/applicant/information', [ApplicantController::class, 'applicant_information']);
    Route::post('/applicant/information', [ApplicantController::class, 'applicant_store_information']);
    Route::get('/applicant/registration-form', [ApplicantController::class, 'applicant_registration_form']);
    Route::post('/applicant/document-requirement-upload', [ApplicantController::class, 'file_upload']);
    Route::post('/applicant/payment-transaction', [ApplicantController::class, 'payment_transaction']);
    Route::post('/applicant/examination-scheduled', [ApplicantController::class, 'examination_scheduled']);
    Route::post('/applicant/examination', [ApplicantController::class, 'examination_verification']);
    Route::get('/applicant/examination', [ApplicantController::class, 'examination_questions']);
    Route::post('/applicant/examination/answer', [ApplicantController::class, 'examination_answer']);
    Route::post('/applicant/examination/finish', [ApplicantController::class, 'examination_finish']);
    Route::get('/applicant/medical-schedule/{data}', [ApplicantController::class, 'medical_appointment']);
    Route::get('/applicant/medical-schedule/slot/{data}', [ApplicantController::class, 'medical_appointment_slot']);
    # ENROLLMENT PROCUDURE 
    Route::get('/applicant/enrollment',[ApplicantEnrollmentController::class,'enrollment_
    ']);
    Route::post('/applicant/logout', [ApplicantController::class, 'applicant_logout']);
});
/* Route::middleware('auth:sanctum,applicant')->group(function () {
    Route::get('/applicant/information', [ApplicantController::class, 'applicant_information']);
    Route::post('/applicant/logout', [ApplicantController::class, 'applicant_logout']);
}); */
