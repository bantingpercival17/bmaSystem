<?php

namespace App\Exports;

use App\Models\CourseOffer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CourseApplicantMedicalList implements WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($_category)
    {
        $this->course = CourseOffer::where('is_removed', false)->get();
        $this->category = $_category;
    }

    public function sheets(): array
    {
        $sheets = [];
        foreach ($this->course as $key => $value) {
            # code...
            $_data = $value[$this->category];
            $sheet_name = $value->course_name;
            $sheets[$key] = new ApplicantMedicalList($_data, $sheet_name);
        }
       
        return $sheets;
    }
}
