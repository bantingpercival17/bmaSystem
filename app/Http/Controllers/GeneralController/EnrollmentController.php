<?php

namespace App\Http\Controllers\GeneralController;

use App\Exports\CourseStudentEnrolled;
use App\Exports\WorkSheet\SemesteralEnrollmentList;
use App\Http\Controllers\Controller;
use App\Models\CourseOffer;
use App\Models\Curriculum;
use App\Models\EnrollmentAssessment;
use App\Models\StudentAccount;
use App\Models\StudentDetails;
use App\Report\StudentListReport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class EnrollmentController extends Controller
{
    public function __construct()
    {
        set_time_limit(800000000);
    }
    public function enrollment_view(Request $_request)
    {
        try {
            $_courses = CourseOffer::where('is_removed', false)->get();
            $_curriculums = Curriculum::where('is_removed', false)->get();
            $_student_detials = new StudentDetails();
            $_students = $_request->_student ? $_student_detials->student_search($_request->_student) : $_student_detials->enrollment_application_list();
            $_students = $_request->_course ? $_student_detials->enrollment_application_list_view_course($_request->_course) : $_students;
            //return $_students;
            return view('pages.general-view.enrollment.enrollment-view', compact('_courses', '_students', '_curriculums'));
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
    }

    public function enrolled_list_view(Request $_request)
    {
        try {
            $_courses = CourseOffer::where('is_removed', false)->orderBy('id', 'desc')->get();
            $_course = CourseOffer::find(base64_decode($_request->_course));
            $_students = $_request->_year_level ?  $_course->enrollment_list_by_year_level($_request->_year_level)->get() : $_course->enrollment_list; // Year Level
            $_students = $_request->_sort ? $_course->sort_enrolled_list($_request)->get() : $_students; // Sorting
            return view('pages.general-view.enrollment.enrolled_list_view', compact('_courses', '_course', '_students'));
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
    }

    public function course_enrolled_report(Request $_request)
    {
        try {
          
            // Excell Report
            if ($_request->_report == 'excel-report') {
                $_course = CourseOffer::find(base64_decode($_request->_course));
                $_file_name = $_course->course_code . "_" . Auth::user()->staff->current_academic()->school_year . '_' . strtoupper(str_replace(' ', '_', Auth::user()->staff->current_academic()->semester));
                $_file_export = new CourseStudentEnrolled($_course);
                $_respond =  Excel::download($_file_export, $_file_name . '.xlsx', \Maatwebsite\Excel\Excel::XLSX); // Download the File
                ob_end_clean();
                return $_respond;
            }
            if ($_request->_report == 'pdf-report') {
                $report = new StudentListReport();
                $courses = CourseOffer::all();
                return $report->semestral_enrollees($courses);
                //return Excel::download($_file_export, $_file_name . '.pdf'); // Download the File
            }
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
    }

    public function enrollment_payment_assessment(Request $_request)
    {
        try {
            $_courses = CourseOffer::where('is_removed', false)->orderBy('id', 'desc')->get();
            $_course = CourseOffer::find(base64_decode($_request->_course));
            $_students = $_course->payment_assessment;
            return view('pages.general-view.enrollment.payment-assessment', compact('_courses', '_course', '_students'));
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
    }
    public function enrollment_category(Request $_request)
    {
        try {
            $_courses = CourseOffer::orderBy('id', 'desc')->get();
            $_course = CourseOffer::find(base64_decode($_request->_course));
            $_function = [];
            $item = strtoupper($_request->category);
            $level = $_request->level;
            $_category = ['EXPECTED ENROLLEE', 'NOT CLEARED', 'CLEARED', 'ENROLLMENT ASSESSMENT', 'BRIDGING PROGRAM', 'TUITION FEE ASSESSMENT', 'TUITION FEE PAYMENT', 'PAYMENT VERIFICATION', 'TOTAL ENROLLED'];

            $_function = $item == 'EXPECTED ENROLLEE' ? $_course->expected_enrollee_year_level($level)->get() : $_function;
            $_function = $item == 'NOT CLEARED' ? $_course->students_not_clearance_year_level($level)->get() : $_function;
            $_function = $item == 'ENROLLMENT ASSESSMENT' ? $_course->enrollment_assessment_year_level($level)->get() : $_function;
            $_function = $item == 'BRIDGING PROGRAM' ? $_course->student_bridging_program_year_level($level)->get() : $_function;
            $_function = $item == 'TUITION FEE ASSESSMENT' ? $_course->payment_assessment_sort($level)->get() : $_function;
            $_function = $item == 'TUITION FEE PAYMENT' ? $_course->payment_transaction_year_level($level)->get() : $_function;
            $_function = $item == 'PAYMENT VERIFICATION' ? $_course->payment_transaction_online_year_level($level)->get() : $_function;
            $_function = $item == 'TOTAL ENROLLED' ? $_course->enrollment_list_by_year_level($level)->get() : $_function;

            $_students = $_function;
            $_category_content = $item == 'TUITION FEE ASSESSMENT' ? 'payment_assessment' : '';
            return view('pages.general-view.enrollment.enrollment-category-list', compact('_students', '_courses', '_course', '_category_content'));
            return $_function;
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
    }
    public function enrollment_student_number()
    {
        $_enrollee = EnrollmentAssessment::select('enrollment_assessments.*')
            ->join('payment_assessments', 'enrollment_assessments.id', 'payment_assessments.enrollment_id')
            ->join('payment_transactions', 'payment_assessments.id', 'payment_transactions.assessment_id')
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('enrollment_assessments.is_removed', false)
            ->where('payment_transactions.is_removed', false)
            /*  ->where('enrollment_assessments.year_level', 11)
            ->where('enrollment_assessments.course_id', 3)
            */
            //->where('enrollment_assessments.year_level', 4)
            ->where('enrollment_assessments.curriculum_id', 8)
            ->groupBy('enrollment_assessments.id')
            ->orderBy('payment_transactions.created_at', 'ASC')->get();
        $_student_count = 0;
        foreach ($_enrollee as $key => $value) {
            //$_student_number = '06-22';
            $_student_number = '223';
            $_student_count += 1;
            $_number = $_student_count > 9 ? ($_student_count >= 100 ? $_student_count : '' . $_student_count) : '0' . $_student_count;
            $_student_number = $_student_number . $_number;
            echo 'STUDENT COUNT:' . $_student_number;
            echo '<br>';
            echo json_encode($value->student->account);
            $_email =  $_student_number . '.' . str_replace(' ', '', str_replace('.', '', mb_strtolower($value->student->last_name))) . '@bma.edu.ph';
            $_account_details = array(
                'student_id' => $value->student_id,
                'email' => $_email,
                'student_number' => $_student_number,
                'password' => Hash::make($_student_number),
                'is_actived' => true,
                'is_removed' => false,
            );
            //echo "<br>";
            //echo $_email;
            //echo json_encode($_account_details);
            echo "<br>";
            if ($value->student->account) {
                $value->student->account->update($_account_details);
                echo json_encode($_account_details);
                echo true;
            } else {
                $_account_details['personal_email'] = $_email;
                StudentAccount::create($_account_details);
                echo false;
            }
            echo "<br>";
            // echo json_encode($_account_details);
            // /echo "<br><br>";
        }
    }
    //  Un-use Function
    public function dashboard_enrolled_list_view(Request $_request)
    {
        $_course = CourseOffer::find(base64_decode($_request->_course));
        $_students = $_request->_year_level ?  $_course->enrollment_list_by_year_level($_request->_year_level)->get() : $_course->enrollment_list; // Year Level
        $_students = $_request->_sort ? $_course->sort_enrolled_list($_request)->get() : $_students; // Sorting
        return view('pages.general-view.enrollment.enrolled_list_view', compact('_course', '_students'));
    }
    public function enrollment_semestral_list(Request $_request)
    {
        try {
            $index = array(
                'student_details.id',
                'student_details.last_name',
                'student_details.first_name',
                'student_details.middle_name',
                'student_details.extention_name',
                'student_details.middle_initial',
                'student_details.sex',
                'enrollment_assessments.year_level',
                'enrollment_assessments.course_id',
                'enrollment_assessments.curriculum_id',
                'enrollment_assessments.course_id'
            );
            $students =  EnrollmentAssessment::select($index)
                ->join('student_details', 'student_details.id', 'enrollment_assessments.student_id')
                ->join('payment_assessments', 'enrollment_assessments.id', 'payment_assessments.enrollment_id')
                ->join('payment_transactions', 'payment_assessments.id', 'payment_transactions.assessment_id')
                ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
                ->where('enrollment_assessments.is_removed', false)
                ->where('payment_transactions.is_removed', false)
                ->where('enrollment_assessments.course_id', '!=', 3)
                ->groupBy('enrollment_assessments.id')
                ->orderBy('student_details.last_name', 'asc')->orderBy('student_details.first_name', 'asc')->get();
            $_date = now();
            $academic =  strtoupper(Auth::user()->staff->current_academic()->semester) . '-' . Auth::user()->staff->current_academic()->school_year;
            $_file_name = 'SEMESTRAL ENROLLMENT LIST-' . $academic . '-' . $_date . '.xlsx'; // Name of the File
            $_excel = new SemesteralEnrollmentList($students); // Excel Function
            $_file = Excel::download($_excel, $_file_name); // Download the File
            ob_end_clean();
            return $_file;
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
}
