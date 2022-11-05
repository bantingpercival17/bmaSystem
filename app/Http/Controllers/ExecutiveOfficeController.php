<?php

namespace App\Http\Controllers;

use App\Models\CourseOffer;
use App\Models\EmployeeAttendance;
use App\Models\EnrollmentAssessment;
use App\Models\Section;
use App\Models\Staff;
use App\Models\StudentAccount;
use App\Models\StudentAttendance;
use App\Models\StudentDetails;
use App\Models\StudentNonAcademicClearance;
use App\Models\StudentOnboardingAttendance;
use App\Models\User;
use App\Report\ExecutiveFilesReport;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExecutiveOfficeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('executive');
        $this->first_day =  new DateTime();
        $this->last_day = new DateTime();
        $this->first_day->modify('last Sunday');
        $this->last_day->modify('Next Saturday');
        $this->week_dates = array(
            $this->first_day->format('Y-m-d') . '%',  $this->last_day->format('Y-m-d') . '%'
        );
        set_time_limit(0);
        $now = now();
        $day = new DateTime($now);
        $week =  date('l', strtotime($now));
        $modify = $week == 'Sunday' ? 'Sunday' : 'Last Sunday';
        $this->week_start = $day->modify($modify);
        $this->week_start = $day->format('Y-m-d');
        $this->week_end = $day->modify('Next Saturday');
        $this->week_end = $day->format('Y-m-d');
        $this->week_dates = [$this->week_start . '%', $this->week_end . '%'];
    }
    public function index()
    {
        $_courses = CourseOffer::where('is_removed', false)->get();
        return view('pages.exo.dashboard.view', compact('_courses'));
    }
    public function json_attendance(Request $_request)
    {

        $_data = Staff::select('staff.id', 'staff.user_id', 'staff.first_name', 'staff.last_name', 'staff.department', 'ea.staff_id', 'ea.description', 'ea.time_in', 'ea.time_out', 'ea.created_at')
            ->leftJoin('employee_attendances as ea', 'ea.staff_id', 'staff.id')
            ->where('ea.created_at', 'like', '%' . now()->format('Y-m-d') . '%')
            ->orderBy('ea.updated_at', 'desc')->get();
        $_data = $_request->_user == 'employee' ?  EmployeeAttendance::where('created_at', 'like', '%' . now()->format('Y-m-d') . '%')->orderBy('updated_at', 'desc')->get() : $_data;
        $_data = $_request->_user == 'student' ? StudentOnboardingAttendance::whereBetween('created_at', $this->week_dates)
            ->with('student:id,last_name,first_name,middle_name', /* 'student.enrollment_assessment', */ 'student.current_section.section')
            ->orderBy('updated_at', 'desc')->get() : $_data;
        return compact('_data');
    }

    public function qrcode_scanner_view(Request $_request)
    {
        $_employees = $_request->_user == 'employee' ?  EmployeeAttendance::where('created_at', 'like', '%' . now()->format('Y-m-d') . '%')->orderBy('updated_at', 'desc')->get() : [];
        $_students = $_request->_user == 'student' ? StudentOnboardingAttendance::whereBetween('created_at', $this->week_dates)->orderBy('updated_at', 'desc')->get() : [];
        return view('pages.exo.gatekeeper.qrcode-scanner', compact('_employees', '_students'));
    }
    public function qrcode_scanner($_user, $_data)
    {
        $_date = date('Y-m-d'); // Get Date Now
        if ($_user == 'student') {
            $_data = $this->student_qrcode_data($_data, $_date);
        } else {
            $_data = $this->employee_qrcode_data_v2($_data);
        }
        return compact('_data');
    }
    public function employee_qrcode_data_v2($_data)
    {
        //The is a Email value for the Employees
        $_account = User::where('email', $_data)->first(); // Get the Staff Account using the Email Address
        if ($_account) {
            $_time_in_content_respond = array(
                'name' => strtoupper(trim($_account->name)),
                'department' => $_account->staff->department,
                'time_status' => 'TIME IN',
                'time' =>  date('H:i:s'),
                'image' =>  strtolower(str_replace(' ', '_', $_account->name)) . '.jpg',
                'link' => '/assets/audio/' . trim(strtolower(str_replace(' ', '-', trim(str_replace('/', '-', $_account->staff->first_name))))) . '-good-morning.mp3'
            ); // Set up the Return Value for Time In
            $_time_out_content_respond = array(
                'name' => strtoupper(trim($_account->name)),
                'department' => $_account->staff->department,
                'time_status' => 'TIME OUT',
                'time' =>  date('H:i:s'),
                'image' =>  strtolower(str_replace(' ', '_', $_account->name)) . '.jpg',
                'link' => '/assets/audio/' . trim(strtolower(str_replace(' ', '-', trim(str_replace('/', '-', $_account->staff->first_name))))) . '-good-bye.mp3'
            ); // Set up the Return Value for Time Out
            $_staff_content = array(
                'staff_id' => $_account->staff->id,
                'description' => 'n/a',
                'time_in' => date('Y-m-d H:i:s'),
            ); // Create an time in
            $_attendance = EmployeeAttendance::where('staff_id', $_account->staff->id)
                ->where('created_at', 'like', '%' . now()->format('Y-m-d') . '%')->orderBy('id', 'desc')->first(); // Get the Attendance Data
            //$_respond = $_attendance ? $_time_out_content_respond : $_time_in_content_respond; // Get the Respond Content
            if ($_attendance) {
                if ($_attendance->time_out) {
                    $_attendance = EmployeeAttendance::create($_staff_content); // Create a new Time Data
                    $_respond = $_time_in_content_respond;
                } else {
                    $_attendance->update(['time_out' => now()]); // Update the time Out
                    $_respond = $_time_out_content_respond;
                }
            } else {
                $_attendance = EmployeeAttendance::create($_staff_content); // Save Time in 
                $_respond = $_time_in_content_respond;
            }
            //$_attendance = $_attendance ? $_attendance->update(['time_out' => now()]) : EmployeeAttendance::create($_staff_content); // Save Time in and Update the time Out
            $_attendance = EmployeeAttendance::find($_attendance->id);
            $_respond['attendance_details'] = $_attendance;
            $_data = $_attendance ? array('respond' => '200', 'message' => 'Good bye and Keep Safe ' . $_account->staff->first_name . "!", 'data' => $_respond) :
                array('respond' => '200', 'message' => 'Welcome' . $_account->staff->first_name . "!", 'data' => $_respond);
        } else {
            $_data = array('respond' => '404', 'message' => 'Invalid Email');
        }
        return $_data;
    }
    public function employee_qrcode_data($_data, $_date)
    {
        $_data_content = json_decode(base64_decode($_data)); // Decode the Qr-Code Data
        $_date_now = date('Y-m-d'); // Get the Current Date
        $_email = $_data_content[0]; // Get the Email of the Staff in Qr-code Data
        $_time_in = date_format(date_create($_data_content[2]), 'Y-m-d'); // Get the Time in of the Staff and Format into Y-m-d
        $_account = User::where('email', $_email)->first(); // Get the Staff Account using the Email Address
        // return $_email;
        if ($_date_now == $_time_in || $_email == 'p.banting@bma.edu.ph') {
            if ($_account) {
                $_time_in_content_respond = array(
                    'name' => strtoupper(trim($_account->name)),
                    'department' => $_account->staff->department,
                    'time_status' => 'TIME IN',
                    'time' =>  date('H:i:s'),
                    'image' =>  strtolower(str_replace(' ', '_', $_account->name)) . '.jpg',
                    'link' => '/assets/audio/' . trim(strtolower(str_replace(' ', '-', trim(str_replace('/', '-', $_account->staff->first_name))))) . '-good-morning.mp3'
                ); // Set up the Return Value
                $_time_out_content_respond = array(
                    'name' => strtoupper(trim($_account->name)),
                    'department' => $_account->staff->department,
                    'time_status' => 'TIME OUT',
                    'time' =>  date('H:i:s'),
                    'image' =>  strtolower(str_replace(' ', '_', $_account->name)) . '.jpg',
                    'link' => '/assets/audio/' . trim(strtolower(str_replace(' ', '-', trim(str_replace('/', '-', $_account->staff->first_name))))) . '-good-bye.mp3'
                );
                $_description = json_decode($_data_content[1]);
                $_staff_content = array(
                    'staff_id' => $_account->staff->id,
                    'description' => json_encode(array(
                        'body_temprature' => $_description[0],
                        'have_any' => $_description[1],
                        'experience' => $_description[2],
                        'positive' => $_description[3],
                        'gatekeeper_in' => Auth::user()->name

                    )),
                    'time_in' => date('Y-m-d H:i:s'),
                );
                $_attendance = EmployeeAttendance::where('staff_id', $_account->staff->id)
                    ->where('created_at', 'like', '%' . now()->format('Y-m-d') . '%')->first(); // Get the Attendance Data
                $_respond = $_attendance ? $_time_out_content_respond : $_time_in_content_respond; // Get the Respond Content
                if ($_attendance) {
                    $_attendance->update(['time_out' => now()]); // Update the time Out
                } else {
                    $_attendance = EmployeeAttendance::create($_staff_content); // Save Time in 
                }
                //$_attendance = $_attendance ? $_attendance->update(['time_out' => now()]) : EmployeeAttendance::create($_staff_content); // Save Time in and Update the time Out
                $_attendance = EmployeeAttendance::find($_attendance->id);
                $_respond['attendance_details'] = $_attendance;
                $_data = $_attendance ? array('respond' => '200', 'message' => 'Good bye and Keep Safe ' . $_account->staff->first_name . "!", 'data' => $_respond) :
                    array('respond' => '200', 'message' => 'Welcome' . $_account->staff->first_name . "!", 'data' => $_respond);
            } else {
                $_data = array('respond' => '404', 'message' => 'Invalid Email');
            }
        } else {
            $_respond = array(
                'time_status' => 'invalid qr code',
                'link' => '/assets/audio/expired_qr_code.mp3'
            );
            $_data = array('respond' => '404', 'message' => 'Qr Code is Expired', 'data' => $_respond);
        }
        return $_data;
    }
    public function student_qrcode_data($_data, $_date)
    {
        $_data = explode('.', $_data);
        $_account = StudentAccount::where('student_number', $_data[0])->first();
        if ($_account) {
            $_image = $_account->student->profile_pic($_account);
            $_details = array(
                'student_name' => strtoupper($_account->student->last_name . ', ' . $_account->student->first_name),
                'student_course' => $_account->student->enrollment_assessment->course->course_name,
                'student_section' => $_account->student->current_section->section->section_name,
                //'student' => $_account->student->with('enrollment_assessment'),
                'image' => str_replace('bma.edu.ph', '20.0.0.120', $_image),
            );
            $_attendance_details = array(
                'student_id' => $_account->student->id,
                'course_id' => $_account->student->enrollment_assessment->course_id,
                'academic_id' => $_account->student->enrollment_assessment->academic_id,
                'time_in' => now(),
                'time_in_status', 'time_in_remarks',
                'time_in_process_by' => Auth::user()->name,
            );
            $attendance = StudentOnboardingAttendance::where([
                'student_id' => $_account->student->id,
                'course_id' => $_account->student->enrollment_assessment->course_id,
                'academic_id' => $_account->student->enrollment_assessment->academic_id,
            ])
                ->whereBetween('created_at', $this->week_dates)->first();
            if ($attendance) {
                if ($attendance->time_out !== null) {
                    $_attendance = StudentOnboardingAttendance::create($_attendance_details);
                    $_attendance = StudentOnboardingAttendance::find($_attendance->id);
                } else {
                    $attendance->time_out = now();
                    $attendance->time_out_process_by = Auth::user()->name;
                    $attendance->save();
                    $_attendance = StudentOnboardingAttendance::find($attendance->id);
                }
            } else {
                $_attendance = StudentOnboardingAttendance::create($_attendance_details);
                $_attendance = StudentOnboardingAttendance::find($_attendance->id);
            }

            $_details['student_attendance'] = $_attendance;
            $_data = array('respond' => '200', 'message' => 'Welcome ' . $_data[0] . " have a Great Day!", 'details' => $_details);
        } else {
            $_details = array(
                'link' => '/assets/audio/invalid-user.mp3'
            );
            $_data = array('respond' => '404', 'message' => 'Invalid User', 'details' => $_details);
        }

        return $_data;
    }
    public function student_qrcode_data_v2($_data, $_date)
    {
        $_email = $_data[0]; // Get Email 
        $_time_in =   date_format(date_create($_data[2]), "Y-m-d"); // Get Date and Convert
        $_account = StudentAccount::where('campus_email', $_email)->first();
        if ($_account) {
            $_attendance = StudentAttendance::where('student_id', $_account->student_id)
                ->where('created_at', 'like', '%' . now()->format('Y-m-d') . '%')->first();
            $_details = array(
                'name' => strtoupper(trim($_account->student->first_name . " " . $_account->student->last_name)),
                'course' => $_account->student->enrollment_assessment->course->course_name,
                'time_status' => 'Time In',
                'time' =>  date('H:i:s'),
                'image' =>  '/assets/img/staff/student/' . trim($_account->student_number) . ".png",
                'link' => '/assets/audio/cadet_timein.mp3'
            );
            if (!$_attendance) {
                // Store Attendance
                $_description = json_decode($_data[1]);
                $_attendance_details = array(
                    'student_id' => $_account->student_id,
                    'description' => json_encode(array(
                        'body_temprature' => $_description[0],
                        'have_any' => $_description[1],
                        'experience' => $_description[2],
                        'positive' => $_description[3],
                        'gatekeeper_in' => Auth::user()->name
                    )),
                    'time_in' => date('Y-m-d H:i:s'),
                );
                StudentAttendance::create($_attendance_details);
                $_data = array('respond' => '200', 'message' => 'Welcome ' . $_account->student->first_name . " have a Great Day!", 'details' => $_details);
            } else {
                $_attendance->time_out = now();
                $_attendance->save();
                $_details['time_status'] = 'TIME OUT!';
                $_details['link'] = '/assets/audio/cadet_timeout.mp3';
                $_data = array('respond' => '200', 'message' => 'Good Bye and Keep Safe ' . $_account->student->first_name . "!", 'details' => $_details);
                # code...
            }
            return compact('_data');
        } else {
            $_details = array(
                'link' => '/assets/audio/invalid-user.mp3'
            );
            $_data = array('respond' => '404', 'message' => 'Invalid User', 'details' => $_details);
        }
        return compact('_data');
    }
    public function semestral_clearance_view(Request $_request)
    {
        $_courses = CourseOffer::all();
        $_sections = $_request->_course ? Section::where('course_id', base64_decode($_request->_course))->where('is_removed', false)->where('academic_id', Auth::user()->staff->current_academic()->id)->orderBy('section_name', 'desc')->get() : [];
        return view('pages.exo.semestral-clearance.view', compact('_courses', '_sections'));
    }
    public function semestral_student_list_view(Request $_request)
    {
        $_section = Section::find(base64_decode($_request->_section));
        return view('pages.exo.semestral-clearance.student_section', compact('_section'));
    }
    public function semestral_clearance_store(Request $_request)
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

    public function onboarding_attendances(Request $_request)
    {
        $_courses  = CourseOffer::where('is_removed', false)->orderBy('id', 'desc')->get();
        $_students = StudentDetails::where('is_removed', false)->orderBy('last_name', 'asc')->get();

        return view('pages.exo.student.onboarding', compact('_courses', '_students'));
    }
    public function onboarding_student_list_report(Request $_request)
    {

        $_course = CourseOffer::find($_request->course);
        $_sections = Section::where('course_id', $_course->id)
            ->where('is_removed', false)
            ->where('academic_id', Auth::user()->staff->current_academic()->id);
        $_sections = $_request->level == 'all' ? $_sections : $_sections->where('year_level', 'like', '%' . $_request->level . '%');
        $_sections = $_sections->orderBy('year_level', 'desc')->get();
        $_report = new ExecutiveFilesReport();
        return $_report->student_onboarding_report($_sections);
    }
}
