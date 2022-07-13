<?php

use App\Http\Controllers\DepartmentHeadController;
use App\Http\Controllers\GeneralController\CourseSyllabusController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::prefix('teacher')->group(function () {
    Route::get('/', [TeacherController::class, 'subject_list'])->name('teacher.subject-list'); // Subject List view
    Route::get('/subjects', [TeacherController::class, 'subject_list'])->name('teacher.subject-list'); // Subject List View
    Route::get('/subjects/view', [TeacherController::class, 'subject_class_view'])->name('teacher.subject-view'); // Subject Content View
    Route::get('/subjects/student-list')->name('teacher.subject-class-students');
    Route::get('/subjects/semestral-clearance', [TeacherController::class, 'subject_clearance'])->name('teacher.semestral-clearance'); // Subject Clearance
    Route::get('/subjects/grading-sheet', [TeacherController::class, 'subject_grading_view'])->name('teacher.grading-sheet'); // Subject Grading Sheet View
    Route::get('/subjects/schedule-view', [TeacherController::class, 'schedule_view'])->name('teacher.schedule-view'); // Schedule View
    Route::post('/subjects/schedule-view/weekly-lesson-log', [TeacherController::class, 'subject_schedule_week_log_store'])->name('teacher.weekly-lesson-log'); // Schedule View
    //Route::get('/subjects/grading-sheet-frame', [TeacherController::class, 'subject_grading_main_view'])->name('teacher.grading-sheet-main'); // Subject Grading Sheet View
    # Subject Syllabus
    Route::get('/subjects/create-syllabus', [TeacherController::class, 'subject_create_syllabus'])->name('teacher.create-syllabus');
    Route::get('/subjects/select-syllabus', [TeacherController::class, 'subject_select_syllabus'])->name('teacher.select-syllabus');


    Route::post('/subjects/grade-submission', [TeacherController::class, 'subject_grade_submission']); // Subject Submission
    Route::get('/previous-subjects', [TeacherController::class, 'subject_view'])->name('teacher.previous-subjects'); // Previous Subjects Per Academic Year
    /* E-Clearance */
    Route::post('/subjects/e-clearance', [TeacherController::class, 'student_e_clearance'])->name('teacher.e-clearance');
    Route::get('/grading-sheet/store', [TeacherController::class, 'grade_store']); // Store a Score per Subjects Class and Students

    Route::get('/grade-reports', [TeacherController::class, 'submission_view'])->name('department-head.grade-submission'); // Grade Submission View
    Route::get('/grade-submission', [TeacherController::class, 'submission_view'])->name('department-head.grade-submission');
    Route::post('/grade-reports', [TeacherController::class, 'check_grade_submission']); // Review the Grading Sheet  
    Route::get('/grade-reports/instructor', [TeacherController::class, 'instructor_view']); // Instructor View
    Route::get('/grade-reports/subject', [TeacherController::class, 'subject_report_view']); // Grade Report Submission View
    Route::post('/subject-grade/bulk-upload', [TeacherController::class, 'subject_grade_bulk_upload'])->name('teacher.bulk-upload-grades'); // Bulk Upload of Grades

    Route::get('/semestral-clearance', [TeacherController::class, 'e_clearance_view'])->name('department.e-clearance'); // List of Clearance
    Route::get('/semestral-clearance/view', [TeacherController::class, 'section_view_e_clearance'])->name('department.e-clearance-view'); // List of Clearance


    # Course Syllabus
    Route::get('/course-syllabus', [CourseSyllabusController::class, 'course_syllabus_view'])->name('teacher.course-syllabus'); // View the List of Course Syllabus
    Route::get('/course-syllabus/create', [CourseSyllabusController::class, 'course_syllabus_create'])->name('teacher.course-syllabus-create');
    Route::post('/course-syllabus/store', [CourseSyllabusController::class, 'course_syllabus_store'])->name('teacher.course-syllabus-store');
    Route::get('/course-syllabus/editor', [CourseSyllabusController::class, 'course_syllabus_editor'])->name('teacher.course-syllabus-editor');
});
