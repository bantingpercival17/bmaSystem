<?php

namespace App\Exports\WorkSheet;

use App\Models\AcademicYear;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class TeachingLoadPerLevel implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithTitle, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $section;
    public $data;
    public function __construct($section, $data)
    {
        $this->section = $section;
        $this->data = $data;
    }
    public function title(): string
    {
        return strtoupper($this->section->section_name);
    }
    public function headings(): array
    {
        return [
            'ACADEMIC CODE',
            'SUBJECT CODE',
            'SECTION CODE',
            'SUBJECT',
            'SUBJECT DESCRIPTION',
            'TEACHER EMAIL',
            'TEACHER NAME',
            'MONDAY', 'TUESDAY', 'WENESDAY', 'THURSDAY', 'FRIDAY'
        ];
    }
    public function collection()
    {
        $academic = AcademicYear::find($this->data->academic_id);
        $data = array(
            $this->data->curriculum_id,
            $this->data->year_level,
            $academic->semester
        );
        return $this->data->course->course_subject($data);
    }
    public function map($data): array
    {
        $dataList = [];
        if ($data) {
            $_subject_handle = $this->section->subject_handle($data->id);
            $dataList = array(
                base64_encode($this->section->academic->id),
                base64_encode($data->id),
                base64_encode($this->section->id),
                $data->subject_code,
                $data->subject_name,
                $_subject_handle ? $_subject_handle->staff->user->email : '',
                $_subject_handle ? $_subject_handle->staff->first_name . ' ' . $_subject_handle->staff->last_name : '',
                //$this->section->subject_handle($data->id),
            );
        }
        return $dataList;
    }
    public function registerEvents(): array
    {
        $style = [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:L1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('A1:L1')->applyFromArray([
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
                $event->sheet->getColumnDimension('A')->setVisible(false);
                $event->sheet->getColumnDimension('B')->setVisible(false);
                $event->sheet->getColumnDimension('C')->setVisible(false);
                // Get the active sheet
                /*   $sheet = $event->sheet->getActiveSheet();

                // Specify the column index you want to hide (0-based index)
                $columnIndexToHide = 1; // For example, hiding the second column

                // Hide the column
                $sheet->getColumnDimensionByColumn(0)->setVisible(false);
                $sheet->getColumnDimensionByColumn(1)->setVisible(false);
                $sheet->getColumnDimensionByColumn(2)->setVisible(false); */
                /*  $sheets = 'A2:Q' . ($this->totalRows + 2);
                $event->sheet->getStyle($sheets)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '0000000'],
                        ],
                    ]
                ]); */
            }
        ];
        return $style;
    }
}
