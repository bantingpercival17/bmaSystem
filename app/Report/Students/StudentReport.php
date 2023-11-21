<?php

namespace App\Report\Students;

use App\Models\EnrollmentAssessment;
use App\Models\StudentDetails;
use App\Models\StudentSection;
use Barryvdh\DomPDF\Facade as PDF;

class StudentReport
{
    public $legal;
    public $crosswise_legal;
    public $crosswise_short;
    public $path;
    public function __construct()
    {

        $this->legal = [0, 0, 612.00, 1008.00];
        $this->crosswise_legal = [0, 0, 612, 504.00];
        $this->crosswise_short = [0, 0, 612, 396];
        $this->path = "widgets.report.student.";
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
    function enrollment_certificate($assessment)
    {
        $student = $assessment->student;
        $pdf = PDF::loadView($this->path . 'student_enrollment_certificate', compact('student', 'assessment'));
        $formNumber = $assessment->course_id == 3 ? 'FORM RG-04' : 'FORM RG-03';
        $fileName = $formNumber . ' - ' . strtoupper($student->last_name . ', ' . $student->first_name . ' ' . $student->middle_name);
        return $pdf->setPaper($this->legal, 'portrait')->stream($fileName . '.pdf');
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
    public function certificate_of_grade($_student, $_section)
    {
        $view = base64_decode(request()->input('_academic')) >= 5 ? 'widgets.report.student.certificate_of_grades_v2' : 'widgets.report.student.certificate_of_grades';
        $pdf = PDF::loadView($view, compact('_student', '_section'));
        $file_name =   'FORM AD-02a  - ' . strtoupper($_student->last_name . ', ' . $_student->first_name . ' ' . $_student->middle_name);
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
    public function student_card_report($_student)
    {
        $pdf = PDF::loadView($this->path . 'student-card-report', compact('_student'));
        $file_name =   'BMA FORM ACC-12  - ' . strtoupper($_student->last_name . ', ' . $_student->first_name . ' ' . $_student->middle_name);
        return $pdf->setPaper($this->crosswise_legal, 'portrait')->stream($file_name . '.pdf');
    }
    public function student_qr_code($_student)
    {
        $pdf = PDF::loadView($this->path . 'student-qr-code', compact('_student'));
        $file_name =   'BMA QR-CODE  - ' . strtoupper($_student->last_name . ', ' . $_student->first_name . ' ' . $_student->middle_name);
        return $pdf->setPaper($this->crosswise_short, 'portrait')->stream($file_name . '.pdf');
    }
}
