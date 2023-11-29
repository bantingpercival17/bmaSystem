<?php

namespace App\Report;

use Barryvdh\DomPDF\Facade as PDF;

class OnboardTrainingReport
{

    public $legal;
    public $path;
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
    public function narative_summary_report_v2($_data)
    {
        // Set the Layout for the report
        $_layout = $this->path . '.narative-summary-report-v2';
        // Import PDF Class
        $pdf = PDF::loadView($_layout, compact('_data'));
        // Set the Filename of report
        $file_name = 'NARATIVE MONITORING REPORT' . '.pdf';
        return $pdf->setPaper($this->legal, 'landscape')->stream($file_name . '.pdf');
    }
    public function monthly_summary_report($_data, $month)
    {
        // Set the Layout for the report
        $_layout = $this->path . '.monthly-summary-report';
        $_documents = $_data->narrative_documents($month)->get();
        // Import PDF Class
        $pdf = PDF::loadView($_layout, compact('_data', '_documents'));
        // Set the Filename of report
        $file_name = 'BMA OBT-20: ' . strtoupper($_data->last_name . ', ' . $_data->first_name) . '.pdf';
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
    public function monthlySummaryReport($data, $narrative)
    {
        // Set the Layout for the report
        $_layout = $this->path . '.monthly-summary-v2-report';
        // Import PDF Class
        $pdf = PDF::loadView($_layout, compact('data', 'narrative'));
        // Set the Filename of report
        $file_name = 'BMA OBT-20: ' . strtoupper($data->last_name . ', ' . $data->first_name) . '.pdf';
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
    public function assessment_report($_data)
    {
        // Set the Layout for the report
        $_layout = $this->path . '.onboard-assessment-report';
        $_details = $_data->assessment_details;
        $_item = $_data->enrollment_assessment->course_id == 1 ? [15, 10] : [40, 15];

        $_written_score = (($_data->onboard_examination->result->count() / 40) * 100) * .30;
        $_practical_score = (($_details->practical_score / $_item[0]) * 100) * .30;
        $_oral_score =  (($_details->oral_score / $_item[1]) * 100) * .40;
        $_total_score = number_format($_written_score, 2) + number_format($_practical_score, 2) + number_format($_oral_score, 2);
        $_assessment = array(
            'written_score' => $_data->onboard_examination->result->count(),
            'written_final_score' => $_written_score,
            'practical_score' => $_details->practical_score,
            'practical_item' => $_item[0],
            'practical_final_score' => $_practical_score,
            'oral_score' => $_details->oral_score,
            'oral_item' => $_item[1],
            'oral_final_score' => $_oral_score,
            'assesor' => strtoupper($_details->staff->first_name . ' ' . $_details->staff->last_name),
            'total_score' => $_total_score
        );
        // Import PDF Class
        $pdf = PDF::loadView($_layout, compact('_data', '_assessment'));
        // Set the Filename of report
        $file_name = 'BMA OBT-20: ' . strtoupper($_data->last_name . ', ' . $_data->first_name) . '.pdf';
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
}
