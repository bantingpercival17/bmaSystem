<?php

use App\Http\Controllers\DeanController;
use Illuminate\Support\Facades\Route;

Route::prefix('dean')->group(function () {
    Route::get('/', [DeanController::class, 'dashboard'])->name('dean.grade-submission'); // Dashboard View
    Route::get('/grading-verification', [DeanController::class, 'dashboard'])->name('dean.grade-submission'); // Dashboard View
    Route::get('/grading-verification/view', [DeanController::class, 'grading_verification_view'])->name('dean.grade-verification-view');
    Route::get('/grading-verification/store', [DeanController::class, 'verify_grade_submission'])->name('dean.grade-verification');
    Route::get('/grading-verification/grading-sheet-view', [DeanController::class, 'grading_sheet_view'])->name('dean.grading-sheet-view');
    Route::get('/grading-verification/publish', [DeanController::class, 'publish_grade_submission'])->name('dean.grade-publish');
    Route::get('/e-clearance', [DeanController::class, 'e_clearance_view'])->name('dean.e-clearance');
    Route::get('/e-clearance/section-view', [DeanController::class, 'e_clearance_section_view'])->name('dean.clearance-section-view');
    Route::post('/e-clearance/section-view', [DeanController::class, 'e_clearance_section_store'])->name('dean.store-clearance-section');
});
