<?php

namespace App\Exports;

use App\Models\Curriculum;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CurriculumSummaryGradeSheet implements WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($_course, $_request)
    {
        $this->course = $_course;
        $this->request = $_request;
    }

    public function sheets(): array
    {
        $_curriculum = Curriculum::where('is_removed', false)->get();
        foreach ($_curriculum as $key => $curriculum) {
            if (count($curriculum->student_enrolled) > 0) {
                $sheets[$key] = new SummaryGradeSheet($this->course, $this->request, $curriculum);
            }
        }
        return $sheets;
    }
}
