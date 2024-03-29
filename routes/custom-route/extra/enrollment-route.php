<?php

use App\Http\Controllers\GeneralController\EnrollmentController;
use App\Http\Livewire\Registrar\Enrollment\DropStudentView;
use App\Http\Livewire\Registrar\Enrollment\EnrolledStudentView;
use App\Http\Livewire\Registrar\EnrollmentView;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/enrollment-view', [EnrollmentController::class, 'enrollment_view'])->name('enrollment.view');
    Route::get('/enrollment-list', [EnrollmentController::class, 'enrolled_list_view'])->name('enrollment.enrolled-list');
    Route::get('/enrollment-list/report', [EnrollmentController::class, 'course_enrolled_report'])->name('enrollment.enrolled-list-report');
    Route::get('/enrollment/payment-assessment', [EnrollmentController::class, 'enrollment_payment_assessment'])->name('enrollment.payment-assessment');

    Route::get('/enrollment/category', [EnrollmentController::class, 'enrollment_category'])->name('enrollment.status');

    Route::get('/enrollment/category/status-report')->name('enrollment.category-report');

    Route::get('/enrollment/student-number', [EnrollmentController::class, 'enrollment_student_number'])->name('enrollment.student-number');
    Route::get('/enrollment/semestral-list', [EnrollmentController::class, 'enrollment_semestral_list'])->name('enrollment.semestarl-enrollment-list');

    Route::get('/enrollment/report/drop-list', [EnrollmentController::class, 'enrollment_cancellation_report'])->name('enrollment.withdrawn-and-drop-report');
    // Livewire Route
    Route::get('/enrollment/v2', EnrollmentView::class)->name('enrollment.view-v2');
    Route::get('/enrollment/enrolled-student-list', EnrolledStudentView::class)->name('enrollment.enrolled-student-list');
    Route::get('/enrollment/withdrawn-drop-list', DropStudentView::class)->name('enrollment.withdrawn-list');
});
