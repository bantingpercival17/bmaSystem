<?php

namespace App\Report;

use Barryvdh\DomPDF\Facade as PDF;

class GradingSheetReport
{
    public $student;
    public $subject;
    public $legal = "";
    public $super_legal = "";
    public function __construct($_student, $_subject)
    {
        $this->student = $_student;
        $this->subject = $_subject;
        $this->legal = [0, 0, 612.00, 1008.00];
        $this->super_legal = [0, 0, 612.00, 1085.538];
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
        return $pdf->setPaper($this->super_legal, 'landscape')->stream($file_name . '.pdf');
    }
    public function form_ad_01_v1($columns)
    {
        $subject = $this->subject;
        $students = $this->student;
        $contentNumber = 0;
        $contentCount = 40;
        $mainHeader = array(
            array('NAME OF MIDSHIPMAN', 3),
            array('quizzes', 11),
            array('oral exam', 6),
            array('research & work output', 11),
            array(request()->input('_period') . ' exam', 2)
        );
        $pdf = PDF::loadView("widgets.report.grade-v2.form_ad_01_v1", compact('students', 'subject', 'columns', 'contentNumber', 'contentCount'));
        $file_name = strtoupper($this->subject->curriculum_subject->subject->subject_code) . " - FORM AD 01";
        return $pdf->setPaper($this->legal, 'landscape')->stream($file_name . '.pdf');
    }
    public function form_ad_01_v1_1($period)
    {
        $subject = $this->subject;
        $students = $this->student;
        $contentNumber = 0;
        $contentCount = 20;
        $totalColSpan = 32;
        $columns = $this->form_ad_header($period);
        $pdf = PDF::loadView("widgets.report.grade-v2.form_ad_01_v1", compact('students', 'subject', 'columns', 'totalColSpan', 'contentNumber', 'contentCount', 'period'));
        $file_name = strtoupper($this->subject->curriculum_subject->subject->subject_code) . " - FORM AD 01";
        return $pdf->setPaper($this->super_legal, 'landscape')->stream($file_name . '.pdf');
    }
    public function form_ad_02()
    {
        $_subject = $this->subject;
        $_students = $this->student;
        $pdf = PDF::loadView("widgets.report.grade-v2.form_ad_02", compact('_students', '_subject'));
        $file_name = strtoupper($this->subject->curriculum_subject->subject->subject_code) . " - FORM AD 02";
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }

    function form_ad_header($period)
    {
        $subject = $this->subject;
        $mainHeader = array(
            array('NAME OF MIDSHIPMAN', 3),
            array('quizzes', 11),
            array('oral', 6),
            array('research & work output', 11),
            array($period . ' exam', 2),
        );
        $subHeader = array(
            array('no', 1, '15px'),
            array('std no.', 1, '45px'),
            array('complete name', 1, '170px'),
            array('q', 10, '20px'),
            array('15%', 1, '20px'),
            array('o', 5, '20px'),
            array(($this->subject->academic->id > 4 ? '20%' : '15%'), 1, '20px'),
            array('r', 10, '20px'),
            array($this->subject->academic->id > 4 ? '20%' : '15%', 1, '20px'),
            array($period[0] . 'E', 1, '10px'),
            array($this->subject->academic->id > 4 ? '45%' : '55%', 1, '10px'),
        );
        if ($subject->curriculum_subject->subject->laboratory_hours > 0 /* && $_subject_code !=  str_contains($_subject_code, 'P.E.') */) {

            $mainHeader[] = array('lec grade', 1);
            $mainHeader[] =   array('Scientific and Technical Experiments Demonstrations of Competencies Acquired', 10);
            $mainHeader[] = array('lab grade', 1);
            $subHeader[] = array('50%', 1, '10px');
            $subHeader[] = array('A', 10, '20px');
            $subHeader[] = array('50%', 1, '10px');
        }
        if ($period == 'midterm') {
            $mainHeader[] = array($period . ' Grade', 1);
            $mainHeader[] = array('point grade', 1);
            $subHeader[] = array('(fg)', 1, '40px');
            $subHeader[] = array('(pg)', 1, '40px');
        } else {
            $mainHeader[] = array($period . ' Grade', 1);
            $mainHeader[] = array('co grade', 1);
            $mainHeader[] = array('total final Grade', 1);
            $mainHeader[] = array('point grade', 1);
            $subHeader[] = array('(fg)', 1, '20px');
            $subHeader[] = array('', 1, '20px');
            $subHeader[] = array('', 1, '20px');
            $subHeader[] = array('(pg)', 1, '20px');
        }

        return array($mainHeader, $subHeader);
    }
}
