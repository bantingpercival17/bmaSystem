<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExecutiveOfficeController;
use Illuminate\Support\Facades\Route;

Route::prefix('executive')->group(function () {
    Route::get('/', [ExecutiveOfficeController::class, 'index'])->name('exo.dashboard'); // Dashboard View
    Route::get('/attendance', [ExecutiveOfficeController::class, 'index'])->name('exo.dashboard'); // Dashboard View
    Route::get('/attendance-checker', [EmployeeController::class, 'qr_scanner']);
    Route::get('/scan-code/{data}', [EmployeeController::class, 'scanner']);
    Route::get('/scan-code-v2/{data}', [EmployeeController::class, 'scanner_v2']);
    Route::post('/attendance', [EmployeeController::class, 'store']);
    Route::get('/fetch-attendance', [ExecutiveOfficeController::class, 'json_attendance']);

    // Staff
    Route::get('/staff-attendance')->name('exo.staff-attendance');


    // Student 
    Route::get('/student-attendance')->name('exo.student-attendance');
    Route::get('/semestral-clearance', [ExecutiveOfficeController::class, 'semestral_clearance_view'])->name('exo.semestral-clearance'); // Course View
    Route::get('/semestral-clearance/view', [ExecutiveOfficeController::class, 'semestral_student_list_view'])->name('exo.semestral-student-list'); // Section view
    Route::post('/semestral-clearance', [ExecutiveOfficeController::class, 'semestral_clearance_store'])->name('exo.semestral-clearance-store');
});
