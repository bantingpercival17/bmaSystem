<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class SubjectScheduleTemplate implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithTitle

{
    public function __construct($_course, $_data, $_section)
    {
        $this->course = $_course;
        $this->data = $_data;
        $this->section = $_section;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->course->course_subject(json_decode(base64_decode($this->data->data)));
    }
    public function headings(): array
    {
        return [
            'ACADEMIC CODE',
            'CURRICULUM CODE',
            'SUBJECT CODE',
            'SUBJECT NAME',
            'SECTION CODE',
            'SECTION NAME',
            'TEACHER EMAIL',
            'TEACHER NAME',


        ];
    }
    public function map($_data): array
    {
        return  [
            base64_encode($this->section->academic->id),
            base64_encode($_data->curriculum_id),
            base64_encode($_data->id),
            $_data->subject_name,
            base64_encode($this->section->id),
            $this->section->section_name
        ];
    }
    public function title(): string
    {
        return strtoupper($this->section->section_name);
    }
}
