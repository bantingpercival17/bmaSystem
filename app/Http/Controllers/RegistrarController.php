<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\CourseOffer;
use App\Models\Curriculum;
use App\Models\CurriculumSubject;
use App\Models\EnrollmentApplication;
use App\Models\EnrollmentAssessment;
use App\Models\Section;
use App\Models\StudentDetails;
use App\Models\StudentNonAcademicClearance;
use App\Models\Subject;
use App\Models\SubjectClass;
use App\Models\User;
use App\Report\Students\StudentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class RegistrarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('registrar');
    }
    public function index()
    {
        $_courses = CourseOffer::where('is_removed', false)->orderBy('id', 'desc')->get();
        return view('pages.registrar.dashboard.view', compact('_courses'));
    }

    /* Enrollment Panel */
    public function enrollment_view(Request $_request)
    {
        $_courses = CourseOffer::where('is_removed', false)->get();
        $_student_detials = new StudentDetails();
        $_students = $_request->_student ? $_student_detials->student_search($_request->_student) : $_student_detials->enrollment_application_list();
        return view('pages.registrar.enrollment.view', compact('_courses', '_students'));
    }
    public function enrollment_assessment(Request $_request)
    {
        $_student = StudentDetails::find(base64_decode($_request->_student));
        $_current_assessment = $_student->enrollment_assessment;
        $_year_level = Auth::user()->staff->current_academic()->semestral == 'First Semester' ? intval($_current_assessment->year_level) + 1 : intval($_current_assessment->year_level);
        if (count($_student->enrollment_history) > 0) {
            // Old Student 
            // If the Student is Incoming 4th class and have a previous Enrollment Assessment first check the Year level if the year level is Equal to 13 the Student will equvalet into 4th class

            if ($_year_level == 13) {
                // This Will be incoming 4th Class

                // Create a new Student number and Student Account
            }
            $_assessment = EnrollmentAssessment::where('student_id', $_student->id)->where('academic_id', Auth::user()->staff->current_academic()->id)->first();
            if (!$_assessment) {
                // Store Enrollment Assessment
                $_assessment_details = [
                    "student_id" => $_student->id,
                    "academic_id" => Auth::user()->staff->current_academic()->id,
                    "course_id" => $_student->enrollment_assessment->course_id,
                    "curriculum_id" => $_student->enrollment_assessment->curriculum_id,
                    "year_level" => strval($_year_level),
                    "bridging_program" => "without",
                    "staff_id" => Auth::user()->id,
                    "is_removed" => 0
                ];
                EnrollmentAssessment::create($_assessment_details); // Saved Enrollment Assessment
                // Update Enrollment Application
                if ($_student->enrollment_application) { // If Online Enrollee Update Data
                    $_student->enrollment_assessment->staff_id = Auth::user()->staff->id;
                    $_student->enrollment_assessment->is_approved = 1;
                    $_student->enrollment_assessment->save();
                } else { // If Onsite Enrollee Store Data
                    $_details = [
                        'student_id' => $_student->id,
                        'academic_id' => Auth::user()->staff->current_academic()->id->id,
                        'enrollment_place' => 'onsite',
                        'staff_id' => Auth::user()->staff->id,
                        'is_approved' => 1,
                        'is_removed' => false,
                    ];
                    EnrollmentApplication::create($_details);
                }

                return back()->with('success', 'Transaction Successfully.');
            } else {
                if ($_student->enrollment_application) { // If Online Enrollee Update Data
                    $_student->enrollment_application->staff_id = Auth::user()->staff->id;
                    $_student->enrollment_application->is_approved = 1;
                    $_student->enrollment_application->save();
                } else { // If Onsite Enrollee Store Data
                    $_details = [
                        'student_id' => $_student->id,
                        'academic_id' => Auth::user()->staff->current_academic()->id->id,
                        'enrollment_place' => 'onsite',
                        'staff_id' => Auth::user()->staff->id,
                        'is_approved' => 1,
                        'is_removed' => false,
                    ];
                    EnrollmentApplication::create($_details);
                }
                return back()->with('error', 'This is already Saved');
            }

            $_student->enrollment_assessment;
        } else {
            // New Student
        }
    }

    public function enrolled_list_view(Request $_request)
    {
        $_course = CourseOffer::find(base64_decode($_request->_course));
        $_students = $_course->enrollment_list;
        //return $_course->enrolled_list(Auth::user()->staff->current_academic()->id)->count();
        return view('pages.registrar.enrollment.enrolled_list_view', compact('_course', '_students'));
    }

    public function student_clearance(Request $_request)
    {
        $_student = StudentDetails::find(base64_decode($_request->_student));
        $_academic = $_student->enrollment_assessment->academic_id;
        $_section = $_student->section($_academic)->first();
        $_subject_class = $_section ? SubjectClass::where('section_id', $_section->section_id)->where('is_removed', false)->get() : [];
        return view('pages.registrar.enrollment.clearance', compact('_student', '_subject_class'));
    }
    public function subject_view()
    {
        $_curriculum = Curriculum::where('is_removed', false)->get();
        $_academic = AcademicYear::where('is_removed', false)
            ->orderBy('id', 'DESC')
            ->get();
        return view('pages.registrar.subjects.view', compact('_curriculum', '_academic'));
    }


    // Subject Panel
    public function classes_view(Request $_request)
    {
        $_academic = AcademicYear::find(base64_decode($_request->_view)); // Get the selected Academic Year
        $_course_view = $_request->_d ? CourseOffer::find(Crypt::decrypt($_request->_d)) : []; // Get Course Type
        $_course = CourseOffer::where('is_removed', false)->get(); // Get All the Course
        $_curriculum = Curriculum::where('is_removed', false)->get(); // Get all the Curriculum
        $_section = Section::where('academic_id', $_academic)
            ->where('is_removed', false)
            ->get(); // Get all Section base on the Academic Year
        $_teacher = User::select('users.id', 'users.name')
            ->join('role_user', 'users.id', 'role_user.user_id')
            /* ->where('role_user.role_id', 6) */
            ->get(); // Get All the Teachers
        return view('pages.registrar.subjects.classes_view', compact('_academic', '_course', '_course_view', '_curriculum', '_section', '_teacher'));
    }
    public function classes_store(Request $_request)
    {
        $_subject_class_detail = [
            'staff_id' => $_request->_teacher,
            'curriculum_subject_id' => $_request->_subject,
            'academic_id' => $_request->_academic,
            'section_id' => $_request->_section,
            'created_by' => Auth::user()->name,
            'is_removed' => 0,
        ];
        SubjectClass::create($_subject_class_detail);
        return back()->with('message', 'Successfully Created Subject Classes!');
    }
    public function classes_removed(Request $_request)
    {
        $_subject_class = SubjectClass::find(base64_decode($_request->_c));
        $_subject_class->is_removed = 1;
        $_subject_class->save();
        return back()->with('message', 'Successfully Removed Subject Classes!');
    }
    public function curriculum_view(Request $_request)
    {
        $_curriculum = base64_decode($_request->view);
        $_course = $_request->d ? base64_decode($_request->d) : '';
        $_curriculum = Curriculum::find($_curriculum);
        $_course_view = CourseOffer::where('is_removed', false)->get();
        $_course = $_request->d ? CourseOffer::find($_course) : $_course_view;
        $_couuse_subject = $_request->d ? CurriculumSubject::where('course_id', $_course->id)->get() : '';
        return view('pages.registrar.subjects.curriculum_view', compact('_curriculum', '_course_view', '_course'));
    }
    public function curriculum_subject_store(Request $_request)
    {
        $_request->validate([
            '_input_1' => 'required',
            '_input_2' => 'required',
            '_input_3' => 'required',
            '_input_4' => 'required',
            '_input_5' => 'required'
        ]);
        $_subject = [
            'subject_code' => strtoupper($_request->_input_1),
            'subject_name' => strtoupper($_request->_input_2),
            'lecture_hours' => $_request->_input_3,
            'laboratory_hours' => $_request->_input_4,
            'units' => $_request->_input_5,
            'created_by' => Auth::user()->name,
            'is_removed' => 1,
        ]; // Set all the input need to the Subject Details
        $_verify = Subject::where('subject_code', $_request->_input_1)->first(); // Verify if the Subject is Excited
        $_subject = $_verify ?: Subject::create($_subject); // Save Subject or Get Subject

        $_course_subject_details = [
            'curriculum_id' => Crypt::decrypt($_request->_input_0),
            'subject_id' => $_subject->id,
            'course_id' => $_request->_input_6,
            'year_level' => $_request->_input_7,
            'semester' => $_request->_input_8,
            'created_by' => Auth::user()->name,
            'is_removed' => 1,
        ]; // Subject Course Details
        CurriculumSubject::create($_course_subject_details); // Create a Subject Course
        return $_request->_input_ ? back()->with('message', 'Successfully Created Subject') : redirect('/registrar/subjects/curriculum?view=' . $_request->_input_0 . '&d=' . base64_encode($_request->_input_6))->with('message', 'Successfully Created Subject');
    }


    // Student
    public function student_list_view(Request $_request)
    {
        $_academics = AcademicYear::where('is_removed', false)->get();
        $_course = CourseOffer::where('is_removed', false)->get();
        $_student_detials = new StudentDetails();
        $_students = $_request->_student ? $_student_detials->student_search($_request->_student) :  StudentDetails::where('is_removed', false)->orderBy('last_name', 'asc')->paginate(10);
        return view('pages.registrar.student.view', compact('_academics', '_course', '_students'));
    }
    public function student_profile_view(Request $_request)
    {
        $_student = StudentDetails::find(base64_decode($_request->_student));
        $_course = CourseOffer::where('is_removed', false)->get();
        return view('pages.registrar.student.student_profile', compact('_student', '_course'));
    }
    public function student_information_report(Request $_request)
    {
        // $_data = EnrollmentAssessment::find(base64_decode($_request->_assessment));
        $_student_report = new StudentReport();;
        return $_student_report->enrollment_information(base64_decode($_request->_assessment));
    }

    // Section Panel 
    public function section_view(Request $_request)
    {
        $_course = CourseOffer::where('is_removed', false)->get();
        $_academic = AcademicYear::where('is_removed', false)->orderBy('id', 'DESC')->get();
        return view('pages.registrar.sections.view', compact('_course', '_academic'));
    }

    // E-clearance
    public function clearance_view(Request $_request)
    {
        $_enrollment = EnrollmentAssessment::where('academic_id', Auth::user()->staff->current_academic()->id)->get();
        return view('pages.registrar.clearance.view', compact('_enrollment'));
    }
    public function clearance_store(Request $_request)
    {
        foreach ($_request->data as $key => $value) {
            $_student_id = base64_decode($value['sId']);
            $_clearance_data = $_request->_clearance_data;
            // Check if the student is Store
            $_check = count($value) > 2 ? 1 : 0;
            $_clearance = array(
                'student_id' => $_student_id,
                'non_academic_type' => $_clearance_data,
                'comments' => $value['comment'], // nullable
                'staff_id' => Auth::user()->staff->id,
                'is_approved' => $_check, // nullable
                'is_removed' => 0
            );
            $_check_clearance = StudentNonAcademicClearance::where('student_id', $_student_id)->where('non_academic_type', $_clearance_data)->where('is_removed', false)->first();
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
        return back()->with('success', 'Successfully Submitted Clearance');
    }
}
