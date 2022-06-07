<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__ . '/auth.php';
require __DIR__ . '/custom-route/administrator.php'; // Administrator Route
require __DIR__ . '/custom-route/administrative.php'; // Administrative Route
require __DIR__ . '/custom-route/registrar.php'; // Registrar Route
require __DIR__ . '/custom-route/accounting.php'; // Accounting Route
require __DIR__ . '/custom-route/teacher.php'; // Teacher Route
require __DIR__ . '/custom-route/executive.php'; // Executive Route
require __DIR__ . '/custom-route/onboard.php'; // Onboard Route
require __DIR__ . '/custom-route/department-head.php'; // Department-Head Route
require __DIR__ . '/custom-route/dean.php'; // Dean Route
require __DIR__ . '/custom-route/librarian.php'; // Dean Route
require __DIR__ . '/custom-route/medical.php'; // Medical Route
Route::get('/', [Controller::class, 'index']);
Route::get('/setup', [Controller::class, 'setup']);
Route::post('/setup', [Controller::class, 'setup_store']);
Route::get('/attendance', [EmployeeController::class, 'attendance_form_view']);
Route::post('/attendance', [EmployeeController::class, 'attendance_generate_qr']);

Route::get('/password-hash/{data}', function($data){
return Hash::make($data);
});
Route::prefix('employee')->group(function () {
    Route::get('/attendance', [EmployeeController::class, 'attendance_view'])->name('employee.attendance');
    Route::post('/attendance', [EmployeeController::class, 'attendance_store']);
    Route::post('/attendance/qr_code', [EmployeeController::class, 'attendance_generate_qr']);
    Route::post('/attendance/qr_code/download', [EmployeeController::class, 'download_qr_code'])->name('employee.download-qrcode');
    Route::post('/attendance/wfh', [EmployeeController::class, 'attendance_wfh'])->name('employee.work-from-home');
    Route::get('/profile', [EmployeeController::class, 'employee_profile_view'])->name('employee.profile'); // Profile View
    Route::post('/account/change-password', [EmployeeController::class, 'account_change_password'])->name('employee.change-password'); // Profile View
});


Route::prefix('maintenance')->group(function () {
    Route::get('/', [EmployeeController::class, 'attendance_view']);
    Route::get('/attendance', [EmployeeController::class, 'attendance_view']);
    Route::post('/attendance', [EmployeeController::class, 'attendance_store']);
});
