<?php

namespace App\Report\Accounting;

use Barryvdh\DomPDF\Facade as PDF;


class PaymentReceipt
{
    public $legal;
    public $crosswise_short;
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
        //return $pdf->setPaper($this->crosswise_short, 'portrait')->stream($file_name . '.pdf');
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
    public function print_recipt($_data)
    {

        $pdf = PDF::loadView("widgets.report.accounting.receipt_v2", compact('_data'));
        $file_name = "Accounting Receipt: " . $_data->or_number; // With Date now
        //return $pdf->setPaper($this->crosswise_short, 'portrait')->stream($file_name . '.pdf');
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }

    public function print_or_recipt($data, $printer)
    {
        $pdfLayout = 'widgets.report.accounting.';
        $layout = $printer == 'canon' ? 'or_receipt' : 'or_receipt_epson';
        $pdf = PDF::loadView($pdfLayout . $layout, compact('data'));
        $file_name = "Accounting Receipt: " . $data['orNumber']; // With Date now
        //return $pdf->setPaper($this->crosswise_short, 'portrait')->stream($file_name . '.pdf');
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
}
