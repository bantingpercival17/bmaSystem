<?php

use App\Http\Controllers\RegistrarController;
use App\Http\Livewire\Registrar\DashboardView;
use App\Http\Livewire\Registrar\ScholarshipGrantView;
use App\Http\Livewire\Registrar\Subjects\CurriculumSubject;
use App\Http\Livewire\Registrar\Subjects\SubjectHandle\SubjectHandleView;
use App\Http\Livewire\Registrar\Subjects\SubjectHandle\TeacherView;
use App\Models\CourseOffer;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

Route::prefix('registrar')->group(function () {
    // Dashboard
    Route::get('/', DashboardView::class)->name('registrar.dashboard');
    Route::get('/dashboard', DashboardView::class)->name('registrar.dashboard');
    /* Route::get('/', [RegistrarController::class, 'index'])->name('registrar.dashboard');
    Route::get('/dashboard', [RegistrarController::class, 'index'])->name('registrar.dashboard');
 */
    Route::get('/dashboard/payment-assessment', [RegistrarController::class, 'dashboard_payment_assessment'])->name('registrar.dashboard-payment-assessment');
    Route::get('/dashboard/student-clearance-list', [RegistrarController::class, 'dashboard_student_clearance_list'])->name('registrar.dashboard-student-clearance-list');
    // Applicants

    // Subjects
    Route::get('/subjects', [RegistrarController::class, 'subject_view'])->name('registrar.subject-view'); // Subject Views
    Route::get('/subjects/classes', [RegistrarController::class, 'classes_view'])->name('registrar.course-subject-view'); // Subject Classes View
    Route::post('/subjects/classes', [RegistrarController::class, 'classes_store'])->name('registrar.classes-handled'); // Store Subjects Classes Handled
    Route::get('/subjects/classes/subject-handle', [RegistrarController::class, 'classes_subject_handle'])->name('registrar.course-subject-handle-view');
    Route::post('/subjects.classes/subject-handle', [RegistrarController::class, 'classes_schedule'])->name('registrar.class-schedule');
    Route::post('/subject-classes/subject-handle/edit', [RegistrarController::class, 'classes_update'])->name('registrar.subject-class-update');
    Route::get('/subjects.classes/subject-handle', [RegistrarController::class, 'classes_schedule_removed'])->name('registrar.class-schedule-remove');
    Route::get('/subjects/classes/removed', [RegistrarController::class, 'classes_removed'])->name('registrar.subject-class-removed'); // Remove Subjects Clases Handled
    Route::get('/subjects/classes/schedule-template', [RegistrarController::class, 'class_schedule_template'])->name('registrar.subject-schedule-template');
    Route::post('/subjects/classes/schedule-upload', [RegistrarController::class, 'class_schedule_upload'])->name('registrar.subject-schedule-upload');
    Route::get('/subjects/curriculum', [RegistrarController::class, 'curriculum_view'])->name('registrar.curriculum-view'); // Curriculum Subject View
    Route::post('/subjects/curriculum', [RegistrarController::class, 'curriculum_subject_store'])->name('registrar.curriculum-store'); // Store Curriculum Subject
    Route::get('/subjects/curriculum/subject', [RegistrarController::class, 'curriculum_subject_remove'])->name('registrar.remove-curriculum-subject'); // Remove Curriculum Subject
    Route::get('/subjects/curriculum/view', [RegistrarController::class, 'curriculum_subject_view'])->name('registrar.view-curriculum-subject'); // Remove Curriculum Subject
    Route::post('/subjects/curriculum/update', [RegistrarController::class, 'curriculum_subject_update'])->name('registrar.update-curriculum-subject'); // Store Curriculum Subject
    /* Livewire - Subjects */
    Route::get('/subjects/v2', CurriculumSubject::class)->name('regsitrar.course-curriculum-subject');
    Route::get('/subjects/classes/v2', SubjectHandleView::class)->name('registrar.course-subject-view-v2');
    Route::get('subjects/teaching-loads', TeacherView::class)->name('registrar.teacher-subject-loads');

    // Enrollment
    Route::get('/enrollment', [RegistrarController::class, 'enrollment_view'])->name('registrar.enrollment');
    Route::get('/enrollment/enrolled-list', [RegistrarController::class, 'enrolled_list_view'])->name('registrar.course-enrolled');
    Route::get('/enrollment/student-clearance', [RegistrarController::class, 'student_clearance'])->name('registrar.student-clearance');
    Route::post('/enrollment/assessment', [RegistrarController::class, 'enrollment_assessment'])->name('registrar.enrollment-assessment');
    Route::get('/enrollment/bridging-program', [RegistrarController::class, 'enrollment_briding_program'])->name('registrar.bridging-program');
    Route::post('/enrollment/cancellation', [RegistrarController::class, 'enrollment_cancellation'])->name('registrar.enrollment_cancellation');
    // Student Profile
    Route::get('/student-profile', [RegistrarController::class, 'student_profile_view'])->name('registrar.students'); // Student List View
    Route::get('/student-profile/view', [RegistrarController::class, 'student_profile_view'])->name('registrar.student-profile');
    Route::get('/student-profile/student-information-report', [RegistrarController::class, 'student_information_report'])->name('registrar.student-information-report');
    Route::get('/student-profile/student-application-report', [RegistrarController::class, 'student_application_report'])->name('registrar.student-application-view');
    // Section
    Route::get('/sections', [RegistrarController::class, 'section_view'])->name('registrar.section-view'); // Section View
    Route::post('/sections', [RegistrarController::class, 'section_store'])->name('registrar.section-store'); // Section Store
    Route::get('/sections/view', [RegistrarController::class, 'section_add_student_view'])->name('registrar.section-add-student-view');
    Route::get('/sections/view/add', [RegistrarController::class, 'section_add_student'])->name('registrar.section-add-student');
    Route::get('/section/view/store', [RegistrarController::class, 'section_store_student'])->name('registrar.section-store-student');
    Route::get('/section/view/remove', [RegistrarController::class, 'section_remove_student'])->name('registrar.student-section-remove');
    Route::get('/section/export-file', [RegistrarController::class, 'section_export_file'])->name('registrar.section-export');
    Route::post('/section/import-file', [RegistrarController::class, 'section_import_files'])->name('registrar.section-import');
    Route::get('/section/export-qrcode', function (Request $_request) {
        $_course = CourseOffer::find(base64_decode($_request->_course));
        $_year = Auth::user()->staff->convert_year_level($_request->_year_level);
        $_file_name = $_course->course_code . "_" . strtoupper($_year) . "_" . Auth::user()->staff->current_academic()->school_year . '_' . strtoupper(str_replace(' ', '_', Auth::user()->staff->current_academic()->semester));
        // $_file_export = new CourseSectionStudentList($_course, $_request->_year_level);
        $_data_sheet = $_course->section([Auth::user()->staff->current_academic()->id, $_year])->get();
        foreach ($_data_sheet as $key => $section) {
            $_datas = $section->student_sections;
            foreach ($_datas as $key => $_data) {
                if ($_data->student->account) {
                    $_student_number = $_data->student->account->student_number;
                    /*  $image = QrCode::format('png')
                        // ->merge('img/t.jpg', 0.1, true)
                        ->size(200)->errorCorrection('H')
                        ->generate($_student_number . "." . mb_strtolower(str_replace(' ', '', $_data->student->last_name)));
                    $output_file = '/student/qr-code/' . $this->section->section_name . '/' . $_student_number . '.png'; */
                    //Storage::disk('local')->put($output_file, $image);
                    echo "'" . $_student_number . "." . mb_strtolower(str_replace(' ', '', $_data->student->last_name)) . "',";
                }
            }
        }
    })->name('registrar.section-export-qrcode');
    // E-clearance
    Route::get('/semestral-clearance', [RegistrarController::class, 'clearance_view'])->name('registrar.semestral-clearance');
    Route::get('/semestral-clearance/view', [RegistrarController::class, 'semestral_student_list_view'])->name('registrar.semestral-student-list'); // Section view
    Route::post('/semestral-clearance', [RegistrarController::class, 'clearance_store'])->name('registrar.semestral-clearance-store');
    Route::get('/semestral-clearance/report', [RegistrarController::class, 'semestral_clearance_report'])->name('registrar.semestral-clearance-report');

    // Semestral Grades
    Route::get('/semestral-grade', [RegistrarController::class, 'semestral_grade_view'])->name('registrar.semestral-grades');
    Route::get('/semestral-grade/view', [RegistrarController::class, 'semestral_grade_section_view'])->name('registrar.semestral-grade-view');
    Route::get('/semestral-grade/report-form', [RegistrarController::class, 'semestral_grade_report_form'])->name('registrar.semestral-grade-form-ad2');
    Route::get('/semestral-grade/report-summary', [RegistrarController::class, 'semestral_grade_summary_report'])->name('registrar.semestral-grade-semestral-report');
    Route::get('/smestral-grade/publish-grade', [RegistrarController::class, 'semestral_grade_publish'])->name('registrar.semestral-grade-publish');
    Route::get('/smestral-grade/publish-grade-all', [RegistrarController::class, 'semestral_grade_publish_all'])->name('registrar.semestral-grade-publish-all');
    Route::get('/semstral-grade/subject-grade', [RegistrarController::class, 'semestral_subject_grade'])->name('registrar.subject-grade');
    Route::get('/semstral-grade/subject-grade/export-excel', [RegistrarController::class, 'summary_grade_report_excel'])->name('registrar.subject-grade-export');
    /* Applicants */
    //require __DIR__ . '\extra\applicant-route.php'; // Applicant Route

    // Applicant
    //Route::get('/applicant');
    require __DIR__ . '/extra/applicant-route.php'; // Applicant Route
    require __DIR__ . '/extra/ticket-route.php'; // Applicant Route
    require __DIR__ . '/extra/enrollment-route.php'; // Enrollment Route

    Route::get('/scholarship-grants', ScholarshipGrantView::class)->name('registrar.scholarship-grants');
});
