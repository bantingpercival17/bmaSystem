<?php

use App\Http\Controllers\LibrarianController;
use Illuminate\Support\Facades\Route;

Route::prefix('librarian')->group(function () {

    // Student 
    Route::get('/student-attendance')->name('exo.student-attendance');
    Route::get('/semestral-clearance', [LibrarianController::class, 'semestral_clearance_view'])->name('librarian.semestral-clearance'); // Course View
    Route::get('/semestral-clearance/view', [LibrarianController::class, 'semestral_student_list_view'])->name('librarian.semestral-student-list'); // Section view
    Route::post('/semestral-clearance', [LibrarianController::class, 'semestral_clearance_store'])->name('librarian.semestral-clearance-store');
});
