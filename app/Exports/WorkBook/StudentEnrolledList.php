<?php

namespace App\Exports\WorkBook;

use App\Exports\WorkSheet\StudentInformationSheets;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StudentEnrolledList implements WithMultipleSheets
{
    public function __construct($course)
    {
        $this->course = $course;
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
            $sheets[$key] = new StudentInformationSheets($this->course, $_level);
        }
        return $sheets;
    }
}
