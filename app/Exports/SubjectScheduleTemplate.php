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
            'SUBJECT CODE',
            'SECTION CODE',
            'SUBJECT',
            'SUBJECT DESCRIPTION',
            'TEACHER EMAIL',
            'TEACHER NAME',
        ];
    }
    public function map($_data): array
    {
        $_subject_handle = $this->section->subject_handle($_data->id);
        return  [
            base64_encode($this->section->academic->id),
            base64_encode($_data->id),
            base64_encode($this->section->id),
            $_data->subject_code,
            $_data->subject_name,
            $_subject_handle ? $_subject_handle->staff->user->email : '',
            $_subject_handle ? $_subject_handle->staff->first_name . ' ' . $_subject_handle->staff->last_name : '',
            //$this->section->subject_handle($_data->id),
        ];
    }
    public function title(): string
    {
        return strtoupper($this->section->section_name);
    }
}
