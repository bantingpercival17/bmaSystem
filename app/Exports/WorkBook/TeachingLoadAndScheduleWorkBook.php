<?php

namespace App\Exports\WorkBook;

use App\Exports\EmployeeListExport;
use App\Exports\SubjectScheduleTemplate;
use App\Exports\WorkSheet\TeachingLoadPerLevel;
use App\Models\CourseOffer;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TeachingLoadAndScheduleWorkBook implements WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }
    public function sheets(): array
    {
        $sheets = [];
        $sheetCounts = 0;
        //$course = CourseOffer::find($this->data->course_id);
        $_sections = $this->data->course->section([$this->data->academic_id, $this->data->year_level])->get();
        if (count($_sections) > 0) {
            foreach ($_sections as $key => $section) {
                $sheets[$key] = new TeachingLoadPerLevel($section, $this->data);
            }
            $sheetCounts = count($_sections);
        }
        $sheets[$sheetCounts] = new EmployeeListExport;
        return $sheets;
    }
}
