<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CourseStudentMedicalList implements WithMultipleSheets
{
    public function __construct($_category, $_course)
    {
        $this->course = $_course;
        $this->category = $_category;
    }

    public function sheets(): array
    {
        $sheets = [];
        $_levels = $this->course->id != 3 ? [1, 2, 3, 4] : [11, 12];
        foreach ($_levels as $key => $level) {
            /* $_table_content = array(
                array('scheduled', $this->course->student_medical_scheduled_year($level)->get()),
                  array('waiting for result', 'student_medical_waiting_for_result'),
                array('passed', 'student_medical_passed'),
                array('pending', 'student_medical_pending'),
                array('failed', 'student_medical_failed')
            ); */
            /*  foreach ($_table_content as $key => $content) {
                $_data =  $this->category == $content[0] ? $content[1] : [];
            } */
            $_data = $this->course->student_medical_scheduled_year($level)->get();

            $sheet_name = Auth::user()->staff->convert_year_level($level);
            $sheets[$key] = new StudentMedicalList($_data, $sheet_name);
        }
        return $sheets;
    }
}
