<?php

use App\Http\Controllers\RegistrarController;
use Illuminate\Support\Facades\Route;

Route::prefix('registrar')->group(function () {
    // Dashboard
    Route::get('/', [RegistrarController::class, 'index'])->name('registrar.dashboard');
    Route::get('/dashboard', [RegistrarController::class, 'index'])->name('registrar.dashboard');

    Route::get('/dashboard/payment-assessment', [RegistrarController::class, 'dashboard_payment_assessment'])->name('registrar.dashboard-payment-assessment');
    Route::get('/dashboard/student-clearance-list', [RegistrarController::class, 'dashboard_student_clearance_list'])->name('registrar.dashboard-student-clearance-list');
    // Applicants

    // Subjects
    Route::get('/subjects', [RegistrarController::class, 'subject_view'])->name('registrar.subject-view'); // Subject Views
    Route::get('/subjects/classes', [RegistrarController::class, 'classes_view'])->name('registrar.course-subject-view'); // Subject Classes View
    Route::post('/subjects/classes', [RegistrarController::class, 'classes_store'])->name('registrar.classes-handled'); // Store Subjects Classes Handled
    Route::get('/subjects/classes/subject-handle', [RegistrarController::class, 'classes_subject_handle'])->name('registrar.course-subject-handle-view');
    Route::post('/subjects.classes/subject-handle', [RegistrarController::class, 'classes_schedule'])->name('registrar.class-schedule');
    Route::get('/subjects.classes/subject-handle', [RegistrarController::class, 'classes_schedule_removed'])->name('registrar.class-schedule-remove');
    Route::get('/subjects/classes/removed', [RegistrarController::class, 'classes_removed'])->name('registrar.subject-class-removed'); // Remove Subjects Clases Handled
    Route::get('/subjects/classes/schedule-template', [RegistrarController::class, 'class_schedule_template'])->name('registrar.subject-schedule-template');
    Route::post('/subjects/classes/schedule-upload', [RegistrarController::class, 'class_schedule_upload'])->name('registrar.subject-schedule-upload');
    Route::get('/subjects/curriculum', [RegistrarController::class, 'curriculum_view'])->name('registrar.curriculum-view'); // Curriculum Subject View
    Route::post('/subjects/curriculum', [RegistrarController::class, 'curriculum_subject_store'])->name('registrar.curriculum-store'); // Store Curriculum Subject
    Route::get('/subjects/curriculum/subject', [RegistrarController::class, 'curriculum_subject_remove'])->name('registrar.remove-curriculum-subject'); // Remove Curriculum Subject
    Route::get('/subjects/curriculum/view', [RegistrarController::class, 'curriculum_subject_view'])->name('registrar.view-curriculum-subject'); // Remove Curriculum Subject
    Route::post('/subjects/curriculum/update', [RegistrarController::class, 'curriculum_subject_update'])->name('registrar.update-curriculum-subject'); // Store Curriculum Subject

    // Enrollment
    Route::get('/enrollment', [RegistrarController::class, 'enrollment_view'])->name('registrar.enrollment');
    Route::get('/enrollment/enrolled-list', [RegistrarController::class, 'enrolled_list_view'])->name('registrar.course-enrolled');
    Route::get('/enrollment/student-clearance', [RegistrarController::class, 'student_clearance'])->name('registrar.student-clearance');
    Route::post('/enrollment/assessment', [RegistrarController::class, 'enrollment_assessment'])->name('registrar.enrollment-assessment');
    Route::get('/enrollment/bridging-program', [RegistrarController::class, 'enrollment_briding_program'])->name('registrar.bridging-program');

    // Student Profile
    Route::get('/student-profile', [RegistrarController::class, 'student_list_view'])->name('registrar.students'); // Student List View
    Route::get('/student-profile/view', [RegistrarController::class, 'student_profile_view'])->name('registrar.student-profile');
    Route::get('/student-profile/student-information-report', [RegistrarController::class, 'student_information_report'])->name('registrar.student-information-report');
    Route::get('/student-profile/student-application-report', [RegistrarController::class, 'student_application_report'])->name('registrar.student-application-view');
    // Section 
    Route::get('/sections', [RegistrarController::class, 'section_view'])->name('registrar.section-view'); // Section View
    Route::post('/sections', [RegistrarController::class, 'section_store'])->name('registrar.section-store'); // Section Store
    Route::get('/sections/view', [RegistrarController::class, 'section_add_student_view'])->name('registrar.section-add-student-view');
    Route::get('/sections/view/add', [RegistrarController::class, 'section_add_student'])->name('registrar.section-add-student');
    Route::get('/section/view/store', [RegistrarController::class, 'section_store_student'])->name('registrar.section-store-student');
    Route::get('/section/view/remove', [RegistrarController::class, 'section_remove_student'])->name('registrar.student-section-remove');
    Route::get('/section/export-file', [RegistrarController::class, 'section_export_file'])->name('registrar.section-export');
    // E-clearance
    Route::get('/semestral-clearance', [RegistrarController::class, 'clearance_view'])->name('registrar.semestral-clearance');
    Route::get('/semestral-clearance/view', [RegistrarController::class, 'semestral_student_list_view'])->name('registrar.semestral-student-list'); // Section view
    Route::post('/semestral-clearance', [RegistrarController::class, 'clearance_store'])->name('registrar.semestral-clearance-store');
    Route::get('/semestral-clearance/report', [RegistrarController::class, 'semestral_clearance_report'])->name('registrar.semestral-clearance-report');

    // Semestral Grades
    Route::get('/semestral-grade', [RegistrarController::class, 'semestral_grade_view'])->name('registrar.semestral-grades');
    Route::get('/semestral-grade/view', [RegistrarController::class, 'semestral_grade_section_view'])->name('registrar.semestral-grade-view');
    Route::get('/semestral-grade/report-form', [RegistrarController::class, 'semestral_grade_report_form'])->name('registrar.semestral-grade-form-ad2');
    Route::get('/semestral-grade/report-summary', [RegistrarController::class, 'semestral_grade_summary_report'])->name('registrar.semestral-grade-semestral-report');
    Route::get('/smestral-grade/publish-grade', [RegistrarController::class, 'semestral_grade_publish'])->name('registrar.semestral-grade-publish');
    Route::get('/semstral-grade/subject-grade', [RegistrarController::class, 'semestral_subject_grade'])->name('registrar.subject-grade');
    Route::get('/semstral-grade/subject-grade/export-excel', [RegistrarController::class, 'summary_grade_report_excel'])->name('registrar.subject-grade-export');
    /* Applicants */
    //require __DIR__ . '\extra\applicant-route.php'; // Applicant Route

    // Applicant 
    //Route::get('/applicant');
    require __DIR__ . '/extra/applicant-route.php'; // Applicant Route
    require __DIR__ . '/extra/ticket-route.php'; // Applicant Route
    require __DIR__ . '/extra/enrollment-route.php'; // Enrollment Route
});
