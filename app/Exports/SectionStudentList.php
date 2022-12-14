<?php

namespace App\Exports;

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
        ];
    }
    public function map($_data): array
    {
        if ($_data->student->account) {
            $_student_number = $_data->student->account->student_number;
            $image = QrCode::format('png')
               // ->merge('img/t.jpg', 0.1, true)
                ->size(200)->errorCorrection('H')
                ->generate($_student_number . "." . mb_strtolower(str_replace(' ', '', $_data->student->last_name)));
            $output_file = '/student/qr-code/' + $this->section->section_name + '/' . $_student_number . '.png';
            Storage::disk('local')->put($output_file, $image); //storage/app/public/img/qr-code/img-1557309130.png 
        }

        return [
            $_data->student->account ? $_data->student->account->student_number : '',
            $_data->student->account ?  $_data->student->account->campus_email : "-",
            $_data->student->last_name,
            $_data->student->first_name,
            $_data->student->middle_name,
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
