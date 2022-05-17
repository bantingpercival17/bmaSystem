<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Facades\Auth;
class CourseSectionStudentList implements WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($_course,$_level)
    {
        $this->course = $_course;
        $this->level = $_level;
    }

    public function sheets(): array
    {
        $sheets = [];
        $_data_sheet = $this->course->section([Auth::user()->staff->current_academic()->id, $this->level])->get();
        foreach ($_data_sheet as $key => $_data) {
            $sheets[$key] = new SectionStudentList($_data);
        }
        return $sheets;
    }
}
