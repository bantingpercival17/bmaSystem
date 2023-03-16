<?php

namespace App\Report;

use App\Models\Staff;
use Barryvdh\DomPDF\Facade as PDF;

class AttendanceSheetReport
{
    public function __construct()
    {
        $this->legal = [0, 0, 612.00, 1008.00];
        $this->crosswise_short = [0, 0, 612, 738];
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
            ->where('is_removed', false)
            ->orderBy('staff.last_name', 'asc')->get();
        $pdf = PDF::loadView("widgets.report.employee.daily_report_attendance", compact('_employees'));
        $file_name = "Daily Attendance: "; // With Date now
        return $pdf->setPaper($this->crosswise_short, 'portrait')->stream($file_name . '.pdf');
    }
    public function daily_time_record_report($_start_date, $_end_date)
    {
        $_dates = array();
        $start = $current = strtotime($_start_date);
        $end = strtotime($_end_date);
        while ($current <= $end) {
            $_dates[] = date('Y-m-d', $current);
            $current = strtotime('+1 days', $current);
        }
        $_employees = Staff::orderBy('staff.department', 'asc')->where('is_removed', false)
            ->orderBy('staff.last_name', 'asc')->get();
        $pdf = PDF::loadView("widgets.report.employee.weekly_report_attendance", compact('_employees', '_dates'));
        $file_name = "Daily Attendance: "; // With Date now
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
    public function health_check()
    {
        $_employees = Staff::orderBy('staff.department', 'asc')
            ->orderBy('staff.last_name', 'asc')->get();
        $pdf = PDF::loadView("widgets.report.employee.health_check_report", compact('_employees'));
        $file_name = "Health Check: "; // With Date now
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
}
