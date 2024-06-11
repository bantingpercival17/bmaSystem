<?php

namespace App\Exports\WorkBook;

use App\Exports\WorkSheet\MedicalMonitoringSheets;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MedicalMonitoringBook implements WithMultipleSheets
{
    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function sheets(): array
    {
        $sheet = [];
        foreach ($this->data as $key => $section) {
            $sheet[$key] = new MedicalMonitoringSheets($section);
        }
        return $sheet;
    }
}
