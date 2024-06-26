<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttendanceController;
use Illuminate\Http\Request;

Route::get('attendance', [AttendanceController::class, 'fetch_attendance']);

Route::get('attendance/store', [AttendanceController::class, 'store_attendance']);
Route::get('data-sync', [AttendanceController::class, 'data_sync']);
Route::post('employee-attendance', [AttendanceController::class, 'employee_attendance_sync']);
Route::post('student-attendance', [AttendanceController::class, 'students_attendance_sync']);
