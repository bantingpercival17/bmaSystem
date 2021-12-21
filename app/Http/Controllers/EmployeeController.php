<?php

namespace App\Http\Controllers;

use App\Models\EmployeeAttendance;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function qr_scanner()
    {
        $_employees = Staff::select('staff.id', 'staff.user_id', 'staff.first_name', 'staff.last_name', 'staff.department', 'ea.staff_id', 'ea.description', 'ea.created_at')
            ->leftJoin('employee_attendances as ea', 'ea.staff_id', 'staff.id')
            ->where('ea.created_at', 'like', '%' . now()->format('Y-m-d') . '%')
            ->groupBy('staff.id')
            ->orderBy('ea.updated_at', 'desc')->get();
        return view('employee.scanner', compact('_employees'));
    }
    public function scanner($_data)
    {
        $_data = Staff::find(Crypt::decrypt($_data));
        $_attendance = EmployeeAttendance::where('staff_id', $_data->id)->where('created_at', 'like', '%' . now()->format('Y-m-d') . '%')->first();
        if ($_attendance) {
            $_staff_details = array(
                'staff_id' => $_data->id,
                'time_out' => now(),
                'description' => ''
            );
            $_attendance->staff_id = $_data->id;
            $_attendance->time_out = date('Y-m-d H:i:s');
            $_attendance->save();
            $_status = array(
                'respond' => 'time-out'
            );
        } else {
            $_status = array(
                'respond' => 'time-in'
            );
        }
        return compact('_data', '_status');
    }
    public function store(Request $_request)
    {
        $_request->validate([
            'employee' => 'required ',
            'body_temp' => 'required',
            'question1' => 'required',
            'question2' => 'required',
            'question3' => 'required'
        ]);
        $_staff_details = array(
            'staff_id' => $_request->employee,
            'description' => json_encode(array(
                'body_temprature' => $_request->body_temp,
                'have_any' => $_request->question1,
                'experience' => $_request->question2,
                'positive' => $_request->question3,
                'gatekeeper_in' => Auth::user()->name

            )),
            'time_in' => date('Y-m-d H:i:s'),
        );
        EmployeeAttendance::create($_staff_details);
        return back()->with('message', "Attendance Saved!");
    }

    public function view(Request $_request)
    {
        $_employees = Staff::select('staff.id', 'staff.user_id', 'staff.first_name', 'staff.last_name', 'staff.department', 'ea.staff_id', 'ea.description', 'ea.created_at')
            ->leftJoin('employee_attendances as ea', 'ea.staff_id', 'staff.id')
            ->groupBy('staff.id')
            ->orderBy('ea.updated_at', 'desc')->get();
        //$_employees = Staff::all();
        return view('administrator.employee.attendance', compact('_employees'));
    }
    public function attendance_form_view()
    {
        return view('employee.form_view');
    }
    public function attendance_generate_qr(Request $_request)
    {
        $_request->validate([
            'email' => 'required ',
            'body_temp' => 'required',
            'question1' => 'required',
            'question2' => 'required',
            'question3' => 'required'
        ]);
        $_staff_details = array(
            'email' => $_request->email,
            'description' => json_encode(array(
                'body_temprature' => $_request->body_temp,
                'have_any' => $_request->question1,
                'experience' => $_request->question2,
                'positive' => $_request->question3,

            )),
            'time_in' => date('Y-m-d H:i:s'),
        );
        return view('employee.generate_qr_code', compact('_staff_details'));
    }
    public function scanner_v2($_data)
    {
        $_data = base64_decode($_data); // Data
        $_data = json_decode($_data);
        $_date = date('Y-m-d'); // Date Now
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
                    'link' => '/assets/audio/' . trim(strtolower($_staff->user->staff->first_name)) . '-good-morning.mp3'
                );
                if ($_attendance) {
                    $_attendance->time_out = now();
                    $_attendance->save();
                    $_staff_details['time_status'] = 'TIME OUT';
                    $_staff_details['link'] = '/assets/audio/' . trim(strtolower($_staff->user->staff->first_name)) . '-good-bye.mp3';
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
            $_data = array('respond' => '404', 'message' => 'Invalid Date Encode');
        }

        return compact('_data');
    }

    public function attendance_view()
    {
        $_staff = Auth::user()->staff;
        $_attendance = EmployeeAttendance::where('staff_id', $_staff->id)->get();
        //return $_attendance;
        return view('employee.attendance_view', compact('_attendance'));
    }
    public function attendance_store(Request $_request)
    {
        $_request->validate([
            'employee' => 'required ',
            'body_temp' => 'required',
            'question1' => 'required',
            'question2' => 'required',
            'question3' => 'required'
        ]);
        $_staff_details = array(
            $_request->employee,
            json_encode(array(
                $_request->body_temp,
                $_request->question1,
                $_request->question2,
                $_request->question3,

            )),
            date('Y-m-d H:i:s'),
        );
        //return base64_encode($_staff_details);
        $_data = json_encode($_staff_details);
        $_data = base64_encode($_data);
        //return $_staff_details;
        return back()/* redirect() */->with('qr-code', $_data);
        //return view('employee.generate_qr_code', compact('_staff_details'));
        /* $pdf =  PDF::loadView("employee.qr_generate", compact('_data'));
        $file_name = strtoupper('Qr code generate:');
        return $pdf->setPaper([0, 0, 285.00, 250.00], 'landscape')->stream($file_name . '.pdf'); */
    }
}
