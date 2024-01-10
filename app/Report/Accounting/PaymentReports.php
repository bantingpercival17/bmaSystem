<?php

namespace App\Report\Accounting;

use Barryvdh\DomPDF\Facade as PDF;


class PaymentReports
{
    public $legal;
    public $short;
    public $crosswise_short;
    public $crosswise_legal;
    public $path;
    public function __construct()
    {
        /* The paper size. */
        $this->legal = [0, 0, 612.00, 1008.00];
        $this->short = [0, 0, 612.00, 792.00];
        $this->crosswise_short = [0, 0, 612, 396];
        $this->crosswise_legal = [0, 0, 612, 504.00];
        $this->path = "widgets.report.accounting";
    }
    /**
     * It's a function that generates a PDF file from a view
     * 
     * @param _data The data that will be passed to the view
     * 
     * @return The PDF file is being returned.
     */
    public function monthly_payment_report($_sections)
    {
        $pdf = PDF::loadView($this->path . '.student-monthly-payment-report', compact('_sections'));
        $file_name = "Monthly Payment Report"; // With Date now
        return $pdf->setPaper($this->legal, 'landscape')->stream($file_name . '.pdf');
    }
    public function examination_permit($section)
    {
        $view = "widgets.report.accounting.examination-permit";
        $pdf = PDF::loadView($view, compact('section'));
        $file_name = 'Test Permit - ';
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
    function student_account_card_by_section($students, $filename, $academic)
    {
        $file_name =   'FORM ACC-12-' . $filename."".date('dmy');
        $pdf = PDF::loadView($this->path . '.student-card-report', compact('students', 'academic'));
        return $pdf->setPaper($this->crosswise_legal, 'portrait')->download($file_name . '.pdf');
    }
}
