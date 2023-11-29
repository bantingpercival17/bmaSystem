<?php

use App\Http\Controllers\DeanController;
use App\Http\Livewire\Dean\GradeSubmissionView;
use Illuminate\Support\Facades\Route;

Route::prefix('dean')->middleware(['auth', 'dean'])->group(function () {
    Route::get('/', [DeanController::class, 'dashboard'])->name('dean.grade-submission'); // Dashboard View
    Route::get('/grading-verification', [DeanController::class, 'dashboard'])->name('dean.grade-submission'); // Dashboard View
    Route::get('/grading-verification/view', [DeanController::class, 'grading_verification_view'])->name('dean.grade-verification-view');
    Route::get('/grading-verification/store', [DeanController::class, 'verify_grade_submission'])->name('dean.grade-verification');
    Route::get('/grading-verification/grading-sheet-view', [DeanController::class, 'grading_sheet_view'])->name('dean.grading-sheet-view');
    Route::get('/grading-verification/publish', [DeanController::class, 'publish_grade_submission'])->name('dean.grade-publish');
    Route::get('/grade-submission-view', GradeSubmissionView::class)->name('dean.grade-submission-v2');
    Route::get('/grade-submission-view/preview-report', [DeanController::class, 'suject_grade_report_view'])->name('dean.grade-preview-report');
    Route::get('/grade-submission-view/export-grade-formad01/{data}', [DeanController::class, 'section_export_grade_ad01'])->name('dean.export-form-ad01');
    Route::get('/grade-submission-view/export-grade-formad02/{data}', [DeanController::class, 'section_export_grade_ad02'])->name('dean.export-form-ad02');
    Route::get('/e-clearance', [DeanController::class, 'e_clearance_view'])->name('dean.e-clearance');
    Route::get('/e-clearance/section-view', [DeanController::class, 'e_clearance_section_view'])->name('dean.clearance-section-view');
    Route::post('/e-clearance/section-view', [DeanController::class, 'e_clearance_section_store'])->name('dean.store-clearance-section');
});
