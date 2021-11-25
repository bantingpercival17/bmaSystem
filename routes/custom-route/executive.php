<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExecutiveOfficeController;
use Illuminate\Support\Facades\Route;

Route::prefix('executive')->group(function () {
    Route::get('/', [ExecutiveOfficeController::class, 'index']); // Dashboard View
    Route::get('/attendance', [ExecutiveOfficeController::class, 'index']); // Dashboard View
    Route::get('/attendance-checker', [EmployeeController::class, 'qr_scanner']);
    Route::get('/scan-code/{data}', [EmployeeController::class, 'scanner']);
    Route::post('/attendance', [EmployeeController::class, 'store']);
    Route::get('/fetch-attendance', [ExecutiveOfficeController::class, 'json_attendance']);
});
