<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class SummaryGradeSheet implements FromCollection, WithHeadings, WithTitle, WithMapping
{
    public function __construct($_course, $_request, $curriculum)
    {
        $this->course = $_course;
        $this->request = $_request;
        $this->curriculum = $curriculum;
        $this->subject_curriculum = $curriculum->subject([$_course->id, $_request->_year_level, Auth::user()->staff->current_academic()->semester])->get();
    $this->subject_list = $curriculum->subject_lists([$_course->id, $_request->_year_level, Auth::user()->staff->current_academic()->semester])->get();
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        //return $this->course->student_list;
        return $this->course->grading_student_list($this->curriculum)->get();
    }
    public function headings(): array
    {
        $count = 0;
        $_data = [$count => 'STUDENT NAME'];
        foreach ($this->subject_curriculum as $key => $value) {
            $count += 1;
            $_data += [$count => $value->subject->subject_code, $count += 1 => 'UNITS'];
        }
        $_data += [$count += 1 => 'REMARKS', $count += 1 => 'GEN AVERAGE'];
        return $_data;
    }
    public function title(): string
    {
        $_name = $this->curriculum->curriculum_name;
        return strtoupper($_name);
    }
    public function map($_data): array
    {
        $count = 0;
        $_cell_data = [$count => strtoupper($_data->last_name . ', ' . $_data->first_name . ' ' . $_data->middle_name[0] . ". ")];
        $_total_units = 0;
        foreach ($this->subject_list as $key => $value) {
            $_subject_class = $value->curriculum_subject_class($_data->section_id);
            if ($_subject_class) {
                if ($_subject_class->grade_final_verification) {
                    $_final_grade = number_format($_data->student->final_grade($_subject_class->id, 'finals'), 2);
                    $_final_grade = number_format($_data->student->percentage_grade($_final_grade),2);
                    if ($value->subject->subject_code == 'BRDGE') {
                        $_final_grade = $_data->student->enrollment_status->bridging_program == 'with' ? $_final_grade : '';
                    } else {
                        $_final_grade = $_final_grade;
                    }
                } else {
                    $_final_grade = '-';
                }
            } else {
                $_final_grade = '';
            }
             $_cell_data += [$count += 1 =>$_final_grade];
            $_cell_data += [$count += 1 => $value->subject->units];
            $_total_units += $value->subject->units;
        }
        $_cell_data += [$count += 1 => $_total_units];
        return $_cell_data;
        return $_data;
    }
}
