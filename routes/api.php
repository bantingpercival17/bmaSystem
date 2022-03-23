<?php

use App\Http\Controllers\Api\ApplicantController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\PaymongoApi;
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
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/applicant/create', [ApplicantController::class, 'create_applicant_details']);
    Route::post('/logout', [ApplicantController::class, 'logout']);
});
Route::post('/paymongo-sources', [PaymongoApi::class, 'paymongo_sources']);
Route::get('/paymongo', [PaymongoApi::class, 'paymongo_view']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
