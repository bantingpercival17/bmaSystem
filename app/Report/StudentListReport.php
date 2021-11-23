<?php

namespace App\Report;

use App\Models\AcademicYear;
use App\Models\CourseOffer;
use App\Models\Section;
use App\Models\StudentSection;
use Barryvdh\DomPDF\Facade as PDF;

class StudentListReport
{
    public function __construct()
    {

        $this->legal = [0, 0, 612.00, 1008.00];
    }

    public function build()
    {
        $pdf = PDF::loadView("widgets.report.grade.grading_sheet_report", compact('_students', '_subject'));
        $file_name = strtoupper($this->subject->curriculum_subject->subject->subject_code);
        return $pdf->setPaper($this->legal, 'landscape')->stream($file_name . '.pdf');
    }
    public function student_section_list($_data)
    {
        //return $_data;
        $_sections = Section::where($_data)->get();
        $_academic = AcademicYear::find($_data['academic_id']);
        $_course = CourseOffer::find($_data['course_id']);
        /* foreach ($_sections as $key => $_section) {
            $pdf = PDF::loadView("widgets.report.student.student_section_list", compact('_sections'));
            $file_name = strtoupper($_course->course_code) . "-" . $_data['year_level'] . "-STUDENT-SECTION-LIST";
            return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
        } */
        $pdf = PDF::loadView("widgets.report.student.student_section_list", compact('_sections','_academic'));
        $file_name = strtoupper($_course->course_code) . " : " . $_data['year_level'] . "/C - FORM : STUDENT SECTION LIST " . $_academic->school_year . " - " . $_academic->semester;
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
}
