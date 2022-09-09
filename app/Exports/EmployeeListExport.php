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

class EmployeeListExport implements FromCollection, ShouldAutoSize, WithMapping, WithHeadings, WithEvents, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Staff::all();
    }
    public function headings(): array
    {
        return [
            'ID no',
            'LAST NAME',
            'FIRST NAME',
            'MIDDLE NAME',
            'EMAIL',
            'MARITME DEPT',
        ];
    }
    public function map($_data): array
    {
        return [
            $_data->id,
            $_data->last_name,
            $_data->first_name,
            $_data->middle_name,
            $_data->user->email,
            $_data->department,
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:D1')->applyFromArray([
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
        return 'Empolyee';
    }
}
