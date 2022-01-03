<?php

use App\Http\Controllers\AdministrativeController;
use Illuminate\Support\Facades\Route;

Route::prefix('administrative')->group(function () {
    Route::get('/', [AdministrativeController::class, 'index'])->name('administrative.dashboard'); // Dashboard View
    Route::get('/dashboard', [AdministrativeController::class, 'index'])->name('administrative.dashboard'); // Dashboard View

    Route::get('/attendance', [AdministrativeController::class, 'attendance_view'])->name('administrative.attendance'); // Dashboard View
    // Attendance
    Route::get('/attendance/report', [AdministrativeController::class, 'attendance_report']); // Attendance Report



    Route::get('/employees', [AdministrativeController::class, 'employees_view'])->name('administrative.employees'); // 
    Route::get('/employees/view', [AdministrativeController::class, 'employees_profile_view'])->name('administrative.employee-profile'); // 
});
