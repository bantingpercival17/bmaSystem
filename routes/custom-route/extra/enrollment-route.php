<?php

use App\Http\Controllers\GeneralController\EnrollmentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/enrollment-view', [EnrollmentController::class, 'enrollment_view'])->name('enrollment.view');
    Route::get('/enrollment-list', [EnrollmentController::class, 'enrolled_list_view'])->name('enrollment.enrolled-list');
    Route::get('/enrollment-list/report', [EnrollmentController::class, 'course_enrolled_report'])->name('enrollment.enrolled-list-report');
    Route::get('/enrollment/payment-assessment', [EnrollmentController::class, 'enrollment_payment_assessment'])->name('enrollment.payment-assessment');
});
