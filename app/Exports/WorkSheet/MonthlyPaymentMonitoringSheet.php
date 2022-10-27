<?php

namespace App\Exports\WorkSheet;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class MonthlyPaymentMonitoringSheet implements FromCollection, ShouldAutoSize,  WithMapping,  WithHeadings, WithTitle
{
    public function __construct($_section)
    {
        $this->section = $_section;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->section->student_sections;
    }
    public function headings(): array
    {
        return [
            'STUDENT NUMBER',
            'STUDENT NAME',
            'UPON ENROLLMENT',
            '1ST MONTHLY', '2ND MONTHLY', '3RD MONTHLY', '4TH MONTHLY', 'REMAINING BALANCE', 'TOTAL PAYMENT'
        ];
    }
    public function map($_data): array
    {
        $_particular = [
            'UPON ENROLLMENT',
            '1ST MONTHLY', '2ND MONTHLY', '3RD MONTHLY', '4TH MONTHLY'
        ];
        $_index = 2;
        $_content[0] =  $_data->student->account->student_number;
        $_content[1] = strtoupper($_data->student->last_name . ", " . $_data->student->first_name . " " . $_data->student->middle_name);
        foreach ($_particular as $key => $value) {
            $_content[$_index] =  $_data->student->enrollment_assessment->payment_assessments->payment_remarks($value) ?: '';
            $_index += 1;
        }
        $_payment_details = $_data->student->enrollment_assessment->payment_assessments;
        $_content[$_index] =  number_format(($_payment_details->course_semestral_fee_id ? $_payment_details->course_semestral_fee->total_payments($_payment_details) : $_payment_details->total_payment) - $_payment_details->total_paid_amount->sum('payment_amount'), 2);
        $_index += 1;
        $_content[$_index] = number_format(($_payment_details->course_semestral_fee_id ? $_payment_details->course_semestral_fee->total_payments($_payment_details) : $_payment_details->total_payment));
        return $_content;
    }
    public function title(): string
    {
        return strtoupper($this->section->section_name);
    }
}
