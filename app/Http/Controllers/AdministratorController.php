<?php

namespace App\Http\Controllers;

use App\Exports\CourseStudentEnrolled;
use App\Exports\EnrolledStudentList;
use App\Imports\ImportExamination;
use App\Imports\ImportExaminationV2;
use App\Imports\ShipboardInformationImport;
use App\Imports\StaffImport;
use App\Imports\StudentGadgetImport;
use App\Imports\StudentInformationImport;
use App\Imports\StudentSection as ImportsStudentSection;
use App\Imports\SubjectHandle;
use App\Mail\ApplicantEmail;
use App\Mail\StudentEnrollmentMail;
use App\Models\AcademicYear;
use App\Models\ApplicantAccount;
use App\Models\ApplicantDocuments;
use App\Models\ComprehensiveExamination;
use App\Models\ComprehensiveExaminationScheduled;
use App\Models\CourseOffer;
use App\Models\Curriculum;
use App\Models\CurriculumSubject;
use App\Models\DebugReport;
use App\Models\Department;
use App\Models\Documents;
use App\Models\EducationalDetails;
use App\Models\EnrollmentAssessment;
use App\Models\Examination;
use App\Models\ExaminationCategory;
use App\Models\ParentDetails;
use App\Models\PaymentAssessment;
use App\Models\PaymentTransaction;
use App\Models\Role;
use App\Models\Section;
use App\Models\Staff;
use App\Models\StaffPictures;
use App\Models\StaffProfiles;
use App\Models\StudentAccount;
use App\Models\StudentDetails;
use App\Models\StudentGadget;
use App\Models\StudentNonAcademicClearance;
use App\Models\StudentPasswordReset;
use App\Models\StudentSection;
use App\Models\Subject;
use App\Models\SubjectClass;
use App\Models\Task;
use App\Models\ThirdDatabase\MobileApplicationDetails;
use App\Models\ThirdDatabase\MobileApplicationVersions;
use App\Models\User;
use App\Models\UserPasswordReset;
use App\Report\AttendanceSheetReport;
use App\Report\OnboardTrainingReport;
use App\Report\StudentListReport;
use App\Report\StudentLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;
use Exception;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

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
        $_courses = CourseOffer::where('is_removed', false)->orderBy('id', 'desc')->get();
        $_total_population = Auth::user()->staff->enrollment_count();
        $_total_applicants = ApplicantAccount::join(env('DB_DATABASE_SECOND') . '.applicant_detials', env('DB_DATABASE_SECOND') . '.applicant_detials.applicant_id', 'applicant_accounts.id')->where('academic_id', Auth::user()->staff->current_academic()->id)->where('applicant_accounts.is_removed', false)->get();
        return view('pages.administrator.dashboard', compact('_academics', '_courses', '_total_population', '_total_applicants'));
    }

    /* Students */
    public function student_view(Request $_request)
    {
        $_academics = AcademicYear::where('is_removed', false)->get();
        $_course = CourseOffer::where('is_removed', false)->get();
        if ($_request->_course  || $_request->_academic || $_request->_student) {
            $_student_detials = new StudentDetails();
            $_students = $_request->_student ? $_student_detials->student_search($_request->_student) : [];
            //return $_students;
        } else {
            $_students = StudentDetails::where('is_removed', false)->orderBy('last_name', 'asc')->paginate(10);
        }
        $sectionLists = Section::where('academic_id', Auth::user()->staff->current_academic()->id)->get();
        //return $_students;
        //$_students = StudentDetails::where('is_removed', false)->orderBy('last_name', 'asc')->paginate(10);
        return view('pages.administrator.student.view', compact('_academics', '_course', '_students', 'sectionLists'));
    } /* View Student  */
    public function student_profile(Request $_request)
    {
        $_student = StudentDetails::find(base64_decode($_request->student));
        if ($_request->_course  || $_request->_academic || $_request->search_student) {
            $_student_detials = new StudentDetails();
            $_students = $_request->search_student ? $_student_detials->student_search($_request->search_student) : [];
            //return $_students;
        } else {
            $_students = StudentDetails::where('is_removed', false)->orderBy('last_name', 'asc')->paginate(10);
        }
        return view('pages.administrator.student.profile', compact('_student', '_students'));
    }
    public function student_imports(Request $_request)
    {
        try {
            if ($_request->_file_type == 'json') {
                $_files = json_decode(file_get_contents($_request->file('_file')));
                $_student_model = new StudentDetails();
                foreach ($_files as $key => $_file) {
                    $_student_model->upload_student_details($_file);
                }
            }
            if ($_request->_file_type == 'excel') {
                Excel::import(new StudentInformationImport, $_request->file('_file'));
            }
            if ($_request->_file_type == 'shipboard') {
                Excel::import(new ShipboardInformationImport, $_request->file('_file'));
            }
            echo "<a href=" . route('admin.students') . ">BACK</a>";
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
        }

        //return back()->with('message', 'Successfully Upload Student Details');
    }
    public function student_reset_password(Request $_request)
    {
        $student = StudentDetails::find(base64_decode($_request->_student));
        $_student_accounts = StudentAccount::where('is_removed', false)->find(base64_decode($_request->_student));
        $length = 5;
        $_password = 'BMA-' . substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
        $student->account->password = Hash::make($_password);
        $student->account->save();
        StudentPasswordReset::create([
            'student_id' => $student->account->student_id,
            'password_string' => $_password,
            'is_status' => 'reset-password',
            'is_removed' => false,
        ]);

        return back()->with('reset-password', 'Successsfully Password Reset : ' . $_password);
    }
    public function student_account(Request $_request)
    {
        try {
            $_student = StudentDetails::find(base64_decode($_request->student));
            $_account_content = array(
                'student_id' => $_student->id,
                'student_number' => $_request->student_number,
                'email' => $_request->student_number . '.' . str_replace(' ', ' ', strtolower($_student->last_name)) . '@bma.edu.ph',
                'personal_email' => $_request->personal_email ?: '',
                'is_actived' => true,
                'is_removed' => false
            );
            StudentAccount::create($_account_content);
            return back()->with('success', 'Successfuly Created Account');
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
    }
    /* Accounts */
    public function account_view()
    {
        $_employees = Staff::orderBy('last_name', 'asc')/* ->where('is_removed', false) */->get();
        $_role = Role::all();
        return view('pages.administrator.accounts_view', compact('_employees', '_role'));
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

    public function account_upload_profile(Request $_request)
    {
        $_staff = Staff::find($_request->_id);
        $_file_image_name = strtolower(trim(str_replace(' ', '_', $_staff->user->name))) . '.jpg';
        $_request->validate([
            '_file' => 'mimes:doc,pdf,docx,zip,jpeg,png,jpg,gif,svg',
        ]);
        if ($file = $_request->hasFile('_file')) {

            $file = $_request->file('_file');
            //$fileName = $file->getClientOriginalName();
            $destinationPath = public_path() . '/assets/img/staff';
            $file->move($destinationPath,   $_file_image_name);
            return back()->with('message', 'Successfully Upload image');
        }
        // return $_file_image_name;
    }
    function staff_upload_profile(Request $request)
    {
        $request->validate([
            'file' => 'mimes:jpeg,png,jpg',
        ]);
        try {
            if ($file = $request->hasFile('file')) {

                $filename =  '/image/' . base64_encode($request->staff) . "/" . time() . '.' . $request->file->getClientOriginalExtension();
                // File Path Format: $_path.'/'.student-number.'/'.$_folder
                $path = 'bma-employee';
                // Using Storage facade to store the file
                Storage::disk($path)->put($filename, fopen($request->file, 'r+'));
                // Generating the URL for the stored file
                //$url = URL::to('/') . '/storage/' . $path . '/' . $filename;
                /* $link = $this->office_file_save($request->file, 'public', 'employee', 'picture/' . base64_encode($request->staff));
                 */
                $filename =  'employee/' . $filename;
                StaffPictures::create([
                    'staff_id' => $request->staff, 'image_path' => $filename
                ]);
                /* $file = $request->file('file');
                //$fileName = $file->getClientOriginalName();
                $destinationPath = public_path() . '/assets/img/staff';
                $file->move($destinationPath,   $_file_image_name); */
                return back()->with('message', 'Successfuly Uploaded.');
            }
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function account_roles_store(Request $_request)
    {
        $_user = User::find($_request->id);
        $_user->attachRole($_request->_role);
        return back()->with('message', 'Successfuly Added a new Role');
    }
    /* /Accounts */

    /* Curriculum */
    public function subject_view()
    {
        $_curriculum = Curriculum::where('is_removed', false)->get();
        $_academic = AcademicYear::where('is_removed', false)
            ->orderBy('id', 'DESC')
            ->get();
        return view('pages.administrator.subjects_view', compact('_curriculum', '_academic'));
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
        return view('pages.administrator.curriculum_view', compact('_curriculum', '_course_view', '_course'));
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
        return view('pages.administrator.subject_class_view', compact('_academic', '_course', '_course_view', '_curriculum', '_section', '_teacher'));
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
    public function subject_class_remove(Request $_request)
    {
        $_subject_class = SubjectClass::find(Crypt::decrypt($_request->_c));
        $_subject_class->is_removed = 1;
        $_subject_class->save();
        return back()->with('message', 'Successfully Removed Subject Classes!');
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
        $_academic = AcademicYear::where('is_removed', false)->orderBy('id', 'DESC')->get();
        return view('pages.administrator.classess_view', compact('_course', '_academic'));
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
        $_section = Section::find(Crypt::decrypt($_request->_cs)); // Get Section Details
        $_students = StudentDetails::select('student_details.id', 'student_details.first_name', 'student_details.last_name')
            ->join('student_sections as ss', 'ss.student_id', 'student_details.id')
            ->join('student_accounts as sa', 'sa.student_id', 'student_details.id')
            /* ->orderBy('student_details.last_name', 'asc') */
            ->where(['ss.section_id' => $_section->id, 'ss.is_removed' => false]); // Get All the Student in a Section

        $_students = $_request->_student
            ? $_students
            ->where('student_details.last_name', 'like', '%' . $_request->_student . "%")
            ->orderBy('student_details.last_name', 'ASC')
            ->get()
            : $_students->orderBy('student_details.last_name', 'ASC')->get();


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
        return view('pages.administrator.section_view', compact('_section', '_students', '_add_students'));
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
        StudentSection::find(Crypt::decrypt($_request->_cs))->update(['is_removed' => 1]);
        $_section = StudentSection::find(Crypt::decrypt($_request->_cs));
        return back()->with('message', 'Successfully Removed to ' . $_section->section->section_name);
    }
    public function section_import(Request $_request)
    {
        Excel::import(new ImportsStudentSection($_request->_data_1, $_request->_data_2), $_request->file('_file'));
        return back()->with('message', 'Successfully Upload Section ');
    }
    public function section_report_list(Request $_request)
    {
        $_report = new StudentListReport();
        return $_report->student_section_list([
            'course_id' => Crypt::decrypt($_request->_c),
            'year_level' => Crypt::decrypt($_request->_l),
            'academic_id' => Crypt::decrypt($_request->_a),
            'is_removed' => false
        ]);
    }
    /*  Class */

    /* Semestral Clearance */
    public function clearance_view(Request $_request)
    {
        $_courses = CourseOffer::all();
        $_sections = $_request->_course ? Section::where('course_id', base64_decode($_request->_course))->where('is_removed', false)->where('academic_id', Auth::user()->staff->current_academic()->id)->orderBy('section_name', 'desc')->get() : [];
        return view('pages.administrator.semestral-clearance.view', compact('_courses', '_sections'));
    }
    public function semestral_student_list_view(Request $_request)
    {
        $_section = Section::find(base64_decode($_request->_section));
        return view('pages.administrator.semestral-clearance.student_section', compact('_section'));
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
                'academic_id' => $_request->_academic,
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
            $_student = StudentDetails::find($_student_id);
            $_student->offical_clearance_cleared();
        }
        return back()->with('success', 'Successfully Submitted Clearance');
    }

    // Employee
    public function employee_profile(Request $_request)
    {
        $_staff = Staff::find(base64_decode($_request->employee));
        $_roles = Role::all();
        return view('pages.administrator.employee.view', compact('_staff', '_roles'));
    }
    public function qr_generator($_data)
    {
        $_employee = Staff::find($_data);
        $_data = strtolower(str_replace(' ', '-', trim($_employee->first_name . ' ' . $_employee->last_name)));
        $pdf = PDF::loadView("employee.qr_generate", compact('_employee'));
        $file_name = strtoupper('Qr code generate: ' . $_data);
        return $pdf->setPaper([0, 0, 285.00, 250.00], 'landscape')->stream($file_name . '.pdf');
    }

    public function employee_reset_password(Request $_request)
    {
        $_staff = Staff::find(base64_decode($_request->_employee));
        $length = 5;
        $_password = 'BMA-' . substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
        $_staff->user->password = HasH::make($_password);
        $_staff->user->save();
        UserPasswordReset::create([
            'user_id' => $_staff->user->id,
            'password_string' => $_password,
            'is_status' => 'reset-password',
            'is_removed' => false,
        ]);

        return back()->with('reset-password', 'Successsfully Password Reset : ' . $_password);
    }
    // Attendance
    public function attendance_view()
    {
        $_employees = Staff::where('is_removed', false)->orderBy('staff.department', 'asc')
            ->orderBy('staff.last_name', 'asc')->get();
        return view('pages.administrator.employee.attendance', compact('_employees'));
    }
    public function attendance_report(Request $_request)
    {
        $_report = new AttendanceSheetReport();
        $_report_pdf = $_request->r_view == 'daily' ? $_report->daily_report() : $_report->daily_time_record_report($_request->start_date, $_request->end_date);
        $_report_pdf = $_request->r_view == 'health_check' ? $_report->health_check() : $_report_pdf;

        return $_report_pdf;
    }


    /* Setting */
    public function setting_view(Request $_request)
    {
        $_roles = Role::all();
        $_academic = AcademicYear::where('is_removed', false)->orderBy('id', 'desc')->get();
        $_department = Department::all();
        $_documents = Documents::where('is_removed', false)->get();
        return view('pages.administrator.setting.view', compact('_roles', '_academic', '_department', '_documents'));
    }
    // Academic
    public function store_academic(Request $_request)
    {
        $_current_academic = AcademicYear::where('is_active', true)->first();
        $_current_academic->is_active = false;
        $_current_academic->save();
        AcademicYear::create([
            'school_year' => $_request->_school_year,
            'semester' => $_request->semester,
            'is_active' => true,
            'is_removed' => false
        ]);

        return back()->with('success', 'Successfully Created');
    }
    // Roles
    public function store_role(Request $_request)
    {
        $_details = array(
            'name' => str_replace(' ', '-', strtolower($_request->_role_name)),
            'display_name' => ucwords($_request->_role_name),
            'description' => str_replace(' ', '-', strtolower($_request->_role_name)),
        );
        Role::create($_details);
        return back()->with('success', 'Successfully Created');
    }

    public function student_handbook_logs(Request $_request)
    {
        $_academic = AcademicYear::find(base64_decode($_request->_academic));
        $_report = new StudentLogs();
        return $_report->student_handbook_logs($_academic);
        return $_academic->enrollment_list;
    }
    // Academic Year Store

    // Documents
    public function store_documents(Request $_request)
    {
        $_field = $_request->validate([
            'document_name' => 'required|string',
            'document_details' => 'required|string',
            'document_propose' => 'required|string',
            'year_level' => 'required',
            'department' => 'required',
        ]);
        $_data = array(
            'document_name' => $_field['document_name'],
            'document_details' => $_field['document_details'],
            'document_propose' => $_field['document_propose'],
            'year_level' => $_field['year_level'],
            'department_id' => $_field['department'],
            'is_removed' => false
        );
        //return $_data;
        Documents::create($_data);
        return back()->with('success', 'Successfuly Created');
    }
    /* Applicant Panel */
    public function applicant_view(Request $_request)
    {
        $_courses = CourseOffer::all();
        $_course = CourseOffer::find(base64_decode($_request->_course));
        $_applicants =  $_course->student_applicants;

        return view('pages.general-view.applicants.list_view', compact('_applicants', '_course', '_courses'));
    }
    public function applicant_profile(Request $_request)
    {
        $_account = ApplicantAccount::find(base64_decode($_request->_student));
        $_applicants = $_account->course->student_applicants;
        return view('pages.general-view.applicants.profile_view', compact('_account', '_applicants'));
    }
    public function applicant_document_notification(Request $_request)
    {
        $_applicant = ApplicantAccount::find(base64_decode($_request->_applicant));
        $_email_model = new ApplicantEmail();
        //return $_applicant->email;
        Mail::to($_applicant->email)->send($_email_model->document_notificaiton($_applicant));
        return back()->with('success', 'Successfully Send the Notification');
    }
    public function applicant_document_review(Request $_request)
    {
        $_document = ApplicantDocuments::find(base64_decode($_request->_document));
        $_email_model = new ApplicantEmail();
        if ($_request->_verification_status) {
            $_document->is_approved = 1;
            $_document->staff_id = Auth::user()->staff->id;
            $_document->save();
            if (count($_document->account->applicant_documents) == count($_document->account->document_status)) {
                Mail::to($_document->account->email)->send($_email_model->document_verified($_document));
            }
            //return $_document->account->document_status->count();
            //Mail::to($_document->account->email)->send($_email_model->document_approved($_document->account, $_document));
            return back()->with('success', 'Successfully Transact.');
        } else {
            $_document->is_approved = 2;
            $_document->staff_id = Auth::user()->staff->id;
            $_document->feedback = $_request->_comment;
            $_document->save();
            Mail::to($_document->account->email)->send($_email_model->document_disapproved($_document));
            return back()->with('success', 'Successfully Transact.');
            //echo "Disapproved";
        }
        // Disapproved
    }
    public function examination_view(Request $_request)
    {
        if ($_request->_view) {
            $_examination = Examination::find(base64_decode($_request->_view));
            return view('pages.administrator.examination.category_view', compact('_examination'));
        } else {
            $_examination = Examination::where('is_removed', false)->get();
            return view('pages.administrator.examination.view', compact('_examination'));
        }
    }
    public function examination_store(Request $_request)
    {
        $_request->validate([
            'exam_name' => 'required',
            'exam_descrip' => 'required',
            'exam_department' => 'required'
        ]);
        $_data = array(
            'examination_name' => $_request->exam_name,
            'description' => $_request->exam_descrip,
            'department' => $_request->exam_department,
            'due_date' => now(),
            'exp_date' => now(),
        );
        Examination::create($_data);
        return back()->with('success', 'Successfully Add an Examination');
    }
    public function examination_import(Request $_request)
    {
        $_request->validate([
            'files' => 'required|mimes:xlsx',
        ]);
        $_file = $_request->file('files');
        Excel::import(new ImportExamination($_request->exam), $_file);
        return back()->with('success', 'Excel File Successfully Imported');
    }
    public function examination_import_v2(Request $request)
    {
        $request->validate([
            'files' => 'required|mimes:xlsx',
        ]);
        $_file_name = '/examination-files/' . date('dmyhis');
        $_file_extention =  $request->file('files')->getClientOriginalExtension();
        $_file_name = $_file_name . "." . $_file_extention;
        //return  $_request->file('files');
        if ($request->file('files')) {
            Storage::disk('local')->put($_file_name, fopen($request->file('files'), 'r+'));
            //Excel::import(new GradeImport($_request->_section), $_request->file('file_input'));
            Excel::import(new ImportExaminationV2($request->exam), storage_path('app' . $_file_name));

            return back()->with('success', 'Excel File Successfully Imported3');
        }
        /*   } */
        /*       $file = $request->file('files'); */
        /*       Excel::import(new ImportExaminationV2($request->exam), $file); */
        /*       return back()->with('success', 'Excel File Successfully Imported'); */
    }
    function examination_category_view(Request $request)
    {
        $category =  ExaminationCategory::find(base64_decode($request->_view));
        #return $category->question;
        return view('pages.administrator.examination.question_view', compact('category'));
    }
    public function student_account_details(Request $_request)
    {
        $_student = StudentDetails::find(90);
        $_email_model = new StudentEnrollmentMail($_student);
        //return $_applicant->email;
        //Student Email
        $_email = $_student->account->email;
        $_cc = $_student->account->personal_email;
        $_email = 'p.banting@bma.edu.ph';
        $_cc = 'p.banting@bma.edu.ph';
        Mail::to($_email)->bcc($_cc)->send($_email_model);
    }

    public function student_gadgets_view()
    {
        $_student = [];
        $_students = [];
        return view('pages.administrator.student.gadget_view', compact('_students', '_student'));
    }
    public function gadget_upload_file(Request $_request)
    {
        $_request->validate([
            'files' => 'required|mimes:xlsx',
        ]);
        $_file = $_request->file('files');
        Excel::import(new StudentGadgetImport, $_file);
        return back()->with('success', 'Excel File Successfully Imported');
    }
    public function gadget_finder(Request $_request)
    {
        // R22J5003FEX
        $_gadget = StudentGadget::where('gadget_serial', 'like', '%' . $_request->serial . '%')->with('student:id,first_name,last_name')->get();
        return compact('_gadget');
    }
    public function deactive_account(Request $_request)
    {
        $_staff = Staff::find(base64_decode($_request->staff));
        $_staff->is_removed = ($_request->input('status') == 'deactive') ? true : false;

        $_staff->save();
        return back()->with('success', 'Successfully De-active');
    }

    public function request_task_view(Request $_request)
    {
        try {
            $tasks = Task::where('is_removed', false)->get();
            $debug_tacker = DebugReport::where('is_removed', false)->get();
            return view('pages.administrator.task.view', compact('tasks', 'debug_tacker'));
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function request_task_store(Request $_request)
    {
        $_request->validate([
            'task' => 'required',
        ]);
        try {
            $_data = array(
                'staff_id' => Auth::user()->staff->id,
                'task' => $_request->task,
                'status' => 0, // 0 mean Pending Task
                'task_approved' => 0, // 0 mean on Pending Problem
            );
            Task::create($_data);
            return back()->with('success', 'Successfully Created Task');
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function request_task_approved(Request $_request)
    {
        try {
            $task = Task::find(base64_decode($_request->task));
            $task->update(['task_approved' => 1, 'status' => 2]);
            return back()->with('success', 'Successfully Accept Task');
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function employee_generate_qrcode(Request $_request)
    {
        $_employee = Staff::find(base64_decode($_request->employee));
        $_data = strtolower(str_replace(' ', '-', trim($_employee->first_name . ' ' . $_employee->last_name)));
        $pdf = PDF::loadView("widgets.report.employee.employee-qr-code", compact('_employee'));
        $file_name = strtoupper('Qr code generate: ' . $_data);
        return $pdf->setPaper([0, 0, 612, 396], 'portrait')->stream($file_name . '.pdf');
    }
    function generateQrcodeBySection(Request $_request)
    {
        $section = Section::find($_request->section);
        $filename = $section->section . '-' . $section->academic->semester;
        $pdf = PDF::loadView("widgets.report.student.student_qrcode_by_section", compact('section'));
        return $pdf->setPaper([0, 0, 612.00, 1008.00], 'portrait')->stream($filename . '.pdf');
    }
    function store_mobile_application(Request $request)
    {
        $request->validate([
            'app_name' => 'required',
            'app_description' => 'required',
            'app_file' => 'required',
        ]);
        try {
            //Save the App LOGO
            $file_path = $this->office_file_save($request->file('app_file'), 'bma-students', 'ict', 'mobile-application');
            $details = array(
                'app_name' => $request->app_name,
                'description' => $request->app_description,
                'app_logo_path' => $file_path
            );
            MobileApplicationDetails::create($details);
            return back()->with('success', 'Successfully Created Application');
        } catch (\Throwable $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    function store_mobile_app(Request $request)
    {
        $request->validate([
            'app_name' => 'required',
            'app_description' => 'required',
            'app_file' => 'required|file|max:102400',
        ]);
        try {
            //Save the App LOGO
            $file_path = $this->office_file_save($request->file('app_file'), 'bma-students', 'ict', 'mobile-application/' . $request->app);
            /*  $file = $request->file('app_file');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $fileName);
            $file_path = asset('uploads/' . $fileName); */
            $details = array(
                'app_id' => base64_decode($request->app),
                'version_name' => $request->app_name,
                'description' => $request->app_description,
                'app_path' => $file_path
            );
            MobileApplicationVersions::create($details);
            return back()->with('success', 'Successfully Created Application');
        } catch (\Throwable $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    function store_comprehensive(Request $request)
    {
        $request->validate([
            'upload-file' => 'required|file|mimes:zip',
        ]);
        try {
            if ($request->hasFile('upload-file')) {
                $department = $request->course == 2 ? 'DECK-' : 'ENGINE-';
                $file = $request->file('upload-file');
                $fileName = $file->getClientOriginalName();
                $name = explode(' ', $fileName);
                $fileName = $department . $name[0];
                /*   $fileName = str_replace(' ', '', $fileName); */
                $path = $file->storeAs('upload-file', $fileName);
                // Unzip the SCORM package
                $zip = new \ZipArchive;
                if ($zip->open(storage_path('app/' . $path)) === TRUE) {
                    $zip->extractTo(storage_path('app/scorm/' . pathinfo($fileName, PATHINFO_FILENAME)));
                    $zip->close();
                }
                $data = array(
                    'function' => $request->function,
                    'competence_name' => $request->name,
                    'competence_code' => $request->code,
                    'file_name' => 'scorm/' . pathinfo($fileName, PATHINFO_FILENAME),
                    'course_id' => $request->course
                );
                ComprehensiveExamination::create($data);
                return back()->with('success', 'Examination Added.');
            }
            //code...
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
    function store_comprehensive_scheduled(Request $request)
    {
        try {
            $data = [
                'student_id' => $request->student,
                'examinee_id' => $request->examinee,
                'scheduled' => $request->date,
                'scheduled_staff_id' => Auth::user()->staff->id,
            ];
            ComprehensiveExaminationScheduled::create($data);
            return back()->with('success', 'Successfully Scheduled Examination');
        } catch (\Throwable $th) {
            $this->debugTracker($th);
            return back()->with('error', $th->getMessage());
        }
    }
    function comprehensive_examination_report(Request $request)
    {
        try {
            $examiee = ComprehensiveExaminationScheduled::select('comprehensive_examination_scheduleds.*')
                ->join('student_details', 'student_details.id', 'comprehensive_examination_scheduleds.student_id')
                ->join('enrollment_assessments', 'enrollment_assessments.student_id', 'comprehensive_examination_scheduleds.student_id')
                ->where('comprehensive_examination_scheduleds.scheduled', $request->date)
                ->where('comprehensive_examination_scheduleds.is_removed', false)
                ->where('enrollment_assessments.course_id', $request->course)
                ->groupBy('comprehensive_examination_scheduleds.student_id')
                ->orderBy('student_details.last_name', 'asc')
                ->orderBy('student_details.first_name', 'asc')
                ->get();
            $course = CourseOffer::find($request->course);
            $comprehensive = ComprehensiveExamination::where('course_id', $request->course)->orderBy('id', 'asc')->get();
            $pdfReport = new OnboardTrainingReport();
            return  $pdfReport->comprehensive_examination_report($examiee, $course, $comprehensive, $request->date);
            return $examiee;
        } catch (\Throwable $th) {
            $this->debugTracker($th);
            return back()->with('error', $th->getMessage());
        }
    }
}
