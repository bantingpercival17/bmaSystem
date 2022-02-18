<?php

namespace App\Http\Controllers;

use App\Models\CourseOffer;
use App\Models\EnrollmentAssessment;
use App\Models\Staff;
use App\Report\AttendanceSheetReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $_courses = CourseOffer::where('is_removed', false)->orderBy('id', 'desc')->get();
        $_total_population = EnrollmentAssessment::join('payment_assessments as pa', 'pa.enrollment_id', 'enrollment_assessments.id')
            /* ->join('payment_transactions as pt', 'pt.assessment_id', 'pa.id')
            ->where('pt.remarks', 'Upon Enrollment') */
            ->where('enrollment_assessments.is_removed', false)
            ->where('academic_id', Auth::user()->staff->current_academic()->id)
            ->with('payment_transactions')
            ->get();
        return view('administrative.dashboard', compact('_employees', '_courses','_total_population'));
    }
    public function attendance_view()
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
        return $_request->r_view == 'daily' ? $_report->daily_report() : $_report->daily_time_record_report($_request->start_date, $_request->end_date);
    }


    /* Employee */
    public function employees_view()
    {
        $_employees = Staff::orderBy('last_name', 'asc')->get();
        return view('administrative.employee.view', compact('_employees'));
    }
    public function employees_profile_view(Request $_request)
    {
        $_staff = Staff::find(base64_decode($_request->_e));
        return view('administrative.employee.profile', compact('_staff'));
    }
}
