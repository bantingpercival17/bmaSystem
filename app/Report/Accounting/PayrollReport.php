<?php

namespace App\Report;

use Barryvdh\DomPDF\Facade as PDF;


class PayrollReport
{
    public function __construct()
    {
        $this->legal = [0, 0, 612.00, 1008.00];
    }
    public function payroll_generated_report_without()
    {
        # code...
    }
}
