<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StudentMedicalList implements FromCollection,  WithMapping, WithHeadings, WithEvents, WithTitle, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($_data, $_name)
    {
        $this->data = $_data;
        $this->name = $_name;
    }
    public function collection()
    {
        return $this->data;
    }
    public function map($_data): array
    {

        $_medical_status = '';
        if ($_data->student->student_medical_appointment) {
            if ($_data->student->student_medical_appointment->is_approved == true) {
                if ($_data->student->student_medical_result) {
                    if ($_data->student->student_medical_result->is_fit !== null)
                        if ($_data->student->student_medical_result->is_fit === 1)
                            $_medical_status = 'FIT TO ENROLL';
                        else
                            $_medical_status = 'FAILED';
                    else
                        $_medical_status = 'PENDING RESULT: ' . $_data->student->student_medial_result->remarks;
                } else {
                    $_medical_status = 'Waiting for Result';
                }
            } else {
                $_medical_status = 'Wait for Schedule Approval';
            }
            # code...
        } else {
            $_medical_status = 'No Medical Appointment';
        }
        return [
            $_data->student->student_medical_appointment ? $_data->student->student_medical_appointment->appointment_date : 'NO APPOINTMENT',
            $_data->student->last_name,
            $_data->student->first_name,
            $_data->student->middle_name,
            $_medical_status,

        ];
    }
    public function headings(): array
    {
        return [
            'MEDICAL APPOITMENT DATE',
            'LAST NAME',
            'FIRST NAME',
            'MIDDLE NAME',
            'MEDICAL STATUS'
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:E1')->applyFromArray([
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
        return strtoupper($this->name);
    }
}
