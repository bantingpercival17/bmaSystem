<?php

namespace App\Report;

use Barryvdh\DomPDF\Facade as PDF;

class ApplicantReport
{

    public function __construct()
    {

        $this->legal = [0, 0, 612.00, 1008.00];
    }

    public function applicant_form($_account)
    {

        $pdf = PDF::loadView("widgets.report.student.applicant_application_form", compact('_account'));
        $file_name = 'FORM RG-01 - ' . strtoupper($_account->applicant->last_name . ', ' . $_account->applicant->first_name . ' ' . $_account->applicant->middle_name);
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
}
