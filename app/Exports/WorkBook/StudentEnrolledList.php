<?php

namespace App\Exports\WorkBook;

use App\Exports\WorkSheet\StudentInformationSheets;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StudentEnrolledList implements WithMultipleSheets
{
    public $course;
    public $cancellation;
    public function __construct($course, $data)
    {
        $this->course = $course;
        $this->cancellation = $data;
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
            $sheets[$key] = new StudentInformationSheets($this->course, $_level, $this->cancellation);
        }
        return $sheets;
    }
}
