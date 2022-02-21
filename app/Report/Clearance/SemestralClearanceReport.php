<?php

namespace App\Report\Clearance;

use Barryvdh\DomPDF\Facade as PDF;


class SemestralClearanceReport
{
    public function __construct()
    {
        $this->legal = [0, 0, 612.00, 1008.00];
    }
    public function semestral_clearance_overview($_section)
    {
        $pdf = PDF::loadView("widgets.report.clearance.semestral_clearance_overview_report", compact('_section'));
        $file_name = strtoupper($_section->section_name);
        return $pdf->setPaper($this->legal, 'landscape')->stream($file_name . '.pdf');
    }
}
