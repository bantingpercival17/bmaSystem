<?php

namespace App\Report;

use Barryvdh\DomPDF\Facade as PDF;

class OnboardTrainingReport
{

    public function __construct()
    {

        $this->legal = [0, 0, 612.00, 1008.00];
        $this->path = "widgets.report.onboard";
    }

    public function narative_summary_report($_data)
    {
        // Set the Layout for the report
        $_layout = $this->path . '.narative-summary-report';
        // Import PDF Class
        $pdf = PDF::loadView($_layout, compact('_data'));
        // Set the Filename of report
        $file_name = 'NARATIVE MONITORING REPORT' . '.pdf';
        return $pdf->setPaper($this->legal, 'landscape')->stream($file_name . '.pdf');
    }
    public function monthly_summary_report($_data)
    {
        // Set the Layout for the report
        $_layout = $this->path . '.monthly-summary-report';
        $_documents = $_data->narrative_documents(request()->input('_month'))->get();
        // Import PDF Class
        $pdf = PDF::loadView($_layout, compact('_data', '_documents'));
        // Set the Filename of report
        $file_name = 'BMA OBT-20: ' . strtoupper($_data->last_name . ', ' . $_data->first_name) . '.pdf';
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
    public function assessment_report($_data)
    {
        // Set the Layout for the report
        $_layout = $this->path . '.onboard-assessment-report';
        $_documents = $_data->narrative_documents(request()->input('_month'))->get();
        // Import PDF Class
        $pdf = PDF::loadView($_layout, compact('_data', '_documents'));
        // Set the Filename of report
        $file_name = 'BMA OBT-20: ' . strtoupper($_data->last_name . ', ' . $_data->first_name) . '.pdf';
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
}
