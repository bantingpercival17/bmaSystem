<?php

namespace App\Report\Students;

use App\Models\EnrollmentAssessment;
use App\Models\StudentDetails;
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
        $_form_number = $_enrollment_assessment->course_id == 3 ? 'FORM RG-04' : 'FORM RG-03';
        $file_name = $_form_number . ' - ' . strtoupper($_student->last_name . ', ' . $_student->first_name . ' ' . $_student->middle_name);
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
    public function application_form($_data)
    {
        $_student = StudentDetails::find($_data);
        $_enrollment_assessment = $_student->enrollment_assessment;
        $_form_number = $_enrollment_assessment->course_id == 3 ? 'FORM RG-02' : 'FORM RG-01';
        $pdf = PDF::loadView("widgets.report.student.student_application_form", compact('_student', '_enrollment_assessment'));
        $file_name = $_form_number . ' - ' . strtoupper($_student->last_name . ', ' . $_student->first_name . ' ' . $_student->middle_name);
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
}
