<?php

use App\Http\Controllers\Api\ApplicantController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExportController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\PaymongoApi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

//Route::post('/applicant/create', [ApplicantController::class, 'create_applicant_details']);
/* Route::middleware('auth:applicant')->group(function () {
    Route::post('/applicant/create', [ApplicantController::class, 'create_applicant_details']);
}); */
Route::post('/student/login', [AuthController::class, 'student_login']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/applicant/create', [ApplicantController::class, 'create_applicant_details']);
    Route::post('/logout', [ApplicantController::class, 'logout']);



    // Student API
    Route::get('/student', [StudentController::class, 'student_details']);
    Route::get('/student/onboard', [StudentController::class, 'student_onboarding']);
});
Route::post('/paymongo-sources', [PaymongoApi::class, 'paymongo_sources']);
Route::get('/paymongo', [PaymongoApi::class, 'paymongo_view']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/staff-details', [ExportController::class, 'export_staff']);
Route::get('/course', [ExportController::class, 'export_course']);

Route::get('/academic-year', [ExportController::class, 'export_academic_year']);
