
<?php

use App\Http\Controllers\DepartmentHeadController;
use App\Http\Middleware\DepartmentHead;
use Illuminate\Support\Facades\Route;

Route::prefix('department-head')->group(function () {
    Route::get('/grade-submission', [DepartmentHeadController::class, 'submission_view'])->name('department-head.grade-submission');
    Route::get('/grade-submission/view',[DepartmentHeadController::class,'subject_submission_view'])->name('department-head.grade-submission-view');
    
    Route::get('/semestral-clearance', [DepartmentHeadController::class, 'e_clearance_view'])->name('department-head.e-clearance'); // List of Clearance
    Route::get('/semestral-clearance/view', [DepartmentHeadController::class, 'section_view_e_clearance'])->name('department-head.e-clearance-view'); // List of Clearance
    Route::post('/semestral-clearance/store', [DepartmentHeadController::class, 'store_student_clearance'])->name('department-head.store-e-clearance');
    Route::get('/semestral-clearance/uncleared', [DepartmentHeadController::class, 'update_student_clearance'])->name('department-head.uncleared-clearance');
    Route::get('/semestral-clearance/cleared', [DepartmentHeadController::class, 'save_student_clearance'])->name('department-head.cleared-clearance');
});
