<?php

use App\Http\Controllers\RegistrarController;
use Illuminate\Support\Facades\Route;

Route::prefix('registrar')->group(function () {
    // Dashboard
    Route::get('/', [RegistrarController::class, 'index'])->name('registrar.dashboard');

    Route::get('/dashboard', [RegistrarController::class, 'index'])->name('registrar.dashboard');
    // Applicants

    // Subjects
    Route::get('/subjects', [RegistrarController::class, 'subject_view']); // Subject Views
    Route::get('/subjects/classes', [RegistrarController::class, 'classes_view']); // Subject Classes View
    Route::post('/subjects/classes', [RegistrarController::class, 'classes_store']); // Store Subjects Classes Handled
    Route::get('/subjects/classes/removed', [RegistrarController::class, 'classes_removed']); // Remove Subjects Clases Handled
    Route::get('/subjects/curriculum', [RegistrarController::class, 'curriculum_view']); // Curriculum Subject View
    Route::post('/subjects/curriculum', [RegistrarController::class, 'curriculum_subject_store']); // Store Curriculum Subject

    // Enrollment
    Route::get('/enrollment', [RegistrarController::class, 'enrollment_view'])->name('registrar.enrollment');
    // Student Profile
    Route::get('/student-profile', [RegistrarController::class, 'student_list_view'])->name('registrar.students'); // Student List View
    Route::get('/student-profile/view', [RegistrarController::class, 'student_profile_view']);

    // Section 
    Route::get('/sections', [RegistrarController::class, 'section_view']); // Section View
});
