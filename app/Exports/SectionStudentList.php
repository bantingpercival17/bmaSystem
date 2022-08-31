<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class SectionStudentList implements FromCollection, ShouldAutoSize, WithMapping, WithHeadings, WithEvents, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($_section)
    {
        $this->section = $_section;
    }
    public function collection()
    {

        $_student = $this->section->student_sections;
        return $_student;
    }
    public function headings(): array
    {
        return [
            'STUDENT NUMBER',
            'LAST NAME',
            'FIRST NAME',
            'MIDDLE NAME',
            'EMAIL',

        ];
    }
    public function map($_data): array
    {
        /* return $_data; */
        return [
            $_data->student->account ? $_data->student->account->student_number : '',
            $_data->student->last_name,
            $_data->student->first_name,
            $_data->student->middle_name,
            $_data->student->account ?  $_data->student->account->campus_email : "-",
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:Z1')->applyFromArray([
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
        return strtoupper($this->section->section_name);
    }
}
