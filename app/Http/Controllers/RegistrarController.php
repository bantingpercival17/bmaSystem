<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\CourseOffer;
use App\Models\Curriculum;
use App\Models\CurriculumSubject;
use App\Models\Section;
use App\Models\StudentDetails;
use App\Models\Subject;
use App\Models\SubjectClass;
use App\Models\User;
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
        return view('registrar.dashboard.view');
    }


    public function subject_view()
    {
        $_curriculum = Curriculum::where('is_removed', false)->get();
        $_academic = AcademicYear::where('is_removed', false)
            ->orderBy('id', 'DESC')
            ->get();
        return view('registrar.subjects.view', compact('_curriculum', '_academic'));
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
        return view('registrar.subjects.classes_view', compact('_academic', '_course', '_course_view', '_curriculum', '_section', '_teacher'));
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
        return view('registrar.subjects.curriculum_view', compact('_curriculum', '_course_view', '_course'));
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

        if ($_request->_course  || $_request->_academic || $_request->_student) {
            $_student_detials = new StudentDetails();
            $_students = $_request->_cadet ? $_student_detials->student_search($_request->_cadet) : [];
        } else {

            $_students = StudentDetails::where('is_removed', false)->orderBy('last_name', 'asc')->paginate(10);
        }
        return view('registrar.student.view', compact('_academics', '_course', '_students'));
    }
    public function student_profile_view(Request $_request)
    {
        $_student = StudentDetails::find(base64_decode($_request->_s));
        $_course = CourseOffer::where('is_removed', false)->get();
        return view('registrar.student.student_profile', compact('_student', '_course'));
    }


    // Section Panel 
    public function section_view(Request $_request)
    {
        $_course = CourseOffer::where('is_removed', false)->get();
        $_academic = AcademicYear::where('is_removed', false)->orderBy('id', 'DESC')->get();
        return view('registrar.sections.view', compact('_course', '_academic'));
    }
}
