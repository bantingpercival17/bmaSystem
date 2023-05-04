<?php

namespace App\Http\Controllers;


use App\Exports\CourseSectionStudentList;
use App\Exports\CurriculumSummaryGradeSheet;
use App\Exports\SubjectScheduleTemplate;
use App\Exports\SubjectScheduleWorkbook;
use App\Exports\SummaryGradeSheet;
use App\Imports\StudentSection as ImportsStudentSection;
use App\Imports\SubjectScheduleImport;
use App\Models\AcademicYear;
use App\Models\ApplicantAccount;
use App\Models\CourseOffer;
use App\Models\Curriculum;
use App\Models\CurriculumSubject;
use App\Models\EnrollmentApplication;
use App\Models\EnrollmentAssessment;
use App\Models\GradePublish;
use App\Models\Section;
use App\Models\StudentCancellation;
use App\Models\StudentDetails;
use App\Models\StudentNonAcademicClearance;
use App\Models\StudentSection;
use App\Models\Subject;
use App\Models\SubjectClass;
use App\Models\SubjectClassSchedule;
use App\Models\User;
use App\Report\Clearance\SemestralClearanceReport;
use App\Report\GradingSheetReport;
use App\Report\StudentListReport;
use App\Report\Students\StudentReport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class RegistrarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('registrar');
    }
    public function index()
    {
        try {
            $_academics = AcademicYear::where('is_removed', false)->get();
            $_courses = CourseOffer::where('is_removed', false)->orderBy('id', 'desc')->get();
            $_total_population = Auth::user()->staff->enrollment_count();
            $_total_applicants = ApplicantAccount::where('academic_id', Auth::user()->staff->current_academic()->id)->get();
            return view('pages.registrar.dashboard.view', compact('_academics', '_courses', '_total_population', '_total_applicants'));
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function dashboard_payment_assessment(Request $_request)
    {
        try {
            $_course = CourseOffer::find(base64_decode($_request->_course));
            return view('pages.registrar.dashboard.payment-assessment', compact('_course'));
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function dashboard_student_clearance_list(Request $_request)
    {
        try {
            $_course = CourseOffer::find(base64_decode($_request->_course));
            if ($_request->_clearance_status == 'not-cleared') {
                return view('pages.registrar.dashboard.student-not-clearance-list', compact('_course'));
            } else {
                return view('pages.registrar.dashboard.student-clearance-list', compact('_course'));
            }
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
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
            return view('pages.registrar.enrollment.view', compact('_courses', '_students', '_curriculums'));
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function enrollment_assessment(Request $_request)
    {
        try {
            $_student = StudentDetails::find(base64_decode($_request->_student));
            $_current_assessment = $_student->enrollment_assessment;
            $_application = $_student->enrollment_application ?: $_current_assessment;
            $_value = $_application->course_id == 3 ? 1 : -1;
            if (count($_student->enrollment_history) > 0) {
                // Set the Year Level of Old Student
                $_year_level = Auth::user()->staff->current_academic()->semester == 'First Semester' ? intval($_current_assessment->year_level) +  $_value : intval($_current_assessment->year_level);

                if ($_student->enrollment_application) {
                    $_year_level = $_student->enrollment_application->enrollment_category === 'SBT ENROLLMENT' ? $_year_level - 1 : $_year_level;
                }

                // Old Student
                // If the Student is Incoming 4th class and have a previous Enrollment Assessment
                //first check the Year level if the year level is Equal to 13 the Student will equvalet into 4th class
                if ($_year_level == 13) {
                    // This Will be incoming 4th Class
                    // Create a new Student number and Student Account
                }
                // Validate the Enrollment Assessment of the Student
                $_assessment = EnrollmentAssessment::where('student_id', $_student->id)->where('academic_id', Auth::user()->staff->current_academic()->id)->first();
                if (!$_assessment) {
                    // Store Enrollment Assessment
                    $_assessment_details = [
                        "student_id" => $_student->id,
                        "academic_id" => Auth::user()->staff->current_academic()->id,
                        "course_id" => $_request->_course ?: $_student->enrollment_assessment->course_id,
                        "curriculum_id" => $_request->_curriculum ?: $_student->enrollment_assessment->curriculum_id,
                        "year_level" => strval($_year_level),
                        "bridging_program" => $_request->_bridging_program ?: "without",
                        "staff_id" => Auth::user()->id,
                        "is_removed" => 0
                    ];
                    // Saved Enrollment Assessment
                    EnrollmentAssessment::create($_assessment_details);
                    // Update Enrollment Application
                    if ($_student->enrollment_application) { // If Online Enrollee Update Data
                        $_student->enrollment_application->staff_id = Auth::user()->staff->id;
                        $_student->enrollment_application->is_approved = 1;
                        $_student->enrollment_application->save();
                    } else { // If Onsite Enrollee Store Data
                        $_details = [
                            'student_id' => $_student->id,
                            'academic_id' => Auth::user()->staff->current_academic()->id,
                            'enrollment_place' => 'onsite',
                            'staff_id' => Auth::user()->staff->id,
                            'is_approved' => 1,
                            'is_removed' => false,
                        ];
                        EnrollmentApplication::create($_details);
                    }

                    return back()->with('success', 'Transaction Successfully.');
                } else {
                    $_assessment_details = [
                        "course_id" => $_request->_course ?: $_student->enrollment_assessment->course_id,
                        "curriculum_id" => $_request->_curriculum ?: $_student->enrollment_assessment->curriculum_id,
                        "bridging_program" => $_request->_bridging_program ?: "without",

                    ];
                    $_assessment->update($_assessment_details);
                    return back()->with('success', 'Successfuly Re-assess');
                    // return back()->with('error', 'This is already Saved');
                }
                //$_student->enrollment_assessment;
            } else {
                // New Student
                // Store Enrollment Assessment
                $_assessment_details = [
                    "student_id" => $_student->id,
                    "academic_id" => Auth::user()->staff->current_academic()->id,
                    "course_id" => $_request->_course ?: $_student->enrollment_assessment->course_id,
                    "curriculum_id" => $_request->_curriculum ?: $_student->enrollment_assessment->curriculum_id,
                    "year_level" => 4,
                    "bridging_program" => $_request->_bridging_program ?: "without",
                    "staff_id" => Auth::user()->id,
                    "is_removed" => 0
                ];
                // Saved Enrollment Assessment
                EnrollmentAssessment::create($_assessment_details);
                // Update Enrollment Application
                if ($_student->enrollment_application) { // If Online Enrollee Update Data
                    $_student->enrollment_application->staff_id = Auth::user()->staff->id;
                    $_student->enrollment_application->is_approved = 1;
                    $_student->enrollment_application->save();
                } else { // If Onsite Enrollee Store Data
                    $_details = [
                        'student_id' => $_student->id,
                        'academic_id' => Auth::user()->staff->current_academic()->id,
                        'enrollment_place' => 'onsite',
                        'staff_id' => Auth::user()->staff->id,
                        'is_approved' => 1,
                        'is_removed' => false,
                    ];
                    EnrollmentApplication::create($_details);
                }
                return back()->with('success', 'Transaction Successfully.');
            }
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function enrolled_list_view(Request $_request)
    {
        try {
            $_course = CourseOffer::find(base64_decode($_request->_course));
            $_students = $_request->_year_level ?  $_course->enrollment_list_by_year_level($_request->_year_level)->get() : $_course->enrollment_list;
            return view('pages.registrar.enrollment.enrolled_list_view', compact('_course', '_students'));
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function enrollment_cancellation(Request $_request)
    {
        $_request->validate([
            'type' => 'required',
            'date' => 'required',
            'file' => 'required'
        ]);
        $link = $this->office_file_save($_request->file, 'public', 'registrar', 'enrollment');
        StudentCancellation::create([
            'enrollment_id' => $_request->enrollment,
            'type_of_cancellations' => $_request->type,
            'date_of_cancellation' => $_request->date,
            'cancellation_evidence' => $link,
            'staff_id' => Auth::user()->staff->id,
        ]);
        // Remove to the Section


        return back()->with('success', 'Successfully Submitted.');
    }
    public function student_clearance(Request $_request)
    {
        try {
            $_student = StudentDetails::find(base64_decode($_request->_student));
            $_academic = $_student->enrollment_assessment->academic_id;
            $_section = $_student->section($_academic)->first();
            $_subject_class = $_section ? SubjectClass::where('section_id', $_section->section_id)->where('is_removed', false)->get() : [];
            return view('pages.registrar.enrollment.clearance', compact('_student', '_subject_class'));
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function subject_view()
    {
        try {
            $_curriculum = Curriculum::where('is_removed', false)->orderBy('curriculum_name', 'desc')->get();
            $_courses = CourseOffer::all();
            return view('pages.registrar.subjects.view', compact('_curriculum', '_courses'));
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    // Subject Panel
    public function classes_view(Request $_request)
    {
        try {
            $_curriculums = Curriculum::where('is_removed', false)->orderBy('curriculum_name', 'desc')->get();
            $_course = CourseOffer::find(base64_decode($_request->_course));
            $_teachers = User::select('users.id', 'users.name')
                ->join('role_user', 'users.id', 'role_user.user_id')
                ->where('role_user.role_id', 6)
                ->get();
            return view('pages.registrar.subjects.course_subject_view', compact('_curriculums', '_teachers', '_course'));
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function classes_store(Request $_request)
    {
        try {
            $_subject_class_detail = [
                'staff_id' => $_request->_teacher,
                'curriculum_subject_id' => $_request->_subject,
                'academic_id' => $_request->_academic,
                'section_id' => $_request->_section,
                'created_by' => Auth::user()->name,
                'is_removed' => 0,
            ];
            $_check = SubjectClass::where([
                'staff_id' => $_request->_teacher,
                'curriculum_subject_id' => $_request->_subject,
                'academic_id' => $_request->_academic,
                'section_id' => $_request->_section,
            ])->first();
            $_subject_class = $_check ?: SubjectClass::create($_subject_class_detail);
            $_schedule = array(
                'subject_class_id' => $_subject_class->id,
                'day' => $_request->_week,
                'start_time' => $_request->_start,
                'end_time' => $_request->_end,
                'created_by' => Auth::user()->name,
                'is_removed' => false,

            );
            SubjectClassSchedule::create($_schedule);
            return back()->with('message', 'Successfully Created Subject Classes!');
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function classes_removed(Request $_request)
    {
        try {
            $_subject_class = SubjectClass::find(base64_decode($_request->_c));
            $_subject_class->is_removed = 1;
            $_subject_class->save();
            return back()->with('message', 'Successfully Removed Subject Classes!');
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function classes_subject_handle(Request $_request)
    {
        try {
            $_subject = CurriculumSubject::find(base64_decode($_request->_subject));
            $_teachers = User::select('users.id', 'users.name')
                ->join('role_user', 'users.id', 'role_user.user_id')
                ->where('role_user.role_id', 6)
                ->get();
            return view('pages.registrar.subjects.course_subject_handle_view', compact('_subject', '_teachers'));
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function classes_schedule(Request $_request)
    {
        $_request->validate([
            '_subject_class' => 'required',
            '_start' => 'required',
            '_end' => 'required'
        ]);
        try {
            $_schedule = array(
                'subject_class_id' => $_request->_subject_class,
                'day' => $_request->_week,
                'start_time' => $_request->_start,
                'end_time' => $_request->_end,
                'created_by' => Auth::user()->name,
                'is_removed' => false,
            );
            SubjectClassSchedule::create($_schedule);
            return back()->with('success', 'Successfully Add Scheduled!!');
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function classes_schedule_removed(Request $_request)
    {
        try {
            $_schedule = SubjectClassSchedule::find(base64_decode($_request->_schedule));
            $_schedule->is_removed = 1;
            $_schedule->save();
            return back()->with('success', 'Successfully Removed Schedule');
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }

    public function classes_update(Request $request)
    {
        try {
            $_schedule = SubjectClass::find(base64_decode($request->subject_class));
            $_schedule->staff_id = $request->teacher;
            $_schedule->save();
            return redirect(route('registrar.course-subject-handle-view').'?_subject='.base64_encode($_schedule->curriculum_subject_id))->with('success', 'Successfully Removed Schedule');
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function class_schedule_template(Request $_request)
    {
        try {
            /* Finding the course offer and then getting the course subject. */
            $_course = CourseOffer::find(base64_decode($_request->course));
            //return  $_subject = $_course->course_subject(json_decode(base64_decode($_request->data)));

            $_file_export = new SubjectScheduleWorkbook($_course, $_request);
            $_respond =  Excel::download($_file_export, $_course->course_code . '-subject-schedule-' . base64_decode($_request->data) . '.xlsx', \Maatwebsite\Excel\Excel::XLSX); // Download the File
            ob_end_clean();
            return $_respond;
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function class_schedule_upload(Request $_request)
    {
        try {
            $_curriculum = Curriculum::find($_request->curriculum);
            $_course = CourseOffer::find($_request->course);
            $_file_name = $_course->course_code . '-' . $_request->level . '-' . $_curriculum->curriculum_name;
            $_file_extention =  $_request->file('upload-file')->getClientOriginalExtension();
            $_file_name = "/registrar/scheduled-import/" . strtoupper(str_replace(' ', '-', str_replace('/', '', $_file_name))) . date('dmyhis') . '.' . $_file_extention;

            if ($_request->file('upload-file')) {
                Storage::disk('public')->put($_file_name, fopen($_request->file('upload-file'), 'r+'));
                Excel::import(new SubjectScheduleImport(), $_request->file('upload-file'));
                return back()->with('success', 'Successfully Upload the Class Scheduled');
            }
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function curriculum_view(Request $_request)
    {
        try {
            $_course = $_request->d ? base64_decode($_request->d) : '';
            $_curriculum = Curriculum::find(base64_decode($_request->view)); // Get the Curriculum
            $_course_view = CourseOffer::where('is_removed', false)->get();
            $_course = $_request->d ? CourseOffer::find($_course) : $_course_view;
            //$_course_subject = $_request->d ? CurriculumSubject::where('course_id', $_course->id)->where('is_removed', false)->get() : '';
            return view('pages.registrar.subjects.curriculum_view', compact('_curriculum', '_course_view', '_course'));
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function curriculum_subject_store(Request $_request)
    {
        $_request->validate([
            'course_code' => 'required',
            '_subject_name' => 'required',
            '_hours' => 'required',
            '_lab_hour' => 'required',
            '_units' => 'required'
        ]);
        try {
            $_subject = [
                'subject_code' => strtoupper(trim($_request->course_code)),
                'subject_name' => strtoupper(trim($_request->_subject_name)),
                'lecture_hours' => trim($_request->_hours),
                'laboratory_hours' => trim($_request->_lab_hour),
                'units' => trim($_request->_units),
                'created_by' => Auth::user()->name,
                'is_removed' => 0,
            ]; // Set all the input need to the Subject Details
            $_content_verification = [
                'subject_code' => strtoupper(trim($_request->course_code)),
                'subject_name' => strtoupper(trim($_request->_subject_name)),
                'lecture_hours' => trim($_request->_hours),
                'laboratory_hours' => trim($_request->_lab_hour),
                'units' => trim($_request->_units),
                'is_removed' => 0,
            ];
            // Verify if the Subject is Existing
            //$_verify = Subject::where('subject_code', strtoupper(trim($_request->course_code)))->where('subject_name', strtoupper(trim($_request->_subject_name)))->first();
            /* Revise this Code for Double Checking of the Existing Subjects*/
            $_verify = Subject::where($_content_verification)->first();
            $_subject = $_verify ?: Subject::create($_subject); // Save Subject or Get Subject
            $_course_subject_details = [
                'curriculum_id' => base64_decode($_request->curriculum),
                'subject_id' => $_subject->id,
                'course_id' => $_request->course,
                'year_level' => $_request->_input_7,
                'semester' => $_request->_input_8,
                'created_by' => Auth::user()->name,
                'is_removed' => 0,
            ]; // Subject Course Details
            CurriculumSubject::create($_course_subject_details); // Create a Subject Course
            return $_request->course ?  redirect('/registrar/subjects/curriculum?view=' . $_request->curriculum . '&d=' . base64_encode($_request->course))->with('success', 'Successfully Created Subject') : back()->with('success', 'Successfully Created Subject');
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function curriculum_subject_view(Request $_request)
    {
        try {
            // Get the Curriculum
            $_curriculum_subject = CurriculumSubject::find(base64_decode($_request->curriculum));
            $_subject = $_curriculum_subject->subject;
            return compact('_curriculum_subject', '_subject');
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function curriculum_subject_update(Request $_request)
    {
        try {
            // Get the Curriculum
            $_curriculum_subject = CurriculumSubject::find($_request->curriculum_subject);
            // Set and Check the Subject Update
            $_content_verification = [
                'subject_code' => strtoupper(trim($_request->course_code)),
                'subject_name' => strtoupper(trim($_request->_subject_name)),
                'lecture_hours' => trim($_request->_hours),
                'laboratory_hours' => trim($_request->_lab_hour),
                'units' => trim($_request->_units),
                'is_removed' => 0,
            ];
            // Set the Content of Subject
            $_subject_content = [
                'subject_code' => strtoupper(trim($_request->course_code)),
                'subject_name' => strtoupper(trim($_request->_subject_name)),
                'lecture_hours' => trim($_request->_hours),
                'laboratory_hours' => trim($_request->_lab_hour),
                'units' => trim($_request->_units),
                'created_by' => Auth::user()->name,
                'is_removed' => 0,
            ];
            // Verify if the Update Subject is Existing
            $_verify = Subject::where($_content_verification)->first();
            $_subject = $_verify ?: Subject::create($_subject_content); // Save Subject or Get Subject
            // Update the Subject Id in the Curriculum Subject Table
            $_curriculum_subject->subject_id = $_subject->id;
            $_curriculum_subject->save();
            if ($_curriculum_subject->save()) {
                return back()->with('success', 'Successfuly Update the Subject Curriculum');
            } else {
                return back()->with('success', 'Successfuly Update the Subject Curriculum');
            }
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function curriculum_subject_remove(Request $_request)
    {
        try {
            $_subject = CurriculumSubject::find(base64_decode($_request->_subject));
            //return $_subject;
            $_subject->is_removed = 1;
            $_subject->save();
            return back()->with('success', 'Successfuly Removed');
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }

    // Student
    public function student_list_view(Request $_request)
    {
        try {
            $_academics = AcademicYear::where('is_removed', false)->get();
            $_course = CourseOffer::where('is_removed', false)->get();
            $_student_detials = new StudentDetails();
            $_students = $_request->_student ? $_student_detials->student_search($_request->_student) :  StudentDetails::where('is_removed', false)->orderBy('last_name', 'asc')->paginate(10);
            return view('pages.registrar.student.view', compact('_academics', '_course', '_students'));
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function student_profile_view(Request $_request)
    {
        try {
            $_student = StudentDetails::find(base64_decode($_request->student));
            /* if ($_request->student) {
                $picture =  $_student->profile_picture();
                $_student = compact('_student', 'picture');
            } */
            $_student_detials = new StudentDetails();
            if ($_request->search_student) {
                $_students = $_request->search_student ? $_student_detials->student_search($_request->search_student) : [];
            } else {
                $_students = StudentDetails::select('id', 'first_name', 'last_name', 'middle_name')->where('is_removed', false)->orderBy('last_name', 'asc')->paginate(10);
            }
            //return compact('_student', '_students');
            return view('pages.administrator.student.profile', compact('_student', '_students'));
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function student_information_report(Request $_request)
    {
        try {
            $_student_report = new StudentReport();;
            return $_student_report->enrollment_information(base64_decode($_request->_assessment));
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }

    public function student_application_report(Request $_request)
    {
        try {
            $_student_report = new StudentReport();;
            return $_student_report->application_form(base64_decode($_request->_student));
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }

    // Section Panel
    public function section_view(Request $_request)
    {
        try {
            $_courses = CourseOffer::where('is_removed', false)->get();
            $_curriculum = Curriculum::select('id', 'curriculum_name')->orderBy('curriculum_name', 'asc')->get();
            return view('pages.registrar.sections.view', compact('_courses', '_curriculum'));
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function section_store(Request $_request)
    {
        $_request->validate([
            '_section' => 'required',
            '_level' => 'required',
            '_curriculum' => 'required'
        ]);
        try {
            $_section_details = [
                'section_name' => $_request->_level . ' ' . $_request->_section,
                'academic_id' => $_request->_academic,
                'course_id' => $_request->_course,
                'year_level' => $_request->_level,
                'curriculum_id' => $_request->_curriculum,
                'created_by' => Auth::user()->name,
                'is_removed' => 0,
            ];
            Section::create($_section_details);
            return back()->with('success', 'Successfully Created Section');
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function section_add_student_view(Request $_request)
    {
        try {
            $_section = Section::find(base64_decode($_request->_section));
            $_student_list = $_section->student_sections;
            return view('pages.registrar.sections.section_view', compact('_section', '_student_list'));
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function section_add_student(Request $_request)
    {
        try {
            $_section = Section::find(base64_decode($_request->_section));
            $_student_list = $_section->student_section;
            $_year_level = str_replace('GRADE', '', $_section->year_level);
            $_year_level = str_replace('/C', '', $_year_level);
            $_students = StudentDetails::select('student_details.id', 'student_details.first_name', 'student_details.last_name')
                ->join('enrollment_assessments as ea', 'ea.student_id', 'student_details.id')
                ->where('ea.year_level', trim($_year_level))
                ->where('ea.course_id', $_section->course_id)
                ->where('ea.academic_id', Auth::user()->staff->current_academic()->id)
                ->where('ea.is_removed', false)
                ->orderBy('student_details.last_name')
                ->orderBy('student_details.first_name')
                //->toSql();
                ->get();
            //return compact('_students');
            return view('pages.registrar.sections.section_add_view', compact('_section', '_students'));
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function section_store_student(Request $_request)
    {
        try {
            StudentSection::create([
                'student_id' => base64_decode($_request->_student),
                'section_id' => base64_decode($_request->_section),
                'created_by' => Auth::user()->name,
                'is_removed' => 0,
            ]);
            $_section = Section::find(base64_decode($_request->_section));
            return back()->with('success', 'Successfully Added to ' . $_section->section_name);
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function section_remove_student(Request $_request)
    {
        try {
            $_student_section = StudentSection::find(base64_decode($_request->_student_section));
            $_student_section->is_removed = 1;
            $_student_section->save();
            return back()->with('success', 'Successfuly Removed the Student in Section');
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function section_export_file(Request $_request)
    {
        try {
            $_course = CourseOffer::find(base64_decode($_request->_course));
            $_year = Auth::user()->staff->convert_year_level($_request->_year_level);
            $_file_name = $_course->course_code . "_" . strtoupper($_year) . "_" . Auth::user()->staff->current_academic()->school_year . '_' . strtoupper(str_replace(' ', '_', Auth::user()->staff->current_academic()->semester));
            $_file_export = new CourseSectionStudentList($_course, $_request->_year_level);
            // Excell Report

            if ($_request->_report_type == 'excel-file') {
                $_respond =  Excel::download($_file_export, $_file_name . '.xlsx', \Maatwebsite\Excel\Excel::XLSX); // Download the File
                ob_end_clean();
                return $_respond;
            }
            if ($_request->_report_type == 'pdf-report') {
                $report = new StudentListReport();
                return $report->student_section_list([
                    'course_id' => $_course->id,
                    'year_level' => $_request->_year_level,
                    'academic_id' => Auth::user()->staff->current_academic()->id,
                    'is_removed' => false
                ]);
                //return Excel::download($_file_export, $_file_name . '.pdf'); // Download the File
            }
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function section_import_files(Request $_request)
    {
        try {
            $_section = Section::find(base64_decode($_request->section));
            StudentSection::where('section_id', $_section->id)->where('is_removed', false)->update(['is_removed' => true]);
            if ($_request->file('upload-file')) {
                $_file_extention =  $_request->file('upload-file')->getClientOriginalExtension();
                $_file_name = "/registrar/section-import/" . strtoupper(str_replace(' ', '-', str_replace('/', '', $_section->section_name))) . date('dmyhis') . '.' . $_file_extention;
                Storage::disk('public')->put($_file_name, fopen($_request->file('upload-file'), 'r+'));
                Excel::import(new ImportsStudentSection($_section), $_request->file('upload-file'));
                return back()->with('success', 'Successfully Uploaded');
            }
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    // Semestral clearance
    public function clearance_view(Request $_request)
    {
        $_courses = CourseOffer::all();
        $_sections = $_request->_course ? Section::where('course_id', base64_decode($_request->_course))->where('is_removed', false)->where('academic_id', Auth::user()->staff->current_academic()->id)->orderBy('section_name', 'desc')->get() : [];

        return view('pages.registrar.clearance.view', compact('_courses', '_sections'));
    }
    public function semestral_student_list_view(Request $_request)
    {
        $_section = Section::find(base64_decode($_request->_section));
        return view('pages.registrar.clearance.student_section', compact('_section'));
    }
    public function semestral_clearance_report(Request $_request)
    {
        $_section = Section::find(base64_decode($_request->_section));
        $_report = new SemestralClearanceReport();
        return $_report->semestral_clearance_overview($_section);
        //return view('pages.registrar.clearance.student_section', compact('_section'));
    }
    public function clearance_store(Request $_request)
    {
        try {
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
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }

    public function semestral_grade_view(Request $_request)
    {
        try {
            $_courses = CourseOffer::all();
            $_academic = $_request->_academic ? Auth::user()->staff->current_academic()->id : base64_decode($_request->_academic);
            $_sections = Section::where('academic_id', $_academic)
                ->where('course_id', base64_decode($_request->_course))->get();
            return view('pages.registrar.grade.view', compact('_courses', '_sections'));
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function semestral_grade_section_view(Request $_request)
    {
        $_section = Section::find(base64_decode($_request->_section));
        return view('pages.registrar.grade.student_section', compact('_section'));
    }
    public function semestral_grade_report_form(Request $_request)
    {
        $_student = StudentDetails::find(base64_decode($_request->student));
        $_section = Section::find(base64_decode($_request->_section));
        $_report = new StudentReport();
        return $_report->certificate_of_grade($_student, $_section);
    }

    public function semestral_grade_summary_report(Request $_request)
    {
        try {
            $course = CourseOffer::find(base64_decode($_request->_course));
            $_report = new StudentListReport();
            return $_report->summary_grade($course, $_request->_year_level);
        } catch (Exception $err) {
            $this->debugTracker($err);
            return $err->getMessage();
            //return back()->with('error', $err->getMessage());
        }
    }
    public function summary_grade_report_excel(Request $_request)
    {
        try {
            $_course = CourseOffer::find(base64_decode($_request->_course));
            $_level =  $_request->_year_level;
            $_file = new CurriculumSummaryGradeSheet($_course, $_level, $_request);
            $_year_level = $_level == '4' ? 'First Year' : '';
            $_year_level = $_level == '3' ? 'Second Year' : $_year_level;
            $_year_level = $_level == '2' ? 'Third Year' : $_year_level;
            $_year_level = $_level == '1' ? 'Fourth Year' : $_year_level;
            $_file_name = strtoupper($_course->course_name . '-' . $_year_level . '_' . Auth::user()->staff->current_academic()->school_year . "-" . Auth::user()->staff->current_academic()->semester) . '.xlsx';
            $_file = Excel::download($_file, $_file_name); // Download the File
            ob_end_clean();
            return $_file;
        } catch (Exception $err) {
            $this->debugTracker($err);
            return $err->getMessage();
            // return back()->with('error', $err->getMessage());
        }
    }
    public function semestral_grade_publish(Request $_request)
    {
        try {
            GradePublish::create([
                'student_id' => base64_decode($_request->_student),
                'academic_id' => base64_decode($_request->_academic),
                'staff_id' => Auth::user()->staff->id,
                'is_removed' => 0,
            ]);
            return back()->with('success', 'Grade Publish.');
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function semestral_grade_publish_all(Request $_request)
    {
        try {
            $_section = Section::find(base64_decode($_request->section));
            $_student_section = StudentSection::where('section_id', base64_decode($_request->section))->where('is_removed', false)->get();
            foreach ($_student_section as $key => $value) {
                GradePublish::create([
                    'student_id' => $value->student_id,
                    'academic_id' => base64_decode($_request->academic),
                    'staff_id' => Auth::user()->staff->id,
                    'is_removed' => 0,
                ]);
            }
            return back()->with('success', $_section->section_name . ' Successfully Published Grade.');
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
    public function semestral_subject_grade(Request $_request)
    {
        try {
            $_subject = SubjectClass::find(base64_decode($_request->_subject));
            $_subject_code =  $_subject->curriculum_subject->subject->subject_code;
            if ($_subject_code == 'BRDGE') {
                $_students = $_subject->section->student_with_bdg_sections;
            } else {
                $_students = $_subject->section->student_sections;
            }
            $_report = new GradingSheetReport($_students, $_subject);
            return $_request->_form == "ad1" ? $_report->form_ad_01() : $_report->form_ad_02();
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }


    public function enrollment_briding_program(Request $_request)
    {
        try {
            $_course = CourseOffer::find(base64_decode($_request->_course));
            $_students = $_course->student_bridging_program;
            return view('pages.registrar.enrollment.bridging-program', compact('_students', '_course'));
        } catch (Exception $err) {
            $this->debugTracker($err);
            return back()->with('error', $err->getMessage());
        }
    }
}
