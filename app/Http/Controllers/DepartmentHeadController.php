<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\GradeSubmission;
use App\Models\Section;
use App\Models\Staff;
use App\Models\StudentClearance;
use App\Models\StudentDetails;
use App\Models\StudentNonAcademicClearance;
use App\Models\SubjectClass;
use App\Report\GradingSheetReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentHeadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('department-head');
    }
    public function submission_view(Request $_request)
    {
        $_staffs = Staff::where('department', Auth::user()->staff->department)->orderBy('last_name')->get();
        return view('pages.department-head.grade.grade_submission_view', compact('_staffs'));
    }
    public function subject_submission_view(Request $_request)
    {
        $_staff = Staff::find(base64_decode($_request->_staff));
        //$_subject_class = $_request->_subject ? GradeSubmission::where('subject_class_id', base64_decode($_request->_subject))->where('form', 'ad1')->where('period', $_request->_period)->latest()->first() : [];
        $_subject_class = $_request->_subject ? SubjectClass::find(base64_decode($_request->_subject)) : [];
        return view('pages.department-head.grade.instruction_subject_view', compact('_staff', '_subject_class'));
    }

    public function subject_report_view(Request $_request)
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
        $_current_academic =  $_request->_academic ? AcademicYear::find(base64_decode($_request->_academic)) : AcademicYear::where('is_active', 1)->first();
        $_academics = AcademicYear::where('is_removed', false)->orderBy('id', 'DESC')->get();
        $_course = Auth::user()->staff->department == 'MARINE ENGINEERING' ? 1 : ($_course = Auth::user()->staff->department == 'MARINE TRANSPORTATION' ? 2 : 3);
        $_sections = $_request->_academic ? Section::where('academic_id', base64_decode($_request->_academic))->where('course_id', 2)->orderBy('section_name', 'ASC')->get() :
            Section::where('academic_id', $_current_academic->id)->where('course_id', 2)->orderBy('section_name', 'ASC')->get();
        return view('pages.department-head.clearance.view', compact('_academics', '_current_academic', '_sections'));
    }
    public function section_view_e_clearance(Request $_request)
    {
        $_section = Section::find(base64_decode($_request->_section));
        $_students = StudentDetails::select('student_details.id', 'student_details.last_name', 'student_details.first_name')
            ->join('student_sections as ss', 'ss.student_id', 'student_details.id')
            ->where('ss.section_id', $_section->id)
            ->orderBy('student_details.last_name', 'ASC')
            ->where('ss.is_removed', false)
            ->get();
        return view('pages.department-head.clearance.view_list', compact('_section', '_students'));
    }
    public function store_student_clearance(Request $_request)
    {
        if (is_array($_request->laboratory)) {
            /*  foreach ($_request->laboratory as $key => $value) {
                $_checker = StudentNonAcademicClearance::where(['student_id' => $value, 'non_academic_type' => 'laboratory', 'is_approved' =>  1, 'academic_id' => Auth::user()->staff->current_academic()->id,])->first();
                $_data = array(
                    'student_id' => $value,
                    'non_academic_type' => 'laboratory',
                    'academic_id' => Auth::user()->staff->current_academic()->id,
                    'staff_id' => Auth::user()->staff->id,
                    'is_approved' =>  1, // nullable
                    'is_removed' => 0
                );
                $_checker ? '' : StudentNonAcademicClearance::create($_data);
                //echo $value . "<br>";
            } */
            foreach ($_request->laboratory as $key => $value) {
                $_student_id = $value;
                $_clearance_data = 'laboratory';
                // Check if the student is Store
                $_check = 1;
                $_clearance = array(
                    'student_id' => $value,
                    'non_academic_type' => 'laboratory',
                    'academic_id' => $_request->_academic,
                    'staff_id' => Auth::user()->staff->id,
                    'is_approved' =>  1, // nullable
                    'is_removed' => 0
                );
                $_check_clearance = StudentNonAcademicClearance::where('student_id', $_student_id)->where('non_academic_type', $_clearance_data)->where('academic_id', $_request->_academic)->where('is_removed', false)->first();
                if ($_check_clearance) {
                    // If the Data is existing and the approved status id TRUE and the Input Tag is TRUE : They will remain

                    // If the Data is existing and the apprvod status is FALSE and the Input is FALSE : Nothing to Do, They will remain
                    // If comment is fillable
                    if ($_check_clearance->is_approved == 0 && $_check == 0) {
                        if ($value['comment']) {
                            $_check_clearance->comments = $value['comment'];
                            $_check_clearance->save();
                        }
                    }
                    // If the Data is existing and the approved status is TRUE and the Input is FALSE : The Data will removed and create a new one
                    if ($_check_clearance->is_approved == 1 && $_check == 0) {
                        $_check_clearance->is_removed = true;
                        $_check_clearance->save();
                        StudentNonAcademicClearance::create($_clearance);
                    }
                    if ($_check_clearance->is_approved == 0 && $_check == 1) {
                        $_check_clearance->is_removed = true;
                        $_check_clearance->save();
                        StudentNonAcademicClearance::create($_clearance);
                    }
                } else {
                    StudentNonAcademicClearance::create($_clearance);
                }
                //echo "Saved: " . $_student_id . "<br>";

            }
        }
        if (is_array($_request->dept_head)) {
            foreach ($_request->dept_head as $key => $value) {
                $_checker = StudentNonAcademicClearance::where(['student_id' => $value, 'non_academic_type' => 'department-head', 'is_approved' =>  1, 'academic_id' => $_request->_academic,])->first();
                $_data = array(
                    'student_id' => $value,
                    'non_academic_type' => 'department-head',
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
    public function update_student_clearance(Request $_request)
    {
        if ($_request->category == 'non-academic') {
            $_data = StudentNonAcademicClearance::where('student_id', $_request->id)->where('non_academic_type', $_request->content)->where('is_removed', false)->first();
            if ($_data) {
                $_data->is_approved = 0;
                $_data->is_removed = true;
                $_data->save();
            }
        }
        if ($_request->category == 'academic') {
            $_data = StudentClearance::where('student_id', $_request->id)->where('subject_class_id', $_request->content)->where('is_removed', false)->first();
            if ($_data) {
                $_data->is_approved = 0;
                $_data->save();
            }
        }
        $data = array(
            'respond' => 200,
            'message' => 'Successfully Update.'
        );
        return compact('data');
    }
    public function save_student_clearance(Request $_request)
    {
        if ($_request->category == 'academic') {
            $_data = StudentClearance::where('student_id', $_request->id)->where('subject_class_id', $_request->content)->where('is_removed', false)->first();
            if ($_data) {
                $_data->is_removed = true;
                $_data->save();
                $_data = array(
                    'student_id' => $_request->id,
                    'subject_class_id' => $_request->content,
                    'staff_id' => Auth::user()->staff->id,
                    'is_approved' =>  1, // nullable
                    'is_removed' => 0
                );
                StudentClearance::create($_data);
                $data = array(
                    'respond' => 200,
                    'message' => 'Successfully Update.'
                );
            } else {
                $data = array(
                    'respond' => 404,
                    'message' => 'This Will bee Saved.'
                );
            }
        }
        return compact('data');
    }
    public function submission_verification(Request $_request)
    {
        $_grade_submission = GradeSubmission::find(base64_decode($_request->_submission));
        $_subject = $_grade_submission->subject_class->curriculum_subject->subject->subject_code;
        $_section =  $_grade_submission->subject_class->section->section_name;
        $_grade_submission->is_approved = $_request->_status;
        $_grade_submission->comments = $_request->_comments;
        $_grade_submission->approved_by = Auth::user()->name;
        $_grade_submission->save();
        return back()->with('success', $_subject . " " . $_section . " Verified..");
    }
}
