<?php

use App\Exports\EmployeeListExport;
use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\GeneralController\ApplicantController;
use App\Http\Controllers\GeneralController\EnrollmentController;
use App\Http\Controllers\PaymongoApi;
use App\Http\Livewire\Employee\AttendanceView;
use App\Http\Middleware\Administrator;
use App\Models\StudentDetails;
use App\Report\Students\StudentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/dashboard', [AdministratorController::class, 'index'])->name('admin.dashboard'); // Dashboard
Route::prefix('administrator')->middleware(['auth', 'administrator'])->group(function () {
  /* Applicants */
  require __DIR__ . '/extra/applicant-route.php'; // Applicant Route
  require __DIR__ . '/extra/ticket-route.php'; // Ticket Route
  require __DIR__ . '/extra/enrollment-route.php'; // Enrollment Route

  require __DIR__ . '/extra/livewire-route.php'; // Livewire Route
  //  Route::get('/enrollment-list', [EnrollmentController::class, 'enrolled_list_view'])->name('enrollment.enrolled-list');
  Route::get('/', [AdministratorController::class, 'index'])->name('admin.dashboard');
  Route::get('/dashboard', [AdministratorController::class, 'index'])->name('admin.dashboard'); // Dashboard
  Route::get('/enrollment/enrolled-list', [AdministratorController::class, 'dashboard_enrolled_list_view'])->name('admin.course-enrolled');
  Route::get('/enrollment/enrolled-list/report', [AdministratorController::class, 'course_enrolled_report'])->name('admin.course-enrolled-report');

  /* Students */
  Route::get('/students', [AdministratorController::class, 'student_view'])->name('admin.students'); // View Students
  Route::get('/students/view', [AdministratorController::class, 'student_profile'])->name('admin.student-profile');
  Route::post('/students/imports', [AdministratorController::class, 'student_imports']); // Import Student Details
  Route::get('/students/reset-password', [AdministratorController::class, 'student_reset_password'])->name('admin.student-reset-password');
  Route::post('/students/store-student-account', [AdministratorController::class, 'student_account'])->name('admin.store-student-account');
  /* Accounts */
  Route::get('/accounts', [AdministratorController::class, 'account_view'])->name('admin.accounts'); // View Accounts
  Route::post('/accounts', [AdministratorController::class, 'account_store']); // Store Account
  Route::post('/accounts/profile-picture', [AdministratorController::class, 'account_upload_profile']);
  Route::post('/accounts/role', [AdministratorController::class, 'account_roles_store']);
  Route::get('/accounts/deactive', [AdministratorController::class, 'deactive_account'])->name('admin.deactive-account');
  /* Subject and Curriculum Routes */
  Route::get('/subjects', [AdministratorController::class, 'subject_view'])->name('admin.subjects'); // Subject Curriculum
  Route::post('/curriculum', [AdministratorController::class, 'curriculum_store']); // Store a Curriculum
  Route::get('/subjects/curriculum', [AdministratorController::class, 'curriculum_view']); // Curriculum and Department View
  Route::post('/subjects', [AdministratorController::class, 'subject_store']); // Store New Subjects
  Route::get('/subjects/class', [AdministratorController::class, 'subject_class']); // Subject Class View
  Route::post('/subjects/class', [AdministratorController::class, 'subject_class_store']); // Store Subject Class
  Route::get('/subjects/class/removed', [AdministratorController::class, 'subject_class_remove']); // Removed Subject Class
  Route::post('/subjects-handle', [AdministratorController::class, 'subject_import']); // Import Subject Handle
  /* Subject and Curriculum Routes */
  /* Classes and Sections */
  Route::get('/classes', [AdministratorController::class, 'classes_view'])->name('admin.sections'); // Classes View
  Route::post('/classes', [AdministratorController::class, 'classes_store']); // Store Section
  Route::get('/classes/section', [AdministratorController::class, 'class_section_view']); // Section View
  Route::get('/classes/section/add', [AdministratorController::class, 'section_add']); // Section Add
  Route::get('/classes/section/remove', [AdministratorController::class, 'section_remove']); // Section Add
  Route::post('/classes/student-section-import', [AdministratorController::class, 'section_import']); // Batch Upload Section
  Route::get('/classes/section/report', [AdministratorController::class, 'section_report_list']); // List of Student Per Section
  /* Classes and Sections */
  /* Enrollment */
  Route::get('/semestral-clearance', [AdministratorController::class, 'clearance_view'])->name('admin.semestral-clearance'); // Clearance View
  Route::post('/semestral-clearance', [AdministratorController::class, 'clearance_store'])->name('admin.semestral-clearance-store'); // Enrollment View
  Route::get('/semestral-clearance/view', [AdministratorController::class, 'semestral_student_list_view'])->name('admin.semestral-student-list'); // Section view

  // Paymongo 
  Route::get('/paymongo', [PaymongoApi::class, 'view']); // Enrollment View

  // QR Code
  Route::get('/qr-code/{data}', [AdministratorController::class, 'qr_generator']);

  // Attendance
  Route::get('/attendance', AttendanceView::class)->name('admin.attendance'); // Attendance View
  Route::get('/attendance/report', [AdministratorController::class, 'attendance_report']); // Attendance Report

  // Employee
  Route::get('/accounts/view', [AdministratorController::class, 'employee_profile'])->name('admin.employee-view');
  Route::post('/accounts/reset-password', [AdministratorController::class, 'employee_reset_password'])->name('admin.reset-password');
  Route::get('/accounts/generate-qrcode', [AdministratorController::class, 'employee_generate_qrcode'])->name('admin.staff-qrcode');
  /* Setting */
  Route::get('/setting', [AdministratorController::class, 'setting_view'])->name('admin.setting');
  Route::post('/setting/store-role', [AdministratorController::class, 'store_role'])->name('setting.store-role');
  Route::post('/setting/store-academic', [AdministratorController::class, 'store_academic'])->name('setting.store-academic');
  Route::get('/settong/student-handbook-logs', [AdministratorController::class, 'student_handbook_logs'])->name('admin.student-handbook-logs');
  Route::post('/setting/store-documents', [AdministratorController::class, 'store_documents'])->name('admin.store-documents');
  /* Examination */
  Route::get('/examination', [AdministratorController::class, 'examination_view'])->name('admin.examination');
  Route::post('/examination', [AdministratorController::class, 'examination_store'])->name('admin.examination');
  Route::post('/examination/import', [AdministratorController::class, 'examination_import'])->name('admin.import-examination');
  Route::get('/examination/category', [AdministratorController::class, 'examination_category_view'])->name('admin.examination-category');


  // Student Account 
  Route::get('/students/account-details', [AdministratorController::class, 'student_account_details'])->name('admin.student-account-details');
  Route::get('/student/qr-code', function (Request $_request) {
    $_report = new StudentReport();
    $_student = StudentDetails::find(base64_decode($_request->_student));
    return $_report->student_qr_code($_student);
  })->name('admin.student-qrcode');
  Route::get('employee/export', function () {
    $_file_export = new EmployeeListExport();
    $_respond =  Excel::download($_file_export, 'employee' . '.xlsx', \Maatwebsite\Excel\Excel::XLSX); // Download the File 
    ob_end_clean();
    return $_respond;
  })->name('export-employee');
  Route::get('/students/gadgets', [AdministratorController::class, 'student_gadgets_view'])->name('admin.student-gadget-view');
  Route::post('/student/gadgets', [AdministratorController::class, 'gadget_upload_file'])->name('admin.student-gadget-import');
  Route::get('/students/gadget-finder', [AdministratorController::class, 'gadget_finder'])->name('admin.student-gadget-finder');


  Route::get('/task-request', [AdministratorController::class, 'request_task_view'])->name('admin.request-task');
  Route::post('/task-request/add', [AdministratorController::class, 'request_task_store'])->name('admin.revision-task');
  Route::get('/task-request/approved', [AdministratorController::class, 'request_task_approved'])->name('admin.revision-approved');
  Route::get('/section-qrcode', [AdministratorController::class, 'generateQrcodeBySection'])->name('generate-qrcode-section');
});
