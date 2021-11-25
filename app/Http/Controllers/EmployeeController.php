<?php

namespace App\Http\Controllers;

use App\Models\EmployeeAttendance;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class EmployeeController extends Controller
{

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
}
