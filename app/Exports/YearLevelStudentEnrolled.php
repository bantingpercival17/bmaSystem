<?php

namespace App\Exports;

use App\Models\AcademicYear;
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

class YearLevelStudentEnrolled  implements FromCollection, ShouldAutoSize, WithMapping, WithHeadings, WithEvents, WithTitle
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
        return $this->course->enrollment_list_by_year_level($this->level)->get();
    }
    public function headings(): array
    {
        return [

            'STUDENT NUMBER',
            'LAST NAME',
            'FIRST NAME',
            'MIDDLE NAME',
            'EMAIL ACCOUNT',
            'CONTACT NUMBER',
            'MARITIME DEPARTMENT',
            'YEAR LEVEL',
            'CCI YEAR',
            'COURSE',
            'CURRICULUM',
            'ETRB',
            'SECTION',
            'Enrollment Status',
        ];
    }
    public function map($_data): array
    {
        $_academic = AcademicYear::find(5);
        $_student_section = $_data->student->section(Auth::user()->staff->current_academic()->id)->first();
        $_enrollment_status = $_data->student->enrollment_application_status($_academic)->first();
        $_status = '';
        if ($_enrollment_status) {
            if ($_enrollment_status->payment_assessments) {
                //$_status = $_enrollment_status->payment_assessments;
                if ($_enrollment_status->payment_assessments->payment_assessment_paid) {
                    //$_status= $_enrollment_status->payment_assessments;
                    $_status = 'ENROLLED';
                } else {
                    $_status = 'FOR PAYMENT';
                }
            } else {
                $_status = 'FOR PAYMENT ASSESSMENT';
            }
        } else {
            $_status = 'NOT ENROLLED';
        }

        return [

            $_data->student->account ? $_data->student->account->student_number : '-',
            $_data->student->last_name,
            $_data->student->first_name,
            $_data->student->middle_name,
            $_data->student->account ? $_data->student->account->campus_email : '-',
            $_data->student->contact_number,
            $_data->student->enrollment_assessment->course_id == 1 ? 'ENGINE' : 'DECK',
            Auth::user()->staff->convert_year_level($_data->student->enrollment_assessment->year_level),
            '2026',
            $_data->student->enrollment_assessment->course->course_code,
            'JCMMC01-22',
            'TRMF',
            $_student_section ? $_student_section->section->section_name : '-',
            $_status
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
