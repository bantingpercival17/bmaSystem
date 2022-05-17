<?php

namespace App\Exports;

use App\Models\Staff;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class SalaryDetailsTemplate implements FromCollection, ShouldAutoSize, WithMapping, WithHeadings, WithEvents, WithTitle
{
    public function collection()
    {

        return Staff::orderBy('staff.last_name', 'asc')->where('is_removed', false)->get();
    }
    public function headings(): array
    {
        return [
            'ID',
            "EMPLOYEE'S NAME",
            'BASIC SALARY',
            'ALLOWANCE SALARY',
            'SSS',
            'PHILHEALTH',
            'PAG-IBIG',

        ];
    }
    public function map($_data): array
    {
        return [
            $_data->id,
            strtoupper($_data->last_name . ', ' . trim(str_replace(['2/m', 'C/e', '2/o', '3/e', 'Engr.', 'Capt.', 'C/m'], '', $_data->first_name))),

        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:H1')->applyFromArray([
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
        return strtoupper("EMPLOYEE-DETAILS");
    }
}
