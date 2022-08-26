<?php

namespace App\Report\Accounting;

use Barryvdh\DomPDF\Facade as PDF;


class PaymentReceipt
{
    public function __construct()
    {
        /* The paper size. */
        $this->legal = [0, 0, 612.00, 1008.00];
        //$this->short = [0,0,612.00,792.00];
        $this->crosswise_short = [0, 0, 612, 396];
    }
    /**
     * It's a function that generates a PDF file from a view
     * 
     * @param _data The data that will be passed to the view
     * 
     * @return The PDF file is being returned.
     */
    public function print($_data)
    {

        $pdf = PDF::loadView("widgets.report.accounting.receipt", compact('_data'));
        $file_name = "Accounting Receipt: " . $_data->or_number; // With Date now
        return $pdf->setPaper($this->crosswise_short, 'portrait')->stream($file_name . '.pdf');
    }
}
