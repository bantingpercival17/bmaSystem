<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountingController;

Route::prefix('accounting')->group(function () {
    /* Applicants */
    require __DIR__ . '/extra/applicant-route.php'; // Applicant Route
    require __DIR__ . '/extra/ticket-route.php'; // Ticket Route
    Route::get('/', [AccountingController::class, 'index'])->name('accounting.dashboard');
    Route::get('/dashboard', [AccountingController::class, 'index'])->name('accounting.dashboard'); // Dashboard
    Route::get('/dashboard/payment-pending', [AccountingController::class, 'payment_pending_view'])->name('accounting.dashboard-payment-assessment');
    Route::get('/dashboard/enrolled-list', [AccountingController::class, 'enrolled_list'])->name('accounting.course-enrolled');
    // Assessment
    Route::get('/assessment-fee', [AccountingController::class, 'assessment_view'])->name('accounting.assessments'); // Assessment View
    Route::post('/assessment-fee', [AccountingController::class, 'assessment_store'])->name('accounting.payment-assessment'); // Assessment Store

    //Payment 
    Route::get('/payment-transaction', [AccountingController::class, 'payment_view'])->name('accounting.payment-transactions');
    Route::post('/payment-transaction', [AccountingController::class, 'payment_store'])->name('accounting.payment-transaction');
    Route::post('/payments', [AccountingController::class], 'payment_store')->name('accounting.payment-store');
    Route::post('/payment-transaction/online-payment', [AccountingController::class, 'payment_verification'])->name('accounting.online-payment-disapproved');
    Route::get('/payment-transaction/online-payment/removed', [AccountingController::class, 'online_payment_transaction_removed'])->name('accounting.online-payment-transaction-remove');
    // Bridging Program
    Route::post('/payment-transaction/additional-payment', [AccountingController::class, 'payment_disapproved'])->name('accounting.online-additional-payment-disapproved');
    Route::post('/payment-transaction/additional-payment-approved', [AccountingController::class, 'payment_approved'])->name('accounting.online-additional-payment-approved');

    // Student Search
    Route::get('/student-search', [AccountingController::class, 'search_students']); // Search Student


    // Fees
    Route::get('/fees', [AccountingController::class, 'fee_view'])->name('accounting.fees');
    Route::get('/fees/course', [AccountingController::class, 'course_fee_view'])->name('accounting.course-fee-view');
    Route::get('/fees/course/create', [AccountingController::class, 'course_fee_create_view'])->name('accounting.create-course-fee');
    Route::post('/fees/course', [AccountingController::class, 'course_fee_store'])->name('accounting.course-fee-store');
    Route::get('/fees/course/view', [AccountingController::class, 'course_fee_view_list'])->name('accounting.course-fee-view-list');
    Route::post('/fees/course/change-fee', [AccountingController::class, 'course_change_fee'])->name('accounting.course-change-fee');
    Route::get('/fees/course/fee-remove', [AccountingController::class, 'course_fee_remove'])->name('accounting.course-fee-remove');
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


    // Semestral Clearance
    Route::get('/semestral-clearance', [AccountingController::class, 'semestral_clearance_view'])->name('accounting.semestral-clearance'); // Course View
    Route::get('/semestral-clearance/view', [AccountingController::class, 'semestral_student_list_view'])->name('accounting.semestral-student-list'); // Section view
    Route::post('/semestral-clearance', [AccountingController::class, 'semestral_clearance_store'])->name('accounting.semestral-clearance-store');

    // Applicant
    Route::get('/applicant-transaction', [AccountingController::class, 'applicant_transaction_view'])->name('accounting.applicant-transaction');
    Route::get('/applicant-transaction/verification', [AccountingController::class, 'applicant_transaction_verification'])->name('accounting.applicant-transaction-verification');
    Route::post('/applicant-transaction', [AccountingController::class, 'applicant_transaction_store'])->name('accounting.applicant-payment-transaction');

    // Report
    Route::get('/generate-report', [AccountingController::class, 'generate_report_view'])->name('accounting.report');
    Route::post('/generate-report/collection', [AccountingController::class, 'colletion_report'])->name('accounting.report-collection');
    Route::post('/generate-report/balance', [AccountingController::class, 'balance_report'])->name('accounting.report-balance');
    Route::post('/generate-report/monthly-report', [AccountingController::class, 'report_student_monthly_payment'])->name('accounting.monthly-payment-report');
    // Staff
    Route::get('/staff/payroll-view', [AccountingController::class, 'staff_payroll_view'])->name('accounting.payroll-view');
    Route::get('/staff/salary-details', [AccountingController::class, 'staff_salary_details'])->name('accounting.staff-salary');
    Route::get('/staff/salary-details-template', [AccountingController::class, 'staff_salary_details_template'])->name('accounting.salary-details-template');
    Route::post('/staff/employees-salay-details', [AccountingController::class, 'upload_salary_details'])->name('accounting.employees-upload-salary-details');
    Route::post('/staff/create-payroll', [AccountingController::class, 'payroll_store'])->name('accounting.employees-create-payroll');
    Route::get('/staff/generate-payroll', [AccountingController::class, 'payroll_view'])->name('accounting.generate-payroll');
    Route::post('/staff/payroll-report', [AccountingController::class, 'payroll_generated_report'])->name('accounting.payroll-generate');

    Route::get('/payment-transaction/print-receipt', [AccountingController::class, 'payment_print_receipt'])->name('accounting.print-reciept');

    Route::get('/payment-transaction/student-card', [AccountingController::class, 'student_card'])->name('accounting.student-card');

    Route::post('/payment-transaction/student-transaction-import', [AccountingController::class, 'student_transaction_import'])->name('accounting.student-transacion-import');
    Route::post('/payment-transaction/payment-void', [AccountingController::class, 'payment_transaction_void'])->name('accounting.transaction-void');
    Route::get('/payment-void-transaction', [AccountingController::class, 'void_view'])->name('accounting.payment-void');
    Route::post('/payment-void-transaction', [AccountingController::class, 'void_transaction'])->name('accounting.void-transaction');
});
