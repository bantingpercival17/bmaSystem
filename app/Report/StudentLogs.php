<?php

namespace App\Report;

use App\Models\Staff;
use Barryvdh\DomPDF\Facade as PDF;

class StudentLogs
{
    public function __construct()
    {

        $this->legal = [0, 0, 612.00, 1008.00];
    }
    public function student_handbook_logs($_data)
    {
        $_data_logs = $_data->enrollment_list;
        $pdf = PDF::loadView("widgets.report.student.student_handbook_logs", compact('_data_logs'));
        return $pdf->setPaper($this->legal, 'portrait')->stream($_data->school_year . '.pdf');
    }
}
