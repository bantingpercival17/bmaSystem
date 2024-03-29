<?php

use App\Http\Controllers\DepartmentHeadController;
use App\Http\Controllers\GeneralController\CourseSyllabusController;
use App\Http\Controllers\TeacherController;
use App\Http\Livewire\Teacher\CourseSyllabus\SubjectSyllabusView;
use App\Http\Livewire\Teacher\CourseSyllabus\SubjectTopicView;
use Illuminate\Support\Facades\Route;

Route::prefix('teacher')->middleware(['auth', 'teacher'])->group(function () {
    Route::get('/', [TeacherController::class, 'subject_list'])->name('teacher.subject-list'); // Subject List view
    Route::get('/subjects', [TeacherController::class, 'subject_list'])->name('teacher.subject-list'); // Subject List View
    Route::get('/subjects/view', [TeacherController::class, 'subject_class_view'])->name('teacher.subject-view'); // Subject Content View
    Route::get('/subjects/student-list', [TeacherController::class, 'subject_student_list'])->name('teacher.subject-class-students');
    Route::get('/subjects/semestral-clearance', [TeacherController::class, 'subject_clearance'])->name('teacher.semestral-clearance'); // Subject Clearance
    Route::get('/subjects/grading-sheet', [TeacherController::class, 'subject_grading_view'])->name('teacher.grading-sheet'); // Subject Grading Sheet View
    Route::get('/subjects/schedule-view', [TeacherController::class, 'schedule_view'])->name('teacher.schedule-view'); // Schedule View
    Route::post('/subjects/schedule-view/weekly-lesson-log', [TeacherController::class, 'subject_schedule_week_log_store'])->name('teacher.weekly-lesson-log'); // Schedule View
    //Route::get('/subjects/grading-sheet-frame', [TeacherController::class, 'subject_grading_main_view'])->name('teacher.grading-sheet-main'); // Subject Grading Sheet View
    # Subject Syllabus
    Route::get('/subjects/create-syllabus', [TeacherController::class, 'subject_create_syllabus'])->name('teacher.create-syllabus');
    Route::get('/subjects/select-syllabus', [TeacherController::class, 'subject_select_syllabus'])->name('teacher.select-syllabus');
    Route::get('/subjects/course-syllabus', [TeacherController::class, 'subject_course_syllabus'])->name('teacher.course-syllabus-add');

    Route::post('/subjects/grade-submission', [TeacherController::class, 'subject_grade_submission']); // Subject Submission
    Route::get('/previous-subjects', [TeacherController::class, 'subject_view'])->name('teacher.previous-subjects'); // Previous Subjects Per Academic Year
    /* E-Clearance */
    Route::post('/subjects/e-clearance', [TeacherController::class, 'student_e_clearance'])->name('teacher.e-clearance');
    Route::get('/grading-sheet/store', [TeacherController::class, 'grade_store']); // Store a Score per Subjects Class and Students

    Route::get('/subjects/grading-sheet/grade-reports', [TeacherController::class, 'subject_grading_view_report'])->name('teacher.grading-sheet-report'); // Grade Submission View
    Route::get('/grade-submission', [TeacherController::class, 'submission_view'])->name('department-head.grade-submission');
    Route::post('/grade-reports', [TeacherController::class, 'check_grade_submission']); // Review the Grading Sheet
    Route::get('/grade-reports/instructor', [TeacherController::class, 'instructor_view']); // Instructor View
    Route::get('/grade-reports/subject', [TeacherController::class, 'subject_report_view']); // Grade Report Submission View
    Route::post('/subject-grade/bulk-upload', [TeacherController::class, 'subject_grade_bulk_upload'])->name('teacher.bulk-upload-grades'); // Bulk Upload of Grades
    Route::get('/subject-grade/download-grade-template', [TeacherController::class, 'subject_grade_template'])->name('teacher.grade-template');
    Route::get('/subject-grade/download-grade-export', [TeacherController::class, 'subject_grade_export'])->name('teacher.export-grade');
    Route::get('/subject/grading-sheet-nstp', [TeacherController::class, 'subject_grading_sheet_nstp'])->name('teacher.grade-sheet-special');
    Route::get('/grading-sheet-nstp/store', [TeacherController::class, 'store_nstp_grade']);

    Route::get('/semestral-clearance', [TeacherController::class, 'e_clearance_view'])->name('department.e-clearance'); // List of Clearance
    Route::get('/semestral-clearance/view', [TeacherController::class, 'section_view_e_clearance'])->name('department.e-clearance-view'); // List of Clearance


    # Course Syllabus
    Route::get('/course-syllabus', [CourseSyllabusController::class, 'course_syllabus_view'])->name('teacher.course-syllabus'); // View the List of Course Syllabus
    Route::get('/course-syllabus/create', [CourseSyllabusController::class, 'course_syllabus_create'])->name('teacher.course-syllabus-create'); // Setup the Course Syllabus
    Route::post('/course-syllabus/store', [CourseSyllabusController::class, 'course_syllabus_store'])->name('teacher.course-syllabus-store'); // Save the Syllabus
    Route::get('/course-syllabus/editor', [CourseSyllabusController::class, 'course_syllabus_editor'])->name('teacher.course-syllabus-editor'); // View the Syllabus
    Route::get('/course-syllabus/remove', [CourseSyllabusController::class, 'course_syllabus_remove'])->name('teacher.course-syllabus-remove'); // Remove Syllabus
    Route::get('/course-syllabus/report', [CourseSyllabusController::class, 'course_syllabus_report'])->name('teacher.course-syllabus-report'); // Remove Syllabus

    // STCW Reference
    Route::post('/course-syllabus/editor/store-stcw-reference', [CourseSyllabusController::class, 'store_stcw_reference'])->name('teacher.store-stcw-reference'); // Store STCW Reference
    Route::get('/course-syllabus/editor/stcw-reference/remove', [CourseSyllabusController::class, 'remove_stcw_reference'])->name('teacher.stcw-reference-remove'); // Remove STCW Table
    Route::post('/course-syllabus/editor/stcw-reference/add', [CourseSyllabusController::class, 'add_stcw_reference'])->name('teacher.stcw-reference-add'); // Add STCW REFERENCE
    // COURSE OUTCOME
    Route::post('/course-syllabus/editor/store-course-outcome', [CourseSyllabusController::class, 'store_course_outcome'])->name('teacher.course-outcome-store');
    Route::post('/course-syllabus/editor/store-course-details', [CourseSyllabusController::class, 'store_course_details'])->name('teacher.course-details-store');
    // LEARNING OUTCOME
    Route::post('/course-syllabus/editor/store-course-learning-outcome', [CourseSyllabusController::class, 'store_course_learning_outline'])->name('teacher.syllabus-learning-outcome');
    Route::get('/course-syllabus/editor/remove-course-learning-outcome', [CourseSyllabusController::class, 'remove_course_learning_outline'])->name('teacher.syllabus-learning-outcome-remove');
    Route::post('/course-syllabus/editor/sub-topic/store', [CourseSyllabusController::class, 'store_sub_topic'])->name('teacher.sub-topic-store');
    Route::post('/course-syllabus/editor/sub-topic-learning-outcome/store', [CourseSyllabusController::class, 'store_sub_topic_learning_outcome'])->name('teacher.sub-topic-learning-outcome-store');
    // LEARNING TOPIC MATERIALS
    Route::post('/course-syllabus/editor/store-course-topic-materials', [CourseSyllabusController::class, 'learning_topic_materials'])->name('teacher.topic-materials'); // Add Topic Materials
    Route::get('/course-syllabus/preview', [CourseSyllabusController::class, 'learning_topic_preview'])->name('teacher.learning-topic-preview');
    Route::get('/course-syllabus/preview/topic', [CourseSyllabusController::class, 'topic_view'])->name('teacher.course-syllabus-topic-view');

    // Livewire
    Route::get('/course-syllabus/view', SubjectSyllabusView::class)->name('teacher.course-syllabus-view');
    Route::get('/course-syllabus/topic/view', SubjectTopicView::class)->name('teacher.course-syllabus-topic-view-v2');
});
