<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CourseOffer;
use App\Models\GradeComputed;
use App\Models\GradeVerification;
use App\Models\Section;
use App\Models\StudentDetails;
use App\Models\StudentNonAcademicClearance;
use App\Models\StudentSection;
use App\Models\SubjectClass;
use App\Report\GradingSheetReport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('dean');
    }
    public function dashboard(Request $_request)
    {
        $_course = CourseOffer::where('course_code', '!=', 'pbm')->get();
        return view('pages/dean/grade-book/view', compact('_course'));
    }
    public function grading_verification_view(Request $_request)
    {
        $_section = Section::find(base64_decode($_request->_section));
        return view('pages.dean.grade-book.grading_verification_view', compact('_section'));
    }
    public function grading_sheet_view(Request $_request)
    {
        $_subject = SubjectClass::find(base64_decode($_request->_subject));
        $_subject_code =  $_subject->curriculum_subject->subject->subject_code;
        if ($_subject_code == 'BRDGE') {
            $_students = $_subject->section->student_with_bdg_sections;
        } else {
            $_students = $_subject->section->student_sections;
        }
        $_report = new GradingSheetReport($_students, $_subject);
        return $_request->_form == "ad1" ? $_report->form_ad_01() : $_report->form_ad_02();
    }
    public function e_clearance_view(Request $_request)
    {
        $_course = CourseOffer::where('course_code', '!=', 'pbm')->get();
        return view('pages/dean/clearance/view', compact('_course'));
    }
    public function e_clearance_section_view(Request $_request)
    {
        $_section = Section::find(base64_decode($_request->_section));
        $_students = $_section->student_section;
        return view('pages/dean/clearance/section_view', compact('_section', '_students'));
    }
    public function e_clearance_section_store(Request $_request)
    {
        if (is_array($_request->dean)) {
            foreach ($_request->dean as $key => $value) {
                $_checker = StudentNonAcademicClearance::where(['student_id' => $value, 'non_academic_type' => 'dean', 'academic_id' => $_request->_academic, 'is_approved' =>  1,])->first();
                $_data = array(
                    'student_id' => $value,
                    'non_academic_type' => 'dean',
                    'academic_id' => $_request->_academic,
                    'staff_id' => Auth::user()->staff->id,
                    'is_approved' =>  1, // nullable
                    'is_removed' => 0
                );
                $_checker ? '' : StudentNonAcademicClearance::create($_data);
                //echo $value . "<br>";
                $_student = StudentDetails::find($value);
                $_student->offical_clearance_cleared();
            }
        }
        return back()->with('success', 'Successfully Submitted.');
    }

    public function verify_grade_submission(Request $_request)
    {
        try {
            $_subject_class = SubjectClass::find(base64_decode($_request->subject_class));
            $_data = array(
                'subject_class_id' => $_subject_class->id,
                'is_approved' => $_request->_status,
                'comments' => $_request->_comments ?: null,
                'approved_by' => Auth::user()->name,
                'is_removed' => 0
            );
            $students = $_subject_class->section->student_sections;
            foreach ($students as $key => $student) {
                $midterm_grade = $student->student->period_final_grade('midterm');
                $final_grade = $_subject_class->academic_id >= 5 ? $student->student->total_final_grade() : $student->student->period_final_grade('finals');
                $data = array(
                    'student_id' => $student->student->id,
                    'subject_class_id' => $_subject_class->id,
                    'midterm_grade' => base64_encode($midterm_grade),
                    'final_grade' => base64_encode($final_grade),
                );
                $_computed =  $_subject_class->student_computed_grade($student->student_id)->first();
                if (!$_computed) {
                    GradeComputed::create($data);
                } else {
                    $_computed->update(['removed_at', true]);
                }
            }
            GradeVerification::create($_data);
            return back()->with('success', 'Successfully Approved');
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function publish_grade_submission(Request $_request)
    {
        try {
            $_subject_class = SubjectClass::find(base64_decode($_request->subject_class));

            $students = $_subject_class->section->student_sections;
            foreach ($students as $key => $student) {
                $midterm_grade = $student->student->period_final_grade('midterm');
                $final_grade = $_subject_class->academic_id >= 5 ? $student->student->total_final_grade() : $student->student->period_final_grade('finals');
                $data = array(
                    'student_id' => $student->student->id,
                    'subject_class_id' => $_subject_class->id,
                    'midterm_grade' => base64_encode($midterm_grade),
                    'final_grade' => base64_encode($final_grade),
                );
                $_computed =  $_subject_class->student_computed_grade($student->student_id)->first();
                if (!$_computed) {
                    GradeComputed::create($data);
                } else {
                    GradeComputed::create($data);
                    $_computed->update(['removed_at', true]);
                   
                }
            }
            return back()->with('success', 'Successfully Approved');
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
}
