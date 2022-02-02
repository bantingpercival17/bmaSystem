<?php

use App\Http\Controllers\RegistrarController;
use Illuminate\Support\Facades\Route;

Route::prefix('registrar')->group(function () {
    // Dashboard
    Route::get('/', [RegistrarController::class, 'index'])->name('registrar.dashboard');

    Route::get('/dashboard', [RegistrarController::class, 'index'])->name('registrar.dashboard');
    // Applicants

    // Subjects
    Route::get('/subjects', [RegistrarController::class, 'subject_view'])->name('registrar.subject-view'); // Subject Views
    Route::get('/subjects/classes', [RegistrarController::class, 'classes_view'])->name('registrar.course-subject-view'); // Subject Classes View
    Route::post('/subjects/classes', [RegistrarController::class, 'classes_store']); // Store Subjects Classes Handled
    Route::get('/subjects/classes/removed', [RegistrarController::class, 'classes_removed']); // Remove Subjects Clases Handled
    Route::get('/subjects/curriculum', [RegistrarController::class, 'curriculum_view'])->name('registrar.curriculum-view'); // Curriculum Subject View
    Route::post('/subjects/curriculum', [RegistrarController::class, 'curriculum_subject_store'])->name('registrar.curriculum-store'); // Store Curriculum Subject

    // Enrollment
    Route::get('/enrollment', [RegistrarController::class, 'enrollment_view'])->name('registrar.enrollment');
    Route::get('/enrollment/enrolled-list', [RegistrarController::class, 'enrolled_list_view'])->name('registrar.course-enrolled');
    Route::get('/enrollment/student-clearance', [RegistrarController::class, 'student_clearance'])->name('registrar.student-clearance');
    Route::post('/enrollment/assessment', [RegistrarController::class, 'enrollment_assessment'])->name('registrar.enrollment-assessment');
    // Student Profile
    Route::get('/student-profile', [RegistrarController::class, 'student_list_view'])->name('registrar.students'); // Student List View
    Route::get('/student-profile/view', [RegistrarController::class, 'student_profile_view'])->name('registrar.student-profile');
    Route::get('/student-profile/student-information-report', [RegistrarController::class, 'student_information_report'])->name('registrar.student-information-report');

    // Section 
    Route::get('/sections', [RegistrarController::class, 'section_view'])->name('registrar.section-view'); // Section View

    // E-clearance
    Route::get('/semestral-clearance', [RegistrarController::class, 'clearance_view'])->name('registrar.semestral-clearance');
    Route::get('/semestral-clearance/view', [RegistrarController::class, 'semestral_student_list_view'])->name('registrar.semestral-student-list'); // Section view
    Route::post('/semestral-clearance', [RegistrarController::class, 'clearance_store'])->name('registrar.semestral-clearance-store');
});
