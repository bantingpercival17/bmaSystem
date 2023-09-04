<?php

namespace App\Exports\WorkSheet;

use App\Models\StudentDetails;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentInformationSheets implements FromCollection, ShouldAutoSize, WithMapping, WithHeadings, WithEvents, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $totalRows;
    public $countC = 0;
    public $countS = 0;
    public $level;
    public $course;
    public $cancel;
    public function __construct($_course, $_level, $cancel)
    {
        $this->course = $_course;
        $this->level = $_level;
    }
    public function collection()
    {
        $list = $this->course->enrollment_list_by_year_level_without_cancellation($this->level)->get();
        if ($this->cancel == 1) {
            $list = $this->course->enrollment_list_by_year_level($this->level)->get();
        }
        $this->totalRows = count($list);
        return $list;
    }
    public function title(): string
    {
        return strtoupper(Auth::user()->staff->convert_year_level($this->level));
    }
    public function registerEvents(): array
    {
        $collegeStyle = [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->mergeCells('C1:F1');
                $event->sheet->mergeCells('G1:J1');
                $event->sheet->mergeCells('K1:M1');
                $event->sheet->mergeCells('K1:M1');
                $event->sheet->mergeCells('N1:P1');
                $event->sheet->mergeCells('R1:U1');
                $event->sheet->mergeCells('A1:A2');
                $event->sheet->mergeCells('B1:B2');
                $event->sheet->mergeCells('Q1:Q2');
                $event->sheet->mergeCells('V1:V2');
                $event->sheet->mergeCells('W1:W2');
                $event->sheet->getStyle('A1:W2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('A1:W2')->applyFromArray([
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
                $sheets = 'A3:W' . ($this->totalRows + 2);
                $event->sheet->getStyle($sheets)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '0000000'],
                        ],
                    ]
                ]);
            }
        ];
        $shsStyle = [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:Q1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('A1:Q1')->applyFromArray([
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
                $sheets = 'A2:Q' . ($this->totalRows + 2);
                $event->sheet->getStyle($sheets)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '0000000'],
                        ],
                    ]
                ]);
            }
        ];
        return $this->course->id == 3 ? $shsStyle : $collegeStyle;
    }
    public function headings(): array
    {
        $collegeHeader = [
            ["NO", "STUDENT NUMBER", "STUDENT'S NAME", "", "", "", "STUDENT'S PROFILE", "", "", "",  "FATHER'S NAME", "", "",  "MOTHER'S MAIDEN NAME", "", "",  "HOUSEHOLD PER CAPITAL INCOME", "PERMANENT ADDRESS", "", "", "", "CONTACT NUMBER", "EMAIL ADDRESS"],
            ['', '', 'LAST NAME', 'GIVEN NAME', 'EXT. NAME', 'MIDDLE NAME', 'GENDER', 'BIRTHDAY', 'COMPLETE PROGRAM NAME', 'YEAR LEVEL', 'LAST NAME', 'GIVEN NAME', 'MIDDLE NAME', 'LAST NAME', 'GIVEN NAME', 'MIDDLE NAME', '', 'STREET & BARANGAY', 'TOWN/CITY/MUN', 'PROVINCE', 'ZIPCODE']
        ];
        $shsHeader = [
            'NO', 'STUDENT NUMBER', 'LRN', 'NAME (LAST NAME, FIRST NAME, MIDDLE NAME)', 'BIRTHDAY', 'RELIGION', 'ADDRESS', "FATHER'S NAME", " MOTHER'S MAIDEN NAME", "CONTACT NUMBER OF PARENT OR GUARDIAN", "JHS GRADUATED", "YR GRAD", "GWA", "SCHOOL ADDRESS", "PUBLIC", "PRIVATE", "REMARKS"
        ];
        return $this->course->id == 3 ? $shsHeader : $collegeHeader;
    }

    public function map($_data): array
    {
        $extension =  strtolower($_data->student->extention_name) != 'n/a' ? $_data->student->extention_name : '';
        $collegeData = [
            $this->countC += 1,
            $_data->student->account ? $_data->student->account->student_number : '-',
            $_data->student->last_name,
            $_data->student->first_name,
            $extension,
            $_data->student->middle_name,
            $_data->student->sex,
            $_data->student->birthday,
            $_data->student->enrollment_assessment->course->course_name,
            Auth::user()->staff->convert_year_level($_data->student->enrollment_assessment->year_level),
            $_data->student->parent_details ? $_data->student->parent_details->father_last_name : '',
            $_data->student->parent_details ? $_data->student->parent_details->father_first_name : '',
            $_data->student->parent_details ? $_data->student->parent_details->father_middle_name : '',
            $_data->student->parent_details ? $_data->student->parent_details->mother_last_name : '',
            $_data->student->parent_details ? $_data->student->parent_details->mother_first_name : '',
            $_data->student->parent_details ? $_data->student->parent_details->mother_middle_name : '',
            $_data->student->parent_details ? $_data->student->parent_details->household_income : '',
            $_data->student->street . ' ' . $_data->student->barangay,
            $_data->student->municipality,
            $_data->student->province,
            $_data->student->zip_code,
            $_data->student->contact_number,
            $_data->student->account ? $_data->student->account->personal_email : '-'
        ];
        $shsData = [
            $this->countS += 1,
            $_data->student->account ? $_data->student->account->student_number : '-',
            '',
            $_data->student->last_name . ', ' . $_data->student->first_name . ' ' . $_data->student->middle_name,
            $_data->student->birthday,
            $_data->student->religion,
            $_data->student->street . ' ' . $_data->student->barangay . ' ' . $_data->student->municipality . ' ' . $_data->student->province,
            /* $_data->student->zip_code, */
            $_data->student->parent_details ? $_data->student->parent_details->father_last_name . ' ' . $_data->student->parent_details->father_first_name  . ' ' .  $_data->student->parent_details->father_middle_name : '',
            $_data->student->parent_details ? $_data->student->parent_details->mother_last_name . ' ' . $_data->student->parent_details->mother_first_name  . ' ' .  $_data->student->parent_details->mother_middle_name : '',
            $_data->student->parent_details ? $_data->student->parent_details->mother_contact_number : '',

            /* $_data->student->contact_number,
            $_data->student->account ? $_data->student->account->personal_email : '-' */
        ];
        $data = $this->course->id == 3 ? $shsData : $collegeData;
        return $data;
    }
}
