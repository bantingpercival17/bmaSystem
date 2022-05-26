<?php

namespace App\Exports;

use App\StudentAssessment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DepartmentBalanceSheet implements WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($_data)
    {
        $this->data  = $_data;
    }
    public function sheets(): array
    {
        $sheets = [];
        $_data = $this->data->id == 3 ? ['11', '12'] : ['1', '2', '3', '4'];
        foreach ($_data as $key => $data) {
            $sheets[] = new BalanceStudent($this->data, $data);
        }
        return $sheets;
    }
}
