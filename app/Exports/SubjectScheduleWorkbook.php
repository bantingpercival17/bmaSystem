<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SubjectScheduleWorkbook implements WithMultipleSheets
{
    public function __construct($_course, $_data)
    {
        $this->course = $_course;
        $this->data = $_data;
    }

    public function sheets(): array
    {
        $sheets = [];
        $_sections = $this->course->section(json_decode(base64_decode($this->data->section)))->get();
        foreach ($_sections as $key => $section) {
            $sheets[$key] = new SubjectScheduleTemplate($this->course, $this->data, $section);
        }
        return $sheets;
    }
}
