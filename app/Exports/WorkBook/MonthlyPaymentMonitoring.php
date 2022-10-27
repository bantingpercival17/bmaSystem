<?php

namespace App\Exports\WorkBook;

use App\Exports\WorkSheet\MonthlyPaymentMonitoringSheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MonthlyPaymentMonitoring implements WithMultipleSheets
{
    public function __construct($_section)
    {
        $this->sections = $_section;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function sheets(): array
    {
        $sheet = [];
        foreach ($this->sections as $key => $section) {
            $sheet[$key] = new MonthlyPaymentMonitoringSheet($section);
        }
        return $sheet;
    }
}
