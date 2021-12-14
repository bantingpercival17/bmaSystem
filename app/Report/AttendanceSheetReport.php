<?php

namespace App\Report;

use App\Models\Staff;
use Barryvdh\DomPDF\Facade as PDF;

class AttendanceSheetReport
{
    public function __construct()
    {

        $this->legal = [0, 0, 612.00, 1008.00];
    }
    public function daily_report()
    {
        //$_department = Staff::orderBy('department', 'ASC')->distinct()->get('department');
        /*   $_employees = Staff::rightJoin('employee_attendances as ea', 'ea.staff_id', 'staff.id')
            ->orderBy('staff.department', 'asc')
            ->orderBy('staff.last_name', 'asc')
            ->where('ea.time_in', 'like', '%' . date('Y-m-d') . '%')
            ->get(); */
        $_employees = Staff::orderBy('staff.department', 'asc')
        ->orderBy('staff.last_name', 'asc')->get();
        $pdf = PDF::loadView("widgets.report.employee.daily_report_attendance", compact('_employees'));
        $file_name = "Daily Attendance: "; // With Date now
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
    public function weekly_report()
    {
        return    $_department = Staff::select('department')->distict()->get('department');
        $pdf = PDF::loadView("widgets.report.employee.weekly_report_attendance"/* , compact('_sections', '_academic') */);
        $file_name = "Daily Attendance: "; // With Date now
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
}
