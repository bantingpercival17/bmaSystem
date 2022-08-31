<?php

namespace App\Http\Controllers\GeneralController;

use App\Exports\CourseStudentEnrolled;
use App\Http\Controllers\Controller;
use App\Models\CourseOffer;
use App\Models\Curriculum;
use App\Models\StudentDetails;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class EnrollmentController extends Controller
{
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
            $_students = $_request->_year_level ?  $_course->enrolled_list($_request->_year_level)->get() : $_course->enrollment_list; // Year Level
            $_students = $_request->_sort ? $_course->sort_enrolled_list($_request)->get() : $_students; // Sorting
            return view('pages.general-view.enrollment.enrolled_list_view', compact('_courses', '_course', '_students'));
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
    }

    public function course_enrolled_report(Request $_request)
    {
        try {
            $_course = CourseOffer::find(base64_decode($_request->_course));
            $_file_name = $_course->course_code . "_" . Auth::user()->staff->current_academic()->school_year . '_' . strtoupper(str_replace(' ', '_', Auth::user()->staff->current_academic()->semester));
            $_file_export = new CourseStudentEnrolled($_course);
            // Excell Report
            if ($_request->_report == 'excel-report') {
                $_respond =  Excel::download($_file_export, $_file_name . '.xlsx', \Maatwebsite\Excel\Excel::XLSX); // Download the File 
                ob_end_clean();
                return $_respond;
            }
            if ($_request->_report == 'pdf-report') {
                return Excel::download($_file_export, $_file_name . '.pdf'); // Download the File 
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
            //$_students = $_request->_year_level ?  $_course->enrolled_list($_request->_year_level)->get() : $_course->enrollment_list; // Year Level
            //$_students = $_request->_sort ? $_course->sort_enrolled_list($_request)->get() : $_students; // Sorting
            return view('pages.general-view.enrollment.payment-assessment', compact('_courses', '_course', '_students'));
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
    }


    //  Un-use Function
    public function dashboard_enrolled_list_view(Request $_request)
    {
        $_course = CourseOffer::find(base64_decode($_request->_course));
        $_students = $_request->_year_level ?  $_course->enrolled_list($_request->_year_level)->get() : $_course->enrollment_list; // Year Level
        $_students = $_request->_sort ? $_course->sort_enrolled_list($_request)->get() : $_students; // Sorting
        return view('pages.general-view.enrollment.enrolled_list_view', compact('_course', '_students'));
    }
}
