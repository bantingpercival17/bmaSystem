<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountingController;

Route::prefix('accounting')->group(function () {
    Route::get('/', [AccountingController::class, 'index'])->name('accounting.dashboard');
    Route::get('/dashboard', [AccountingController::class, 'index'])->name('accounting.dashboard'); // Dashboard
    // Assessment
    Route::get('/assessment-fee', [AccountingController::class, 'assessment_view'])->name('accounting.assessments'); // Assessment View
    Route::post('/assessment-fee', [AccountingController::class, 'assessment_store'])->name('accounting.payment-assessment'); // Assessment Store

    //Payment 
    Route::get('/payments', [AccountingController::class, 'payment_view'])->name('accounting.payment-view');
    Route::post('/payments', [AccountingController::class], 'payment_store')->name('accounting.payment-store');
    // Student Search
    Route::get('/student-search', [AccountingController::class, 'search_students']); // Search Student


    // Fees
    Route::get('/fees', [AccountingController::class, 'fee_view'])->name('accounting.fees');
    Route::get('/fees/course', [AccountingController::class, 'course_fee_view'])->name('accounting.course-fee-view');
    Route::get('/fees/course/create', [AccountingController::class, 'course_fee_create_view'])->name('accounting.create-course-fee');
    Route::post('/fees/course', [AccountingController::class, 'course_fee_store'])->name('accounting.course-fee-store');
    // Particulars 
    Route::get('/particular', [AccountingController::class, 'particular_view'])->name('accounting.particulars');
    Route::post('/particular/store', [AccountingController::class, 'particular_store'])->name('accounting.create-particular');
    Route::get('/particular/fee', [AccountingController::class, 'particular_fee_view'])->name('accounting.particular-fee-view');
    Route::post('/particular/fee', [AccountingController::class, 'particular_fee_store'])->name('accounting.particular-fee-store');
    // Paymongo 
    // Create Payment Method
    Route::get('/paymongo-testing', [AccountingController::class, 'payment_testing']); // Paymongo Testing
    // Get Payment Method
    Route::get('/paymongo-get', [AccountingController::class, 'payment_view']); // 

});
