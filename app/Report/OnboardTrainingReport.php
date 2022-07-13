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
        $_details = $_data->assessment_details;
        $_item = $_data->enrollment_assessment->course_id == 1 ? [15, 10] : [40, 15];
        $_assessment = array(
            'written_score' => $_data->onboard_examination->result->count(),
            'written_final_score' => (($_data->onboard_examination->result->count() / 40) * 100) * .30,
            'practical_score' => $_details->practical_score,
            'practical_item' => $_item[0],
            'practical_final_score' => (($_details->oral_score / $_item[1]) * 100) * .30,
            'oral_score' => $_details->oral_score,
            'oral_item' => $_item[1],
            'oral_final_score' => (($_details->oral_score / $_item[1]) * 100) * .40,
            'assesor' => strtoupper($_details->staff->first_name . ' ' . $_details->staff->last_name),
            'total_score' => ((($_data->onboard_examination->result->count() / 40) * 100) * .30) + ((($_details->oral_score / $_item[1]) * 100) * .30) + ((($_details->oral_score / $_item[1]) * 100) * .40)
        );
        // Import PDF Class
        $pdf = PDF::loadView($_layout, compact('_data', '_assessment'));
        // Set the Filename of report
        $file_name = 'BMA OBT-20: ' . strtoupper($_data->last_name . ', ' . $_data->first_name) . '.pdf';
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
}
