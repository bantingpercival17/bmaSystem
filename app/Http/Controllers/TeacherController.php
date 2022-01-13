<?php

namespace App\Http\Controllers;

use App\Imports\GradeImport;
use App\Mail\GradeSubmissionMail;
use App\Mail\GradeVerificationMail;
use App\Models\AcademicYear;
use App\Models\GradeEncode;
use App\Models\GradeSubmission;
use App\Models\Staff;
use App\Models\StudentClearance;
use App\Models\StudentDetails;
use App\Models\StudentSection;
use App\Models\SubjectClass;
use App\Report\GradingSheetReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('teacher');
    }
    public function index()
    {
        $_staff = Auth::user()->staff;

        $_academic = AcademicYear::where('is_active', 1)->first();
        // Select * from subject_classes where staff_id = ? and academic_id = ? 
        $_subject = SubjectClass::where('staff_id', $_staff->id)
            ->where('academic_id', $_academic->id)
            ->where('is_removed', false)
            ->get();
        if (Auth::user()->email == 'k.j.cruz@bma.edu.ph') {
            return view('teacher\dashboard\dasboard-main', compact('_subject'));
        } else {
            return view('teacher.dashboard', compact('_subject'));
        }
    }
    public function subject_class_view(Request $_request)
    {
        $_subject = SubjectClass::find(base64_decode($_request->_s));
        $_subject_code =  $_subject->curriculum_subject->subject->subject_code;
        if ($_subject_code == 'BRDGE') {
            $_students = StudentDetails::select('student_details.id', 'student_details.last_name', 'student_details.first_name')
                ->join('student_sections as ss', 'ss.student_id', 'student_details.id')
                ->join('enrollment_assessments as ea', 'ea.student_id', 'student_details.id')
                ->where('ea.academic_id', $_subject->academic_id)
                ->where('ss.section_id', $_subject->section_id)
                ->where('ea.bridging_program', 'with')
                ->orderBy('student_details.last_name', 'ASC')
                ->where('ss.is_removed', false)
                ->get();
        } else {
            $_students = StudentDetails::select('student_details.id', 'student_details.last_name', 'student_details.first_name')
                ->join('student_sections as ss', 'ss.student_id', 'student_details.id')
                ->where('ss.section_id', $_subject->section_id)
                ->orderBy('student_details.last_name', 'ASC')
                ->where('ss.is_removed', false)
                ->get();
        }
        return view('teacher.subject-class.view', compact('_subject', '_students'));
    }
    public function subject_grading_main_view(Request $_request)
    {
        $_subject = SubjectClass::find(base64_decode($_request->_s));
        $_subject_code =  $_subject->curriculum_subject->subject->subject_code;

        //return $_subject->academic_id;
        if ($_subject_code == 'BRDGE') {
            $_students = StudentDetails::select('student_details.id', 'student_details.last_name', 'student_details.first_name')
                ->join('student_sections as ss', 'ss.student_id', 'student_details.id')
                ->join('enrollment_assessments as ea', 'ea.student_id', 'student_details.id')
                ->where('ea.academic_id', $_subject->academic_id)
                ->where('ss.section_id', $_subject->section_id)
                ->where('ea.bridging_program', 'with')
                ->orderBy('student_details.last_name', 'ASC')
                ->where('ss.is_removed', false)
                ->get();
        } else {
            $_students = StudentDetails::select('student_details.id', 'student_details.last_name', 'student_details.first_name')
                ->join('student_sections as ss', 'ss.student_id', 'student_details.id')
                ->where('ss.section_id', $_subject->section_id)
                ->orderBy('student_details.last_name', 'ASC')
                ->where('ss.is_removed', false)
                ->get();
        }
        if ($_request->_preview) {
            $_report = new GradingSheetReport($_students, $_subject);
            return $_request->_form == "ad1" ? $_report->form_ad_01() : $_report->form_ad_02();
        } else {
            $_columns = [['QUIZZES', 'Q', 10], ['ORAL EXAM', 'O', 5], ['R W - OUTPUT', 'R', 10], [request()->input('_period'),  strtoupper(request()->input('_period')[0]) . 'E', 1]];
            $_subject_code = $_subject->curriculum_subject->subject->subject_code;
            if ($_subject->curriculum_subject->subject->laboratory_hours > 0 && $_subject_code !=  str_contains($_subject_code, 'P.E.')) {
                $_columns[] =  ['Scientific and Technical Experiments Demonstrations of Competencies Acquired', 'A', 10];
            }


            return view('teacher.grading_sheet_main', compact('_subject', '_students', '_columns'));
        }
    }
    public function subject_grading_view(Request $_request)
    {
        $_subject = SubjectClass::find(Crypt::decrypt($_request->_s));
        $_subject_code =  $_subject->curriculum_subject->subject->subject_code;

        //return $_subject->academic_id;
        if ($_subject_code == 'BRDGE') {
            $_students = StudentDetails::select('student_details.id', 'student_details.last_name', 'student_details.first_name')
                ->join('student_sections as ss', 'ss.student_id', 'student_details.id')
                ->join('enrollment_assessments as ea', 'ea.student_id', 'student_details.id')
                ->where('ea.academic_id', $_subject->academic_id)
                ->where('ss.section_id', $_subject->section_id)
                ->where('ea.bridging_program', 'with')
                ->orderBy('student_details.last_name', 'ASC')
                ->where('ss.is_removed', false)
                ->get();
        } else {
            $_students = StudentDetails::select('student_details.id', 'student_details.last_name', 'student_details.first_name')
                ->join('student_sections as ss', 'ss.student_id', 'student_details.id')
                ->where('ss.section_id', $_subject->section_id)
                ->orderBy('student_details.last_name', 'ASC')
                ->where('ss.is_removed', false)
                ->get();
        }
        if ($_request->_preview) {
            $_report = new GradingSheetReport($_students, $_subject);
            return $_request->_form == "ad1" ? $_report->form_ad_01() : $_report->form_ad_02();
        } else {
            $_columns = [['QUIZZES', 'Q', 10], ['ORAL EXAM', 'O', 5], ['R W - OUTPUT', 'R', 10], [request()->input('_period'),  strtoupper(request()->input('_period')[0]) . 'E', 1]];
            $_subject_code = $_subject->curriculum_subject->subject->subject_code;
            if ($_subject->curriculum_subject->subject->laboratory_hours > 0 && $_subject_code !=  str_contains($_subject_code, 'P.E.')) {
                $_columns[] =  ['Scientific and Technical Experiments Demonstrations of Competencies Acquired', 'A', 10];
            }


            return view('teacher.grading_sheet', compact('_subject', '_students', '_columns'));
        }
    }
    public function subject_view()
    {
        $_academics = AcademicYear::where('is_removed', false)->orderBy('id', 'desc')->get();
        return view('teacher.subject_view', compact('_academics'));
    }
    public function grade_store(Request $_request)
    {
        // String Validation
        // Over Score Validation
        // Store and Update the Score
        $_score_details = array(
            'student_id' => $_request->_student,
            'subject_class_id' => $_request->_class,
            'period' => $_request->_period,
            'type' => $_request->_type,
        );
        $_check_details = GradeEncode::where($_score_details)->first();

        if ($_check_details) {
            // Update Score
            if ($_request->_score == '') {
                $_return = GradeEncode::where($_score_details)->update(['is_removed' => 1]);
            } else {
                if ($_check_details->is_removed == 1) {
                    GradeEncode::where($_score_details)->update(['is_removed' => 0]);
                }
                $_return = GradeEncode::where($_score_details)->update(['score' => doubleval($_request->_score)]);
            }
            $_respond = array('message' => 'Score Saved', 'status' => 'success');
        } else {
            // Save Score
            $_score_details['score'] = doubleval($_request->_score);
            $_score_details['is_removed'] = 0;
            $_return = GradeEncode::create($_score_details);
            $_respond = array('message' => 'Score Updated', 'status' => 'success');
        }
        if ($_return) {
            return compact('_respond');
        }
    }
    public function subject_grade_submission(Request $_request)
    {
        $_subject = SubjectClass::find(Crypt::decrypt($_request->_subject));
        //return $_subject->section->course_id;
        GradeSubmission::create([
            'subject_class_id' => $_subject->id,
            'form' => $_request->_form,
            'period' => $_request->_period
        ]);
        //return $_subject;
        //Mail::to('developer@bma.edu.ph')->bcc('it@bma.edu.ph')->send(new GradeSubmissionMail($_subject));
        return back()->with('message', "Grade Sudmitted");
    }
    public function instructor_view(Request $_request)
    {
        $_subject = SubjectClass::find(Crypt::decrypt($_request->_subject));
        $_staff = Staff::find($_subject->staff_id);
        return view('teacher.teacher_view', compact('_subject', '_staff'));
    }
    public function submission_view(Request $_request)
    {
        $_academics = AcademicYear::where('is_removed', false)->orderBy('id', 'DESC')->get();
        $_staffs = Staff::where('department', Auth::user()->staff->department)->orderBy('last_name')->get();
        return view('teacher.submission_view', compact('_staffs', '_academics'));
    }
    public function subject_report_view(Request $_request)
    {
        $_subject = SubjectClass::find(Crypt::decrypt($_request->_s));
        $_students = StudentDetails::select('student_details.id', 'student_details.last_name', 'student_details.first_name')
            ->join('student_sections as ss', 'ss.student_id', 'student_details.id')
            ->where('ss.section_id', $_subject->section_id)
            ->orderBy('student_details.last_name', 'ASC')
            ->where('ss.is_removed', false)
            ->get();
        $_report = new GradingSheetReport($_students, $_subject);
        return $_request->_form == "ad1" ? $_report->form_ad_01() : $_report->form_ad_02();
    }
    public function check_grade_submission(Request $_request)
    {
        $_grade_submission = GradeSubmission::find(Crypt::decrypt($_request->_submission));
        $_subject = $_grade_submission->subject_class->curriculum_subject->subject->subject_code;
        $_section =  $_grade_submission->subject_class->section->section_name;
        if ($_request->_status == 1) {
            echo "Approved";
            $_grade_submission->update([
                'is_approved' => true,
                'approved_by' => Auth::user()->name
            ]);
            //Mail::to('developer@bma.edu.ph')->bcc('it@bma.edu.ph')->send(new GradeVerificationMail($_grade_submission->subject_class, 'approved'));
            return back()->with('message', $_subject . " " . $_section . " Approved");
        } else {
            echo "Disapproved";
            $_grade_submission->update([
                'is_approved' => false,
                'comments' => $_request->_comments,
                'approved_by' => Auth::user()->name
            ]);
            //Mail::to('developer@bma.edu.ph')->bcc('it@bma.edu.ph')->send(new GradeVerificationMail($_grade_submission->subject_class, 'disapproved'));
            return back()->with('message', $_subject . " " . $_section . " Disapproved");
        }
    }
    public function subject_grade_bulk_upload(Request $_request)
    {
        Excel::import(new GradeImport($_request->_section), $_request->file('_file_grade'));
        return back();
    }
    public function student_e_clearance(Request $_request)
    {
        foreach ($_request->data as $key => $value) {
            $_student_id = base64_decode($value['sId']);
            $_subject_class = base64_decode($_request->_subject_class);
            // Check if the student is Store
            $_check = count($value) > 2 ? 1 : 0;
            $_clearance = array(
                'student_id' => $_student_id,
                'subject_class_id' => $_subject_class,
                'comments' => $value['comment'], // nullable
                'is_approved' => $_check, // nullable
                'is_removed' => 0
            );
            $_check_clearance = StudentClearance::where('student_id', $_student_id)->where('subject_class_id', $_subject_class)->where('is_removed', false)->first();
            if ($_check_clearance) {
                // If the Data is existing and the approved status id TRUE and the Input Tag is TRUE : They will remain

                // If the Data is existing and the apprvod status is FALSE and the Input is FALSE : Nothing to Do, They will remain
                // If comment is fillable

                // If the Data is existing and the approved status is TRUE and the Input is FALSE : The Data will removed and create a new one
                if ($_check_clearance->is_approved == 1 && $_check == 0) {
                    $_check_clearance->is_removed = true;
                    $_check_clearance->save();
                    StudentClearance::create($_clearance);
                }
                if ($_check_clearance->is_approved == 0 && $_check == 1) {
                    $_check_clearance->is_removed = true;
                    $_check_clearance->save();
                    StudentClearance::create($_clearance);
                }
            } else {
                StudentClearance::create($_clearance);
            }
            //echo "Saved: " . $_student_id . "<br>";

        }
        return back()->with('success', 'Successfully Submitted Clearance');
    }
}
