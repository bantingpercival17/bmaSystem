<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExportController;
use App\Http\Controllers\Api\ShipboardTraining;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\StudentSubjectsController;
use App\Http\Controllers\PaymongoApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Mews\Captcha\Facades\Captcha;
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

Route::get('/form-recaptcha', function () {
    try {
        $captcha = Captcha::src('default');
        /*  return response()->json([
            'captcha' => $captcha['sensitive'],
            'captcha_image' => $captcha['image'],
        ]); */
        return response()->json(['captcha' => Captcha::img()]);
    } catch (\Throwable $error) {
        return response([
            'message' => $error->getMessage()
        ], 402);
    }
    /* return response()->json(['captcha' => Captcha::img()]); */
});
Route::post('/student/login', [AuthController::class, 'student_login']); // Login Api for Offical Student of the BMA
Route::post('/student/forget-password', [AuthController::class, 'student_forget_password']);
Route::get('/csrf-token', function (Request $request) {
    $userAgent = $request->header('User-Agent');
    $ipAddress = $request->ip();
    $jsonData = json_encode($userAgent);

    // Generate a unique file name
    $fileName = 'data_' . $ipAddress . '.json';

    // Specify the file path
    $filePath =  '/device/' . $fileName;

    // Save the JSON data to the file
    //file_put_contents($filePath, $jsonData);
    Storage::disk('public')->put($fileName, $jsonData);
    // Extract device information from the user agent string
    // You can use regular expressions or other parsing techniques here
    // to extract information such as browser, operating system, etc.
    $browser = ''; // Extract browser information
    $platform = ''; // Extract operating system information
    $deviceType = ''; // Determine the device type (desktop, mobile, tablet, etc.)

    // Create an array or object to store the device information
    $deviceInfo = [
        'browser' => $browser,
        'platform' => $platform,
        'deviceType' => $deviceType,
    ];

    // Return the device information as a response or use it as needed
    //return response()->json($deviceInfo);
    return response()->json(['csrf_token' => csrf_token(), $userAgent]);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    //Route::post('/applicant/create', [ApplicantController::class, 'create_applicant_details']);
    //Route::post('/logout', [ApplicantController::class, 'logout']);

    // Student API
    Route::get('/student', [StudentController::class, 'student_details']);
    Route::get('/student/information', [StudentController::class, 'student_information']);
    Route::post('/student/update-information', [StudentController::class, 'student_update_information']);
    Route::get('/student/enrollment', [StudentController::class, 'enrollment_overview']);
    Route::get('/student/enrollment-history', [StudentController::class, 'student_enrollment_history']);
    Route::get('/student/enrollment/cor/{data}', [StudentController::class, 'student_enrollment_coe']);
    Route::post('/student/enrollment/registration', [StudentController::class, 'enrollment_application']);
    Route::post('/student/enrollment/payment-mode', [StudentController::class, 'enrollment_payment_mode']); // Payment Mode

    //SBT ENROLLMENT
    Route::get('student/onboard/enrollment', [StudentController::class, 'student_onboard_enrollment']);
    Route::get('student/onboard/enrollment-application', [StudentController::class, 'student_enrollment_application_sbt']);
    Route::post('student/onboard/enrollment-payment', [StudentController::class, 'student_enrollment_payment_store']);

    // PAYMENT OVERVIEW
    Route::get('student/payment-overview', [StudentController::class, 'student_payment_overview']);
    Route::post('student/payment-transaction', [StudentController::class, 'student_payment_transaction']);
    // SBT
    Route::get('/student/onboard', [StudentController::class, 'student_onboarding']);
    Route::post('/student/onboard/enrollment', [ShipboardTraining::class, 'onboard_enrollment']);
    Route::post('/student/onboard/upload-file', [ShipboardTraining::class, 'upload_documents']);
    #Route::post('/student/onboard/reupload-file', [ShipboardTraining::class, 'reupload_documents']);
    Route::post('/student/onboard/document-reupload', [ShipboardTraining::class, 'reupload_documents_v2']);
    Route::get('/student/onboard/performance', [ShipboardTraining::class, 'shipboard_performance_view']);
    Route::post('/student/onboard/performance', [ShipboardTraining::class, 'shipboard_performance_store']);
    Route::get('/student/onboard/performance/view', [ShipboardTraining::class, 'performance_report_view']);
    Route::post('/student/onboard/performance/view', [ShipboardTraining::class, 'performance_file_attachment']);
    Route::get('student/onboard/assessment', [ShipboardTraining::class, 'student_onboard_assessment_view']);
    Route::post('student/onboard/assessment', [ShipboardTraining::class, 'student_onboard_assessment_verification']);
    Route::get('student/onboard/assessment/questioner', [ShipboardTraining::class, 'student_onboard_assessment_questioner']);
    Route::post('student/onboard/assessment/questioner', [ShipboardTraining::class, 'student_assessment_answer']);
    Route::post('student/onboard/assessment/finish', [ShipboardTraining::class, 'finish_onboard_assessment']);
    // ACADEMIC
    Route::get('/student/subject-lists', [StudentSubjectsController::class, 'subject_lists']);
    Route::get('/student/subject-lists/view', [StudentSubjectsController::class, 'subject_view']);
    Route::get('/student/semestral-grade', [StudentController::class, 'semestral_grade']);

    // LOGOUT
    Route::post('/student/logout', [StudentController::class, 'student_logout']);
    // Route::
    Route::get('/staff/image', [StudentController::class, 'teacher_image']);
});
require __DIR__ . '/additional-api/applicant-api.php';
require __DIR__ . '/additional-api/attendance-api.php';
Route::post('/paymongo-sources', [PaymongoApi::class, 'paymongo_sources']);
Route::get('/paymongo', [PaymongoApi::class, 'paymongo_view']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/staff-details', [ExportController::class, 'export_staff']);
Route::get('/course', [ExportController::class, 'export_course']);

Route::get('/academic-year', [ExportController::class, 'export_academic_year']);
