<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class SummaryGradeSheet implements FromCollection, WithHeadings, WithTitle, WithMapping
{
    public function __construct($_course, $level, $_request, $curriculum)
    {
        $this->course = $_course;
        $this->level = $level;
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
        //return $this->course->grading_student_list($this->curriculum)->get();
        return $this->course->student_enrollment_list([$this->level, $this->curriculum->id])->get();
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
        $ext = $_data->student->extention_name == 'N/A' ? '' : $_data->student->extention_name;
        $student = strtoupper($_data->student->last_name . ', ' . $_data->student->first_name . ' ' . $_data->student->middle_name . ' ' . $ext);
        $_cell_data = [$count => $student];
        $_total_units = 0;
        foreach ($this->subject_list as $key => $subject) {
            $_final_grade = $_data->student->student_final_subject_grade($subject);
            $_cell_data += [$count += 1 => $_final_grade];
            $_cell_data += [$count += 1 => $subject->subject->units];
        }
        /*  foreach ($this->subject_list as $key => $value) {
            $_subject_class = $value->curriculum_subject_class($_data->section_id);
            if ($_subject_class) {
                if ($_subject_class->grade_final_verification) {
                    if (base64_decode(request()->input('_academic')) >= 5) {
                        $final_grade = $_subject_class->student_computed_grade($_data->student_id)->first();
                        if ($final_grade) {
                            $_final_grade = number_format($_data->student->percentage_grade(base64_decode($final_grade->final_grade)), 2);
                        } else {
                            $_final_grade = '';
                        }
                    } else {
                        $_final_grade = number_format($_data->student->final_grade_v2($_subject_class->id, 'finals'), 2);
                        $_final_grade = number_format($_data->student->percentage_grade($_final_grade), 2);
                    }

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
            $_cell_data += [$count += 1 => $_final_grade];
            $_cell_data += [$count += 1 => $value->subject->units];
            $_total_units += $value->subject->units;
        }
        $_cell_data += [$count += 1 => $_total_units]; */
        $_cell_data += [$count += 1 => $_total_units];
        return $_cell_data;
    }
}
