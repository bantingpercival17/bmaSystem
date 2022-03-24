<?php

namespace App\Exports;

use App\Models\CourseOffer;
use App\Models\StudentDetails;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class YearLevelStudentEnrolled  implements FromCollection, ShouldAutoSize, WithMapping,/*  WithHeadings, */ WithEvents, WithTitle
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
        return $this->course->enrolled_list($this->level)->get();
    }
    /*  public function headings(): array
    {
        return [
            'STUDENT NUMBER',
            'LAST NAME',
            'FIRST NAME',
            'MIDDLE NAME',
        ];
    } */
    public function map($_data): array
    {
        /* return $_data; */
        return [
            '123456789', //$_data->account->student_number,
            $_data->last_name,
            $_data->first_name,
            $_data->middle_name,
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
        $_level = $this->course->id == 3 ? 'Grade' . $this->level : $this->level . '/c';
        return strtoupper($_level);
    }
}
