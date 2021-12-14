<?php

use App\Http\Controllers\AdministrativeController;
use Illuminate\Support\Facades\Route;

Route::prefix('administrative')->group(function () {
    Route::get('/', [AdministrativeController::class, 'index']); // Dashboard View


    // Attendance
    Route::get('/attendance/report', [AdministrativeController::class, 'attendance_report']); // Attendance Report
});
