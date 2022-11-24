<?php

namespace App\Report;

use Barryvdh\DomPDF\Facade as PDF;

class GradingSheetReport
{
    public function __construct($_student, $_subject)
    {
        $this->student = $_student;
        $this->subject = $_subject;
        $this->legal = [0, 0, 612.00, 1008.00];
    }

    public function build()
    {
        $_subject = $this->subject;
        $_students = $this->student;
        $pdf = PDF::loadView("widgets.report.grade.grading_sheet_report", compact('_students', '_subject'));
        $file_name = strtoupper($this->subject->curriculum_subject->subject->subject_code);
        return $pdf->setPaper($this->legal, 'landscape')->stream($file_name . '.pdf');
    }
    public function form_ad_01()
    {
        $_subject = $this->subject;
        $_students = $this->student;
        $pdf = PDF::loadView("widgets.report.grade-v2.form_ad_01", compact('_students', '_subject'));
        $file_name = strtoupper($this->subject->curriculum_subject->subject->subject_code) . " - FORM AD 01";
        return $pdf->setPaper($this->legal, 'landscape')->stream($file_name . '.pdf');
    }
    public function form_ad_01_v1($_columns)
    {
        $_subject = $this->subject;
        $_students = $this->student;
        $pdf = PDF::loadView("widgets.report.grade-v2.form_ad_01_v1", compact('_students', '_subject', '_columns'));
        $file_name = strtoupper($this->subject->curriculum_subject->subject->subject_code) . " - FORM AD 01";
        return $pdf->setPaper($this->legal, 'landscape')->stream($file_name . '.pdf');
    }
    public function form_ad_02()
    {
        $_subject = $this->subject;
        $_students = $this->student;
        $pdf = PDF::loadView("widgets.report.grade-v2.form_ad_02", compact('_students', '_subject'));
        $file_name = strtoupper($this->subject->curriculum_subject->subject->subject_code) . " - FORM AD 02";
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
}
