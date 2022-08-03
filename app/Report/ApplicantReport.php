<?php

namespace App\Report;

use Barryvdh\DomPDF\Facade as PDF;

class ApplicantReport
{

    public function __construct()
    {
        $this->legal = [0, 0, 612.00, 1008.00];
        $this->path = "widgets.report.applicant";
    }

    public function applicant_form($_account)
    {
        $pdf = PDF::loadView("widgets.report.student.applicant_application_form", compact('_account'));
        $file_name = 'FORM RG-01 - ' . strtoupper($_account->applicant->last_name . ', ' . $_account->applicant->first_name . ' ' . $_account->applicant->middle_name);
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }

    public function applicant_examination_logs($_data)
    {
        // Set the Layout for the report
        $_layout = $this->path . '.examination-logs';
        // Import PDF Class
        $pdf = PDF::loadView($_layout, compact('_data'));
        // Set the Filename of report
        // Name format PART - SUBJECT CODE - DATE GENERATED
        $file_name = 'EXAMINATION SYSTEM LOGS -' . $_data->applicant_number . '-' . date('Ymd') . '.pdf';
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
}
