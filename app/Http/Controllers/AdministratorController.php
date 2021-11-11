<?php

namespace App\Http\Controllers;

use App\Imports\StaffImport;
use App\Imports\StudentSection as ImportsStudentSection;
use App\Imports\SubjectHandle;
use App\Models\AcademicYear;
use App\Models\CourseOffer;
use App\Models\Curriculum;
use App\Models\CurriculumSubject;
use App\Models\EducationalDetails;
use App\Models\EnrollmentAssessment;
use App\Models\ParentDetails;
use App\Models\PaymentAssessment;
use App\Models\PaymentTransaction;
use App\Models\Role;
use App\Models\Section;
use App\Models\Staff;
use App\Models\StudentAccount;
use App\Models\StudentDetails;
use App\Models\StudentSection;
use App\Models\Subject;
use App\Models\SubjectClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;

class AdministratorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('administrator');
        set_time_limit(0);
    }
    public function index()
    {
        $_academics = AcademicYear::where('is_removed', false)->get();
        $_course = CourseOffer::where('is_removed', false)->get();
        return view('administrator.dashboard', compact('_academics', '_course'));
    }

    /* Students */
    public function student_view()
    {
        $_academics = AcademicYear::where('is_removed', false)->get();
        $_course = CourseOffer::where('is_removed', false)->get();
        $_students = StudentDetails::where('is_removed', false)->paginate(10);
        return view('administrator.student_view', compact('_academics', '_course', '_students'));
    } /* View Student  */

    public function student_imports(Request $_request)
    {
        $_files = json_decode(file_get_contents($_request->file('_file')));
        $_student_model = new StudentDetails();
        //return dd($_files);
        foreach ($_files as $key => $_file) {
            $_student = StudentAccount::where('student_number', $_file->student_details->student_number)->first();
            if (!$_student) {
                $_student_model->student_single_file_import($_file);
            }
        }
        //$_student_model->student_single_file_import($_file);
        return back();
    }

    /* Accounts */
    public function account_view()
    {
        $_employees = Staff::orderBy('last_name', 'asc')->get();
        //$_accounts = User::join('staff', 'staff.user_id', 'users.id')->orderBy('staff.last_name', 'asc')->get();
        $_role = Role::all();
        return view('administrator.accounts_view', compact('_employees', '_role'));
    }
    public function account_store(Request $_request)
    {
        if ($_request->file('_file')) {
            Excel::import(new StaffImport(), $_request->file('_file'));
            return back()->with('message', 'Successfully Upload the Account');
        } else {
            $_first_name = mb_strtolower($_request->fname);
            $_last_name = mb_strtolower($_request->lname);
            $_middle_name = $_request->mname ? mb_strtolower($_request->mname) : 'n/a';
            $_password = $_last_name . '.' . $_first_name;
            $_email = $_first_name[0] . '.' . str_replace(' ', '', $_last_name) . "@bma.edu.ph";
            // Create User
            $_user_store = [
                'name' => ucwords($_first_name . ' ' . $_last_name),
                'email' => trim($_request->email),
                'password' => Hash::make('bmafaculty'),
                'password-' => str_replace(' ', '', $_password),
            ];
            $_user = User::where('email', trim($_request->email))->first();
            $_user = $_user ?: User::create($_user_store);
            // Create Role
            $_user->attachRole($_request->role);
            // Create Staff
            $_staff = [
                'user_id' => $_user->id,
                'staff_no' => $_request->staff_no,
                'first_name' => ucwords($_first_name),
                'last_name' => ucwords($_last_name),
                'middle_name' => ucwords($_middle_name),
                'job_description' => $_request->job_description,
                'department' => $_request->department,
                'created_by' => Auth::user()->name,
            ];
            Staff::create($_staff);

            return back()->with('message', 'Successfully Created Account');
        }
    }
    /* /Accounts */

    /* Curriculum */
    public function subject_view()
    {
        $_curriculum = Curriculum::where('is_removed', false)->get();
        $_academic = AcademicYear::where('is_removed', false)
            ->orderBy('id', 'DESC')
            ->get();
        return view('administrator.subjects_view', compact('_curriculum', '_academic'));
    }
    public function curriculum_store(Request $_request)
    {
        // Add a Curriculum
        $_data = [
            'curriculum_name' => $_request->input('curriculum_name'),
            'curriculum_year' => $_request->input('effective_year'),
            'created_by' => Auth::user()->name,
            'is_removed' => 0,
        ];
        Curriculum::create($_data);
        return back()->with('message', 'Successfully Created Curriculum');
    }
    public function curriculum_view(Request $_request)
    {
        $_curriculum = Crypt::decrypt($_request->_c);
        $_course = $_request->_d ? Crypt::decrypt($_request->_d) : '';
        $_curriculum = Curriculum::find($_curriculum);
        $_course_view = CourseOffer::where('is_removed', false)->get();
        $_course = $_request->_d ? CourseOffer::find($_course) : $_course_view;
        $_couuse_subject = $_request->_d ? CurriculumSubject::where('course_id', $_course->id)->get() : '';
        return view('administrator.curriculum_view', compact('_curriculum', '_course_view', '_course'));
        // return $_curriculum;
    }
    public function subject_store(Request $_request)
    {
        $_subject = [
            //'curriculum_id' => Crypt::decrypt($_request->_input_0),
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
        return $_request->_input_ ? back()->with('message', 'Successfully Created Subject') : redirect('/administrator/subjects/curriculum?_c=' . $_request->_input_0 . '&_d=' . Crypt::encrypt($_request->_input_6))->with('message', 'Successfully Created Subject');
    }
    public function subject_class(Request $_request)
    {
        $_academic = AcademicYear::find(Crypt::decrypt($_request->_c)); // Get the selected Academic Year
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
        return view('administrator.subject_class_view', compact('_academic', '_course', '_course_view', '_curriculum', '_section', '_teacher'));
    }
    public function subject_class_store(Request $_request)
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
    public function subject_import(Request $_request)
    {
        $_academic = AcademicYear::find(Crypt::decrypt($_request->_academic));
        Excel::import(new SubjectHandle($_academic), $_request->file('_file'));
    }
    /* Classes & Subject Section */
    public function classes_view()
    {
        $_course = CourseOffer::where('is_removed', false)->get();
        $_academic = AcademicYear::where('is_removed', false)->get();
        return view('administrator.classess_view', compact('_course', '_academic'));
    }
    public function classes_store(Request $_request)
    {
        $_course_name = $_request->_course == 1 ? 'ME' : 'MT';
        $_section_details = [
            'section_name' => $_request->_level . ' ' . $_course_name . ' ' . $_request->_section,
            'academic_id' => $_request->_academic,
            'course_id' => $_request->_course,
            'year_level' => $_request->_level,
            'created_by' => Auth::user()->name,
            'is_removed' => 0,
        ];
        Section::create($_section_details);
        return back()->with('message', 'Successfully Created Section');
    }
    public function class_section_view(Request $_request)
    {
        $_section = Crypt::decrypt($_request->_cs);
        $_section = Section::find($_section);
        // $_students = StudentSection::where('section_id', $_section->id)->where('is_removed', false)->get();
        $_students = StudentDetails::select('student_details.id', 'student_details.first_name', 'student_details.last_name')
            ->join('student_sections as ss', 'ss.student_id', 'student_details.id')
            ->join('student_accounts as sa', 'sa.student_id', 'student_details.id')
            ->where(['ss.section_id' => $_section->id, 'ss.is_removed' => false]);
        $_students = $_request->_student
            ? $_students
            ->where('student_details.last_name', 'like', '%' . $_request->student)
            ->orderBy('sa.student_number', 'ASC')
            ->get()
            : $_students->orderBy('sa.student_number', 'ASC')->get();
        $_student_enrollment = StudentDetails::select('student_details.id', 'student_details.first_name', 'student_details.last_name', 'ea.year_level')
            ->join('enrollment_assessments as ea', 'ea.student_id', 'student_details.id')
            ->join('student_accounts as sa', 'sa.student_id', 'student_details.id')
            ->where('ea.academic_id', $_section->academic_id)
            ->where('ea.course_id', $_section->course_id)
            ->where('ea.year_level', str_replace('/C', '', $_section->year_level))
            ->orderBy('student_details.last_name');
        $_add_students = $_request->_student
            ? $_student_enrollment
            ->where('student_details.last_name', 'like', '%' . $_request->_student)
            ->orderBy('sa.student_number', 'ASC')
            ->get()
            : $_student_enrollment->orderBy('sa.student_number', 'ASC')->get();
        return view('administrator.section_view', compact('_section', '_students', '_add_students'));
    }
    public function section_add(Request $_request)
    {
        StudentSection::create([
            'student_id' => Crypt::decrypt($_request->_student),
            'section_id' => Crypt::decrypt($_request->_cs),
            'created_by' => Auth::user()->name,
            'is_removed' => 0,
        ]);
        $_section = Section::find(Crypt::decrypt($_request->_cs));
        return back()->with('message', 'Successfully Added to ' . $_section->section_name);
    }
    public function section_remove(Request $_request)
    {
        /* StudentSection::create([
            'student_id' => Crypt::decrypt($_request->_student),
            'section_id' => Crypt::decrypt($_request->_cs),
            'created_by' => Auth::user()->name,
            'is_removed' => 0,
        ]); */
        // return Crypt::decrypt($_request->_cs);
        StudentSection::find(Crypt::decrypt($_request->_cs))->update(['is_removed' => 1]);
        $_section = StudentSection::find(Crypt::decrypt($_request->_cs));
        return back()->with('message', 'Successfully Removed to ' . $_section->section->section_name);
    }
    public function section_import(Request $_request)
    {
        Excel::import(new ImportsStudentSection($_request->_data_1, $_request->_data_2), $_request->file('_file'));
        return back()->with('message', 'Successfully Upload Section ');
    }
    /*  Class */

    /* Enrollment */
    public function enrollment_view(Request $_request)
    {
        return view('administrator.enrollment.view');
    }
    public function qr_generator($_data)
    {
        $_employee = Staff::find($_data);
        $_data = strtolower(str_replace(' ', '-', trim($_employee->first_name . ' ' . $_employee->last_name)));
        $pdf = PDF::loadView("employee.qr_generate", compact('_employee'));
        $file_name = strtoupper('Qr code generate: ' . $_data);
        return $pdf->setPaper([0, 0, 285.00, 250.00], 'landscape')->stream($file_name . '.pdf');
    }
}
