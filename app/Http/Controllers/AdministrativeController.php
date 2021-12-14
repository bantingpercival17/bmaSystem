<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Report\AttendanceSheetReport;
use Illuminate\Http\Request;

class AdministrativeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('administrative');
    }
    public function index()
    {
        $_employees = Staff::select('staff.id', 'staff.user_id', 'staff.first_name', 'staff.last_name', 'staff.department', 'ea.staff_id', 'ea.description', 'ea.created_at')
            ->leftJoin('employee_attendances as ea', 'ea.staff_id', 'staff.id')
            ->groupBy('staff.id')
            ->orderBy('staff.last_name', 'asc')
            //->orderBy('ea.updated_at', 'desc')
            ->get();
        return view('administrative.attendance.view', compact('_employees'));
    }
    public function attendance_report(Request $_request)
    {
        $_report = new AttendanceSheetReport();
        return $_request->r_view == 'daily' ? $_report->daily_report() : $_report->daily_time_record_report($_request->start_date,$_request->end_date);
    }
}
