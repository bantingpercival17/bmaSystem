<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountingController;

Route::prefix('accounting')->group(function () {
    Route::get('/', [AccountingController::class, 'index'])->name('accounting.dashboard');
    Route::get('/dashboard', [AccountingController::class, 'index'])->name('accounting.dashboard'); // Dashboard
    // Assessment
    Route::get('/assessment-fee', [AccountingController::class, 'assessment_view']); // Assessment View
    // Student Search
    Route::get('/student-search', [AccountingController::class, 'search_students']); // Search Student


    // Fees
    Route::get('/fees')->name('accounting.fees');
    // Paymongo 
    // Create Payment Method
    Route::get('/paymongo-testing', [AccountingController::class, 'payment_testing']); // Paymongo Testing
    // Get Payment Method
    Route::get('/paymongo-get', [AccountingController::class, 'payment_view']); // 

});
