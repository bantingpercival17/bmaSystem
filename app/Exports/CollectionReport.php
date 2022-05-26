<?php

namespace App\Exports;

use App\Models\PaymentTransaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class CollectionReport implements FromCollection, ShouldAutoSize, WithMapping, WithHeadings, WithEvents, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($_date)
    {
        $this->content = $_date;
    }
    public function collection()
    {
        return PaymentTransaction::where('transaction_date', 'like', '%' . $this->content . '%')->get();
    }
    public function map($data): array
    {
        $_student = $data->payment_assessment->enrollment_assessment->student;
        return [
            date_format(date_create($data->transaction_date), "d/m/Y"),
            strtoupper($_student->last_name . ", " . $_student->first_name),
            $data->remarks,
            $data->or_number,
            number_format($data->payment_amount, 2)
        ];
    }
    public function headings(): array
    {
        return [
            'TRANSACTION DATE',
            'STUDENT NAME',
            'PARTICULARS',
            'OR NUMBER',
            'PAYMENT AMOUNT'
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:E1')->applyFromArray([
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
        return strtoupper(date_format(date_create($this->content), "M-d"));
    }
}
