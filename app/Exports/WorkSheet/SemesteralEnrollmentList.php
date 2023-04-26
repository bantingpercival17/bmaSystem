<?php

namespace App\Exports\WorkSheet;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SemesteralEnrollmentList implements FromCollection, ShouldAutoSize, WithMapping, WithHeadings
{
    public function __construct($enrollment_list)
    {
        $this->enrollment_list = $enrollment_list;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->enrollment_list;
    }
    public function headings(): array
    {
        return [
            'STUDENT NUMBER',
            'GENDER',
            'YEAR LEVEL',
            'COURSE',
            'SUBJECT',
        ];
    }
    public function map($data): array
    {
        $extension = strtolower($data->extention) === 'n/a' ? '' : $data->extention; // Check the Extension Name
        $middle_name = strtolower($data->middle_name) === 'n/a' ? '' : $data->middle_name; // Check Middle Name
        $student_name = strtoupper($data->last_name . ', ' . $data->first_name . ' ' . $extension . ' ' . $middle_name); // Student Name
        $year_level = $data->year_level == 1 ? 'IV' : ($data->year_level == 2 ? 'III' : ($data->year_level == 3 ? 'II' : ($data->year_level == 4 ? 'I' : '')));
        $item[0] = $student_name;
        $item[1] = strtoupper($data->sex[0]);
        $item[2] = $year_level;
        $item[3] = $data->course->course_name;
        $index = 4;
        $subjects = $data->course->course_subject([$data->curriculum_id, $data->year_level, Auth::user()->staff->current_academic()->semester]);
        foreach ($subjects as $key => $subject) {
            $item[$index] = $subject->subject_code;
            $index += 1;
            $item[$index] = $subject->units;
            $index += 1;
        }
        return $item;
    }
}
