<?php

namespace App\Report;

use Barryvdh\DomPDF\Facade as PDF;

class ExecutiveFilesReport
{
    public function __construct()
    {
        $this->legal = [0, 0, 612.00, 1008.00];
        $this->path = "widgets.report.executive";
    }
    public function student_onboarding_report($_data)
    {
        $_sections = $_data;
        // Set the Layout for the report
        $_layout = $this->path . '.onboarding-report';
        // Import PDF Class
        $pdf = PDF::loadView($_layout, compact('_sections'));
        // Set the Filename of report
        $file_name = 'LIBERTY REPORT - ' . date('Ymd') . '.pdf';
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
}
