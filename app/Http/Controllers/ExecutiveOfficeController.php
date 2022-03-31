<?php

namespace App\Http\Controllers;

use App\Models\CourseOffer;
use App\Models\EmployeeAttendance;
use App\Models\Section;
use App\Models\Staff;
use App\Models\StudentAccount;
use App\Models\StudentDetails;
use App\Models\StudentNonAcademicClearance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExecutiveOfficeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('executive');
        set_time_limit(0);
    }
    public function index()
    {
        $_employees = Staff::select('staff.id', 'staff.user_id', 'staff.first_name', 'staff.last_name', 'staff.department', 'ea.staff_id', 'ea.description', 'ea.created_at')
            ->leftJoin('employee_attendances as ea', 'ea.staff_id', 'staff.id')
            ->groupBy('staff.id')
            ->orderBy('staff.last_name', 'asc')
            //->orderBy('ea.updated_at', 'desc')
            ->get();
        return view('pages.exo.gatekeeper.view', compact('_employees'));
        $_employees = Staff::select('staff.id', 'staff.user_id', 'staff.first_name', 'staff.last_name', 'staff.department', 'ea.staff_id', 'ea.description', 'ea.created_at')
            ->leftJoin('employee_attendances as ea', 'ea.staff_id', 'staff.id')
            //->where('ea.created_at', 'like', '%' . now()->format('Y-m-d') . '%')
            ->groupBy('staff.id')
            ->orderBy('ea.updated_at', 'desc')
            ->get();
        //return view('administrator.employee.attendance', compact('_employees'));
    }
    public function json_attendance()
    {
        $_data = Staff::select('staff.id', 'staff.user_id', 'staff.first_name', 'staff.last_name', 'staff.department', 'ea.staff_id', 'ea.description', 'ea.time_in', 'ea.time_out', 'ea.created_at')
            ->leftJoin('employee_attendances as ea', 'ea.staff_id', 'staff.id')
            ->where('ea.created_at', 'like', '%' . now()->format('Y-m-d') . '%')
            ->groupBy('staff.id')
            ->orderBy('ea.updated_at', 'desc')->get();
        return compact('_data');
    }

    public function qrcode_scanner_view()
    {
        return view('pages.exo.gatekeeper.qrcode-scanner');
    }
    public function qrcode_scanner($_user, $_data)
    {
        $_data = base64_decode($_data); // Decrypt the Data
        $_data = json_decode($_data); // Decode the Data
        $_date = date('Y-m-d'); // Get Date Now

        if ($_user == 'student') {
            $_data = $this->student_qrcode_data($_data, $_date);
        }

        return compact('_data');
    }
    public function employee_qrcode_data($_data, $_date)
    {
        $_email = $_data[0]; // Email 
        $_time_in = date_create($_data[2]);
        $_time_in =   date_format($_time_in, "Y-m-d");
        $_staff = Staff::select('staff.id', 'staff.user_id')->join('users', 'users.id', 'staff.user_id')->where('users.email', $_data[0])->first(); // Get Staff Id
        if ($_date == $_time_in) {
            if ($_staff) {
                $_attendance = EmployeeAttendance::where('staff_id', $_staff->id)
                    ->where('created_at', 'like', '%' . now()->format('Y-m-d') . '%')->first();
                $_staff_details = array(
                    'name' => strtoupper(trim($_staff->user->name)),
                    'department' => $_staff->user->staff->department,
                    'time_status' => 'TIME IN',
                    'time' =>  date('H:i:s'),
                    'image' =>  strtolower(str_replace(' ', '_', $_staff->user->name)) . '.jpg',
                    'link' => '/assets/audio/' . trim(strtolower(str_replace(' ', '-', trim(str_replace('/', '-', $_staff->user->staff->first_name))))) . '-good-morning.mp3'
                );
                if ($_attendance) {
                    $_attendance->time_out = now();
                    $_attendance->save();
                    $_staff_details['time_status'] = 'TIME OUT';
                    $_staff_details['link'] = '/assets/audio/' . trim(strtolower(str_replace(' ', '-', trim(str_replace('/', '-', $_staff->user->staff->first_name))))) . '-good-bye.mp3';
                    //$_staff_details = json_encode($_staff_details);
                    $_data = array('respond' => '200', 'message' => 'Good bye and Keep Safe ' . $_staff->user->staff->first_name . "!", 'data' => $_staff_details);
                } else {
                    $_description = json_decode($_data[1]);
                    $_staff_ = array(
                        'staff_id' => $_staff->id,
                        'description' => json_encode(array(
                            'body_temprature' => $_description[0],
                            'have_any' => $_description[1],
                            'experience' => $_description[2],
                            'positive' => $_description[3],
                            'gatekeeper_in' => Auth::user()->name

                        )),
                        'time_in' => date('Y-m-d H:i:s'),
                    );
                    EmployeeAttendance::create($_staff_);
                    $_staff_details['time_status'] = 'TIME IN';
                    $_data = array('respond' => '200', 'message' => 'Welcome' . $_staff->user->staff->first_name . "!", 'data' => $_staff_details);
                }
            } else {
                $_data = array('respond' => '404', 'message' => 'Invalid Email');
            }
        } else {
            $_staff_details = array(
                'name' => strtoupper(trim($_staff->user->name)),
                'department' => $_staff->user->staff->department,
                'time_status' => 'invalid qr code',
                'time' =>  date('H:i:s'),
                'image' =>  '',
                'link' => '/assets/audio/expired_qr_code.mp3'
            );
            $_data = array('respond' => '404', 'message' => 'Qr Code is Expired', 'data' => $_staff_details);
        }

        return compact('_data');
    }
    public function student_qrcode_data($_data, $_date)
    {
        $_email = $_data[0]; // Get Email 
        $_time_in =   date_format(date_create($_data[2]), "Y-m-d"); // Get Date and Convert
        $_account = StudentAccount::where('campus_email', $_email)->first();
        if ($_account) {
            if ($_date == $_time_in) {
                $_details = array(
                    'name' => strtoupper(trim($_account->student->first_name . " " . $_account->student->last_name)),
                    'course' => $_account->student->enrollment_assessment->course->course_name,
                    'time_status' => 'Time In',
                    'time' =>  date('H:i:s'),
                    'image' =>  '/assets/img/student-profile/' . $_account->student_number,
                    'link' => '/assets/audio/cadet_timein.mp3'
                );
                $_data = array('respond' => '200', 'message' => 'Welcome ' . $_account->student->first_name . "!", 'details' => $_details);
            } else {
                $_details = array(
                    'name' => strtoupper(trim($_account->student->name)),
                    'department' => $_account->student->enrollment_assessment->course->course_name,
                    'time_status' => 'invalid qr code',
                    'time' =>  date('H:i:s'),
                    'image' =>  '',
                    'link' => '/assets/audio/expired_qr_code.mp3'
                );
                $_data = array('respond' => '404', 'message' => 'Qr Code is Expired', 'details' => $_details);
            }
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
}
