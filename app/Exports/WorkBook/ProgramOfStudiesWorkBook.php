<?php

namespace App\Exports\WorkBook;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProgramOfStudiesWorkBook implements WithMultipleSheets
{
    public $course;
    public $curriculum;
    public function __construct($course, $curriculum)
    {
        $this->course = $course;
        $this->curriculum = $curriculum;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function sheets(): array
    {
        $sheets = [];
        $_levels = [11, 12];
        $_levels = $this->course->id != 3 ? [1, 2, 3, 4] : $_levels;
        foreach ($_levels as $key => $_level) {
        }
        return $sheets;
    }
}
