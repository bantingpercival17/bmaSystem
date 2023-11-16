<?php

namespace App\Exports;

use Dompdf\Css\Color;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SectionStudentList implements FromCollection, ShouldAutoSize, WithMapping, WithHeadings, WithEvents, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $section;
    public $curriculum;
    public function __construct($_section)
    {
        $this->section = $_section;
        $this->curriculum = $this->section->curriculum_subject_class;
    }
    public function collection()
    {
        $_student = $this->section->student_sections;
        return $_student;
    }
    public function headings(): array
    {
        $_fields = array(0 => 'STUDENT NUMBER', 1 => 'EMAIL', 2 => 'LAST NAME', 3 => 'FIRST NAME', 4 => 'MIDDLE NAME');
        return [
            'STUDENT NUMBER',
            'EMAIL',
            'LAST NAME',
            'FIRST NAME',
            'MIDDLE NAME',
            'EXTENSION NAME',
            'GUARDIAN NAME',
            'GUARDIAN CONTACT NUMBER',
            'GUARDIAN ADDRESS',
            'PICTURE',
            'SIGNATURE',
            'QR-CODE',
        ];
    }
    public function map($_data): array
    {
        $parents = $_data->student->parent_details;
        $guardianName = $parents ? $parents->guardian_first_name . ' ' . $parents->guardian_last_name : '';
        $guardianContactNumber = $parents ? $parents->guardian_contact_number : '';
        $guardianAddress = $parents ? $parents->guardian_address : '';
        return [
            $_data->student->account ? $_data->student->account->student_number : '',
            $_data->student->account ?  $_data->student->account->email : "-",
            $_data->student->last_name,
            $_data->student->first_name,
            $_data->student->middle_initial,
            $_data->student->extention_name,
            $guardianName,
            $guardianContactNumber,
            $guardianAddress,
            '',
            '',
            ''

        ];
    }
    public function registerEvents(): array
    {

        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cells = 'A1:K1';
                $event->sheet->getStyle($cells)->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);
                $event->sheet->getStyle($cells)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $event->sheet->getStyle($cells)->getFill()->getStartColor()->setARGB('18995B'); // Hex color code
                $event->sheet->getStyle('A1:K1')->applyFromArray([
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
