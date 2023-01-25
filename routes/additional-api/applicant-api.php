<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApplicantController;
use App\Http\Controllers\Api\AuthController;

Route::post('/applicant/login', [AuthController::class, 'applicant_login']);
Route::post('/applicant/register', [AuthController::class, 'applicant_registration']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/applicant/information', [ApplicantController::class, 'applicant_information']);
    Route::post('/applicant/logout', [ApplicantController::class, 'applicant_logout']);
});
