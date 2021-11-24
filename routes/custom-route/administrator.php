<?php

use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PaymongoApi;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [AdministratorController::class, 'index']); // Dashboard
Route::prefix('administrator')->group(function () {
    Route::get('/', [AdministratorController::class, 'index']);
    Route::get('/dashboard', [AdministratorController::class, 'index']); // Dashboard
    /* Students */
    Route::get('/students', [AdministratorController::class, 'student_view']); // View Students
    Route::post('/students/imports', [AdministratorController::class, 'student_imports']); // Import Student Details
    /* Students */
    Route::get('/accounts', [AdministratorController::class, 'account_view']); // View Accounts
    Route::post('/accounts', [AdministratorController::class, 'account_store']); // Store Account
    /* Subject and Curriculum Routes */
    Route::get('/subjects', [AdministratorController::class, 'subject_view']); // Subject Curriculum
    Route::post('/curriculum', [AdministratorController::class, 'curriculum_store']); // Store a Curriculum
    Route::get('/subjects/curriculum', [AdministratorController::class, 'curriculum_view']); // Curriculum and Department View
    Route::post('/subjects', [AdministratorController::class, 'subject_store']); // Store New Subjects
    Route::get('/subjects/class', [AdministratorController::class, 'subject_class']); // Subject Class View
    Route::post('/subjects/class', [AdministratorController::class, 'subject_class_store']); // Store Subject Class
    Route::get('/subjects/class/removed', [AdministratorController::class, 'subject_class_remove']); // Removed Subject Class
    Route::post('/subjects-handle', [AdministratorController::class, 'subject_import']); // Import Subject Handle
    /* Subject and Curriculum Routes */
    /* Classes and Sections */
    Route::get('/classes', [AdministratorController::class, 'classes_view']); // Classes View
    Route::post('/classes', [AdministratorController::class, 'classes_store']); // Store Section
    Route::get('/classes/section', [AdministratorController::class, 'class_section_view']); // Section View
    Route::get('/classes/section/add', [AdministratorController::class, 'section_add']); // Section Add
    Route::get('/classes/section/remove', [AdministratorController::class, 'section_remove']); // Section Add
    Route::post('/classes/student-section-import', [AdministratorController::class, 'section_import']); // Batch Upload Section
    Route::get('/classes/section/report', [AdministratorController::class, 'section_report_list']); // List of Student Per Section
    /* Classes and Sections */
    /* Enrollment */
    Route::get('/enrollment', [AdministratorController::class, 'enrollment_view']); // Enrollment View




    // Paymongo 
    Route::get('/paymongo', [PaymongoApi::class, 'view']); // Enrollment View

    // QR Code
    Route::get('/qr-code/{data}', [AdministratorController::class, 'qr_generator']);

    // Employee 
    Route::get('/attendance', [EmployeeController::class, 'view']); // Attendance View
});
