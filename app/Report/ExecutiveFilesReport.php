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
        // Additional Data
        $_time_arrival = array(
            array('year_level' => 4, 'time_arrival' => 1730),
            array('year_level' => 3, 'time_arrival' => 1800),
            array('year_level' => 2, 'time_arrival' => 1830),
            array('year_level' => 1, 'time_arrival' => 1900)
        );
        // Import PDF Class
        $pdf = PDF::loadView($_layout, compact('_sections', '_time_arrival'));
        // Set the Filename of report
        $file_name = 'ONBOARDING MASTERLIST REPORT - ' . date('Ymd') . '.pdf';
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
    public function student_onboarding_absent_report($_data)
    {
        $_sections = $_data;
        // Set the Layout for the report
        $_layout = $this->path . '.onboarding-absent-report';
        // Additional Data
        // Import PDF Class
        $pdf = PDF::loadView($_layout, compact('_sections'));
        // Set the Filename of report
        $file_name = 'LIST OF MIDSHIPMAN ABSENT - ' . date('Ymd') . '.pdf';
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
}
