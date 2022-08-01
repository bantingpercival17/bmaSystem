<?php

namespace App\Report;

use Barryvdh\DomPDF\Facade as PDF;

class CourseSyllabusReport
{
    public function __construct()
    {
        $this->legal = [0, 0, 612.00, 1008.00];
        $this->path = "widgets.report.course-syllabus";
    }
    public function part_one($_data)
    {
        // Set the Layout for the report
        $_layout = $this->path . '.course_specification';
        // Import PDF Class
        $pdf = PDF::loadView($_layout, compact('_data'));
        // Set the Filename of report
        // Name format PART - SUBJECT CODE - DATE GENERATED
        $file_name = 'PART A: COURSE SPECIFICATION-' . $_data->subject->subject_code . '-' . date('Ymd') . '.pdf';
        return $pdf->setPaper($this->legal, 'landscape')->stream($file_name . '.pdf');
    }
    public function part_two($_data)
    {
        // Set the Layout for the report
        $_layout = $this->path . '.course_outline';
        // Import PDF Class
        $pdf = PDF::loadView($_layout, compact('_data'));
        // Set the Filename of report
        // Name format PART - SUBJECT CODE - DATE GENERATED
        $file_name = 'PART B: COURSE OUTLINE AND TIMETABLE-' . $_data->subject->subject_code . '-' . date('Ymd') . '.pdf';
        return $pdf->setPaper($this->legal, 'landscape')->stream($file_name . '.pdf');
    }
}
