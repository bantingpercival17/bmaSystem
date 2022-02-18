<?php

use App\Http\Controllers\DeanController;
use Illuminate\Support\Facades\Route;

Route::prefix('dean')->group(function () {
    Route::get('/', [DeanController::class, 'dashboard'])->name('dean.grade-submission'); // Dashboard View
    Route::get('/grading-vefication', [DeanController::class, 'dashboard'])->name('dean.grade-submission'); // Dashboard View
    Route::get('/e-clearance', [DeanController::class, 'e_clearance_view'])->name('dean.e-clearance');
    Route::get('/e-clearance/section-view', [DeanController::class, 'e_clearance_section_view'])->name('dean.clearance-section-view');
    Route::post('/e-clearance/section-view', [DeanController::class, 'e_clearance_section_store'])->name('dean.store-clearance-section');
});
