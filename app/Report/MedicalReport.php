<?php

namespace App\Report;

use App\Models\CourseOffer;
use Barryvdh\DomPDF\Facade as PDF;

class MedicalReport
{
    public function __construct()
    {
        $this->legal = [0, 0, 612.00, 1008.00];
        $this->super_legal = [0, 0, 612.00, 1085.538];
        $this->path = "widgets.report.medical";
    }


    public function applicant_medical_report($data)
    {
        $pdf = PDF::loadView($this->path . '.applicant-medical-report', compact('data'));
        $file_name = "MEDICAL REPORT";
        return $pdf->setPaper($this->super_legal, 'portrait')->stream($file_name . '.pdf');
    }

    public function student_medical_report($academic)
    {
        $courses = CourseOffer::where('is_removed', false)->get();
        $pdf = PDF::loadView($this->path . '.student-medical-report', compact('courses'));
        $file_name = "MEDICAL REPORT - MIDSHIPMAN";
        return $pdf->setPaper($this->super_legal, 'portrait')->stream($file_name . '.pdf');
    }
}
