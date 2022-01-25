<?php

use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PaymongoApi;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [AdministratorController::class, 'index'])->name('admin.dashboard'); // Dashboard
Route::prefix('administrator')->group(function () {
    Route::get('/', [AdministratorController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard', [AdministratorController::class, 'index'])->name('admin.dashboard'); // Dashboard
    /* Students */
    Route::get('/students', [AdministratorController::class, 'student_view'])->name('admin.students'); // View Students
    Route::get('/students/view', [AdministratorController::class, 'student_profile'])->name('admin.student-profile');
    Route::post('/students/imports', [AdministratorController::class, 'student_imports']); // Import Student Details
    /* Accounts */
    Route::get('/accounts', [AdministratorController::class, 'account_view'])->name('admin.accounts'); // View Accounts
    Route::post('/accounts', [AdministratorController::class, 'account_store']); // Store Account
    Route::post('/accounts/profile-picture', [AdministratorController::class, 'account_upload_profile']);
    Route::post('/accounts/role', [AdministratorController::class, 'account_roles_store']);
    /* Subject and Curriculum Routes */
    Route::get('/subjects', [AdministratorController::class, 'subject_view'])->name('admin.subjects'); // Subject Curriculum
    Route::post('/curriculum', [AdministratorController::class, 'curriculum_store']); // Store a Curriculum
    Route::get('/subjects/curriculum', [AdministratorController::class, 'curriculum_view']); // Curriculum and Department View
    Route::post('/subjects', [AdministratorController::class, 'subject_store']); // Store New Subjects
    Route::get('/subjects/class', [AdministratorController::class, 'subject_class']); // Subject Class View
    Route::post('/subjects/class', [AdministratorController::class, 'subject_class_store']); // Store Subject Class
    Route::get('/subjects/class/removed', [AdministratorController::class, 'subject_class_remove']); // Removed Subject Class
    Route::post('/subjects-handle', [AdministratorController::class, 'subject_import']); // Import Subject Handle
    /* Subject and Curriculum Routes */
    /* Classes and Sections */
    Route::get('/classes', [AdministratorController::class, 'classes_view'])->name('admin.sections'); // Classes View
    Route::post('/classes', [AdministratorController::class, 'classes_store']); // Store Section
    Route::get('/classes/section', [AdministratorController::class, 'class_section_view']); // Section View
    Route::get('/classes/section/add', [AdministratorController::class, 'section_add']); // Section Add
    Route::get('/classes/section/remove', [AdministratorController::class, 'section_remove']); // Section Add
    Route::post('/classes/student-section-import', [AdministratorController::class, 'section_import']); // Batch Upload Section
    Route::get('/classes/section/report', [AdministratorController::class, 'section_report_list']); // List of Student Per Section
    /* Classes and Sections */
    /* Enrollment */
    Route::get('/semestral-clearance', [AdministratorController::class, 'clearance_view'])->name('admin.clearance'); // Clearance View
    Route::post('/semestral-clearance', [AdministratorController::class, 'clearance_store'])->name('admin.e-clearance'); // Enrollment View
    // Paymongo 
    Route::get('/paymongo', [PaymongoApi::class, 'view']); // Enrollment View

    // QR Code
    Route::get('/qr-code/{data}', [AdministratorController::class, 'qr_generator']);

    // Attendance
    Route::get('/attendance', [AdministratorController::class, 'attendance_view'])->name('admin.attendance'); // Attendance View
    Route::get('/attendance/report', [AdministratorController::class, 'attendance_report']); // Attendance Report

    // Employee
    Route::get('/accounts/view', [AdministratorController::class, 'employee_profile']);
    Route::post('/accounts/reset-password', [AdministratorController::class, 'employee_reset_password'])->name('admin.reset-password');


    /* Setting */
    Route::get('/setting', [AdministratorController::class, 'setting_view'])->name('admin.setting');
    Route::post('/setting/store-role', [AdministratorController::class, 'store_role'])->name('setting.store-role');
});
