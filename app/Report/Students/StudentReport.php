<?php

namespace App\Report\Students;

use App\Models\EnrollmentAssessment;
use Barryvdh\DomPDF\Facade as PDF;

class StudentReport
{
    public function __construct()
    {

        $this->legal = [0, 0, 612.00, 1008.00];
    }

    public function enrollment_information($_assessment_id)
    {
        $_enrollment_assessment = EnrollmentAssessment::find($_assessment_id);
        $_student = $_enrollment_assessment->student;
        $pdf = PDF::loadView("widgets.report.student.student_enrollment_information", compact('_student', '_enrollment_assessment'));
        $file_name = 'FORM RG-03 - STUDENT REGISTRATION';
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
}
