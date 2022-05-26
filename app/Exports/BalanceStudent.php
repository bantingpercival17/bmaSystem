<?php

namespace App\Exports;

use App\AcademicYear;
use App\Models\PaymentAssessment;
use App\StudentAssessment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class BalanceStudent implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithEvents,  WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($_course, $_level)
    {
        $this->course = $_course;
        $this->level = $_level;
    }
    public function collection()
    {
        return  $this->course->enrolled_list($this->level)->get();
    }
    public function headings(): array
    {
        return [
            'STUDENT NUMBER',
            'STUDENT NAME',
            'MODE OF PAYMENT',
            'TOTAL PAYMENT',
            'PAID',
            'BALANCE',
            'BRIDGING PROGRAM'
        ];
    }
    public function map($_data): array
    {
        return [
            $_data->student->account->student_number,
            strtoupper(
                $_data->student->last_name . ", " . $_data->student->first_name . " " . $_data->student->middle_name
            ),
            $_data->payment_assessments->payment_mode == 1 ? 'INSTALLMENT' : "FULLPAYMENT",
            $_data->payment_assessments
                ? ($_data->payment_assessments->course_semestral_fee_id
                    ? number_format($_data->payment_assessments->course_semestral_fee->total_payments($_data->payment_assessments), 2)
                    : number_format($_data->payment_assessments->total_payment, 2)
                )
                : '-',
            $_data->payment_assessments ? number_format($_data->payment_assessments->total_paid_amount->sum('payment_amount'), 2) : '-',
            $_data->payment_assessments ? number_format(($_data->payment_assessments->course_semestral_fee_id ? $_data->payment_assessments->course_semestral_fee->total_payments($_data->payment_assessments) : $_data->payment_assessments->total_payment) - $_data->payment_assessments->total_paid_amount->sum('payment_amount'), 2) : '-'
            /*  $data->student->user->client_code,
            strtoupper($data->student->last_name . ", " . $data->student->first_name),
            $data->mode_payment == 1 ? 'INSTALLMENT' : "FULLPAYMENT",
            $data->student->assessment_ ? number_format($data->student->assessment_->total_enrollment_payment, 2) : '0',
            number_format($data->student->paid($data->id), 2),
            number_format($data->student->balance($data->id), 2),
            $data->student->current_enrolled->bridging_program */
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:G1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '0000000'],
                        ],
                    ]
                ]);
            }
        ];
    }
    public function title(): string
    {
        return strtoupper($this->level);
    }
}
