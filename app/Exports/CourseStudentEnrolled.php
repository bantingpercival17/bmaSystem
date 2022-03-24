<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CourseStudentEnrolled implements WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($_course)
    {
        $this->course = $_course;
    }

    public function sheets(): array
    {
        $sheets = [];
        $_levels = [11, 12];
        $_levels = $this->course->id == 3 ? [1, 2, 3, 4] : $_levels;
        foreach ($_levels as $key => $_level) {
            $sheets[] = new YearLevelStudentEnrolled($this->course, $_level);
        }
        return $sheets;
    }
}
