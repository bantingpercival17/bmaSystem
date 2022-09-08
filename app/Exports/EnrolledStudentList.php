<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class EnrolledStudentList implements FromCollection, ShouldAutoSize, WithMapping, WithHeadings, WithEvents, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    /*  public function collection()
    {
        //
    } */
    public function __construct($_course)
    {
        $this->course = $_course;
    }
    public function collection()
    {

        $_student = $this->course->enrollment_list;
        return $_student;
    }
    public function headings(): array
    {
        return [
            'STUDENT NUMBER',
            'LAST NAME',
            'FIRST NAME',
            'MIDDLE NAME',
            'YEAR LEVEL',
            'COURSE',
            'SECTION',

        ];
    }
    public function map($_data): array
    {
        return [
            $_data->student->account ? $_data->student->account->student_number : '',
            $_data->student->last_name,
            $_data->student->first_name,
            $_data->student->middle_name,
            $_data->student->enrollment_assessment->year_level,
            $_data->student->enrollment_assessment->course->course_name,
            $_data->student->section->section_name,
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
        return strtoupper("Enrollment List");
    }
}
