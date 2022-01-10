<?php

use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::prefix('teacher')->group(function () {
    Route::get('/', [TeacherController::class, 'index'])->name('teacher.subject-list');
    Route::get('/subjects', [TeacherController::class, 'index'])->name('teacher.subject-list'); // Subject View
    Route::get('/subjects/grading-sheet', [TeacherController::class, 'subject_grading_view'])->name('teacher.grading-sheet'); // Subject Grading Sheet View
    Route::post('/subjects/grade-submission', [TeacherController::class, 'subject_grade_submission']); // Subject Submission
    Route::get('/previous-subjects', [TeacherController::class, 'subject_view'])->name('teacher.previous-subjects'); // Previous Subjects Per Academic Year

    Route::get('/grading-sheet/store', [TeacherController::class, 'grade_store']); // Store a Score per Subjects Class and Students

    Route::get('/grade-reports', [TeacherController::class, 'submission_view']); // Grade Submission View
    Route::post('/grade-reports', [TeacherController::class, 'check_grade_submission']); // Review the Grading Sheet  
    Route::get('/grade-reports/instructor', [TeacherController::class, 'instructor_view']); // Instructor View
    Route::get('/grade-reports/subject', [TeacherController::class, 'subject_report_view']); // Grade Report Submission View
    Route::post('/subject-grade/bulk-upload', [TeacherController::class, 'subject_grade_bulk_upload']); // Bulk Upload of Grades
});
