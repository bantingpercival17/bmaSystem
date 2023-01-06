<?php

namespace App\Http\Controllers;

use App\Exports\GradeTemplate;
use App\Imports\GradeImport;
use App\Mail\GradeSubmissionMail;
use App\Mail\GradeVerificationMail;
use App\Models\AcademicYear;
use App\Models\CourseSyllabus;
use App\Models\GradeEncode;
use App\Models\GradeSubmission;
use App\Models\Section;
use App\Models\Staff;
use App\Models\StudentClearance;
use App\Models\StudentDetails;
use App\Models\StudentSection;
use App\Models\SubjectClass;
use App\Models\SubjectClassCourseSyllabus;
use App\Report\GradingSheetReport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('teacher');
    }
    public function subject_list(Request $_request)
    {
        try {
            $_staff = Auth::user()->staff;
            $_academic = $_request->_academic ? AcademicYear::find(base64_decode($_request->_academic)) : Auth::user()->staff->current_academic();
            $_subject = SubjectClass::where('staff_id', $_staff->id)
                ->where('academic_id', $_academic->id)
                ->where('is_removed', false)
                ->get();
            return view('pages.teacher.dashboard.dasboard-main', compact('_subject'));
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }
    public function subject_class_view(Request $_request)
    {
        try {
            $_subject = SubjectClass::find(base64_decode($_request->_subject));
            $_subject_code =  $_subject->curriculum_subject->subject->subject_code;
            if ($_subject_code == 'BRDGE') {
                $_students = $_subject->section->student_with_bdg_sections;
            } else {
                $_students = $_subject->section->student_sections;
            }
            return view('pages.teacher.subject-class.view', compact('_subject', '_students'));
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }
    public function subject_student_list(Request $_request)
    {
        $_subject = SubjectClass::find(base64_decode($_request->_subject));
        $_subject_code =  $_subject->curriculum_subject->subject->subject_code;
        if ($_subject_code == 'BRDGE') {
            $_students = $_subject->section->student_with_bdg_sections;
        } else {
            $_students = $_subject->section->student_sections;
        }
        return view('pages.teacher.subject-class.student_view', compact('_subject', '_students'));
    }
    public function subject_clearance(Request $_request)
    {
        $_subject = SubjectClass::find(base64_decode($_request->_subject));
        $_subject_code =  $_subject->curriculum_subject->subject->subject_code;
        if ($_subject_code == 'BRDGE') {
            $_students = $_subject->section->student_with_bdg_sections;
        } else {
            $_students = $_subject->section->student_sections;
        }
        return view('pages.teacher.subject-class.semestral_clearance', compact('_subject', '_students'));
    }
    public function subject_grading_view(Request $_request)
    {
        $_subject = SubjectClass::find(base64_decode($_request->_subject));
        $_subject_code =  $_subject->curriculum_subject->subject->subject_code;

        // Set the Student List
        if ($_subject_code == 'BRDGE') {
            $_students = $_subject->section->student_with_bdg_sections;
        } else {
            $_students = $_subject->section->student_sections;
        }
        // Viewing for Report and Grading Sheet
        $_columns = [['QUIZZES', 'Q', 10], ['ORAL EXAM', 'O', 5], ['R W - OUTPUT', 'R', 10], [request()->input('_period'),  strtoupper(request()->input('_period')[0]) . 'E', 1]];
        $_subject_code = $_subject->curriculum_subject->subject->subject_code;
        if ($_subject->curriculum_subject->subject->laboratory_hours > 0 /* && $_subject_code !=  str_contains($_subject_code, 'P.E.') */) {
            $_columns[] =  ['Scientific and Technical Experiments Demonstrations of Competencies Acquired', 'A', 10];
        }
        if ($_request->_preview) {
            // Report View
            $_report = new GradingSheetReport($_students, $_subject);
            return $_request->_form == "ad1" ? $_report->form_ad_01() : $_report->form_ad_02();
        } else {
            // Grading Sheet
            return view('pages.teacher.grading-sheet.view', compact('_subject', '_students', '_columns'));
        }
    }
    public function subject_view()
    {
        $_academics = AcademicYear::where('is_removed', false)->orderBy('id', 'desc')->get();
        return view('pages.teacher.subject_view', compact('_academics'));
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
            $_respond = array('success' => 'Score Updated', 'status' => 'success');
        } else {
            // Save Score
            $_score_details['score'] = doubleval($_request->_score);
            $_score_details['is_removed'] = 0;
            $_return = GradeEncode::create($_score_details);
            $_respond = array('success' => 'Score Saved', 'status' => 'success');
        }
        if ($_return) {
            return compact('_respond');
        }
    }
    public function subject_grade_submission(Request $_request)
    {
        $_subject = SubjectClass::find(Crypt::decrypt($_request->_subject));
        GradeSubmission::create([
            'subject_class_id' => $_subject->id,
            'form' => $_request->_form,
            'period' => $_request->_period
        ]);
        //Mail::to('developer@bma.edu.ph')->bcc('it@bma.edu.ph')->send(new GradeSubmissionMail($_subject));
        return back()->with('success', 'Successfully Submitted Grading Sheet...');
        //return back()->with('success', "Grade Sudmitted");
    }
    public function instructor_view(Request $_request)
    {
        $_subject = SubjectClass::find(Crypt::decrypt($_request->_subject));
        $_staff = Staff::find($_subject->staff_id);
        return view('pages.teacher.teacher_view', compact('_subject', '_staff'));
    }

    public function subject_report_view(Request $_request)
    {
        $_subject = SubjectClass::find(Crypt::decrypt($_request->_subject));
        $_students = StudentDetails::select('student_details.id', 'student_details.last_name', 'student_details.first_name')
            ->join('student_sections as ss', 'ss.student_id', 'student_details.id')
            ->where('ss.section_id', $_subject->section_id)
            ->orderBy('student_details.last_name', 'ASC')
            ->where('ss.is_removed', false)
            ->get();
        $_report = new GradingSheetReport($_students, $_subject);
        return $_request->_form == "ad1" ? $_report->form_ad_01() : $_report->form_ad_02();
    }
    public function subject_grade_bulk_upload(Request $_request)
    {
        $_section = SubjectClass::find(Crypt::decrypt($_request->_section));
        $_path = '/teacher/grade-sheet/' . $_section->academic->school_year . '/' . str_replace('/', '', $_section->section->section_name) . "/";
        $_file_name = $_path . str_replace(' ', '-', str_replace('/', '', $_section->section->section_name) . " " . $_section->curriculum_subject->subject->subject_name . date('dmyhis'));
        $_file_extention =  $_request->file('_file_grade')->getClientOriginalExtension();
        $_file_name = $_file_name . "." . $_file_extention;

        if ($_request->file('_file_grade')) {
            Storage::disk('public')->put($_file_name, fopen($_request->file('_file_grade'), 'r+'));
            Excel::import(new GradeImport($_request->_section), $_request->file('_file_grade'));
            return back()->with('success', 'Successfully Upload your Grades');
        }
    }
    public function subject_grade_export(Request $_request)
    {
        $_subject = SubjectClass::find(base64_decode($_request->_subject));
        $_students = StudentDetails::select('student_details.id', 'student_details.last_name', 'student_details.first_name', 'student_details.middle_name')
            ->join('student_sections as ss', 'ss.student_id', 'student_details.id')
            ->where('ss.section_id', $_subject->section_id)
            ->orderBy('student_details.last_name', 'ASC')
            ->where('ss.is_removed', false)
            ->get();
        $_file_name = $_subject->curriculum_subject->subject->subject_code . "-" . strtoupper(str_replace(' ', '-', str_replace('/', '', $_subject->section->section_name))) . '-EXPORT-GRADE-' . date('dmYhms');
        $_respond =  Excel::download(new GradeTemplate($_students, $_subject), $_file_name . '.xlsx', \Maatwebsite\Excel\Excel::XLSX); // Download the File 
        ob_end_clean();
        return $_respond;
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
                'staff_id' => Auth::user()->staff->id,
                'is_approved' => $_check, // nullable
                'is_removed' => 0
            );
            $_check_clearance = StudentClearance::where('student_id', $_student_id)->where('subject_class_id', $_subject_class)->where('is_removed', false)->first();
            if ($_check_clearance) {
                // If the Data is existing and the approved status id TRUE and the Input Tag is TRUE : They will remain

                // If the Data is existing and the apprvod status is FALSE and the Input is FALSE : Nothing to Do, They will remain
                // If comment is fillable
                if ($_check_clearance->is_approved == 0 && $_check == 0) {
                    if ($value['comment']) {
                        $_check_clearance->comments = $value['comment'];
                        $_check_clearance->save();
                    }
                    /*   $_check_clearance->is_removed = true;
                    $_check_clearance->save();
                    StudentClearance::create($_clearance); */
                }
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
            $_student = StudentDetails::find($_student_id);
            $_student->offical_clearance_cleared();
        }
        return back()->with('success', 'Successfully Submitted Clearance');
    }


    public function e_clearance_view(Request $_request)
    {
        $_current_academic =  $_request->_academic ? AcademicYear::find(base64_decode($_request->_academic)) : AcademicYear::where('is_active', 1)->first();
        $_academics = AcademicYear::where('is_removed', false)->orderBy('id', 'DESC')->get();
        $_sections = $_request->_academic ? Section::where('academic_id', base64_decode($_request->_academic))->where('course_id', 2)->orderBy('section_name', 'ASC')->get() :
            Section::where('academic_id', $_current_academic->id)->where('course_id', 2)->orderBy('section_name', 'ASC')->get();
        return view('pages.teacher.department-head.clearance.view', compact('_academics', '_current_academic', '_sections'));
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
        return view('pages.teacher.department-head.clearance.view_list', compact('_section', '_students'));
    }

    public function schedule_view(Request $_request)
    {
        $_subject = SubjectClass::find(base64_decode($_request->_subject));
        /*   $_students = StudentDetails::select('student_details.id', 'student_details.last_name', 'student_details.first_name')
            ->join('student_sections as ss', 'ss.student_id', 'student_details.id')
            ->where('ss.section_id', $_section->id)
            ->orderBy('student_details.last_name', 'ASC')
            ->where('ss.is_removed', false)
            ->get(); */
        return view('pages.teacher.subject-class.schedule_view', compact('_subject'));
    }
    public function subject_schedule_week_log_store(Request $_request)
    {
        $_request->validate([
            '_week' => 'required',
            '_topic.*' => "required"
        ]);
        return $_request;
    }
    # Create Subject Syllabus
    public function subject_create_syllabus(Request $_request)
    {
        try {
            $_subject = SubjectClass::find(base64_decode($_request->_subject));
            return view('pages.teacher.subject-class.course-syllabus.create-syllabus', compact('_subject'));
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }
    # Create Subject Syllabus
    public function subject_select_syllabus(Request $_request)
    {
        try {
            $_subject = SubjectClass::find(base64_decode($_request->_subject));
            $_course_syllabus = CourseSyllabus::where('subject_id', $_subject->curriculum_subject->subject->id)->get();
            return view('pages.teacher.course-syllabus.select-syllabus', compact('_course_syllabus', '_subject'));
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }
    public function subject_course_syllabus(Request $_request)
    {
        try {
            $_subject = SubjectClass::find(base64_decode($_request->_subject));
            $_content = array(
                'subject_id' => $_subject->id,
                'course_syllabus_id' => base64_decode($_request->course_syllabus)
            );
            SubjectClassCourseSyllabus::create($_content);
            return redirect(route('teacher.subject-view') . '?_subject=' . $_request->_subject);
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }
}
