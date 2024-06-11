<?php

namespace App\Exports\WorkSheet;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class MedicalMonitoringSheets implements FromCollection, ShouldAutoSize,  WithMapping,  WithHeadings, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }
    public function collection()
    {
        return $this->data['value'];
    }
    public function headings(): array
    {
        return [
            'APPLICANT NUMBER',
            'APPLICANT NAME',
            'COURSE',
            'CONTACT NUMBER',
            'MEDICAL SCHEDULED',
            'REMARKS',
        ];
    }
    public function map($data): array
    {
        if ($data->medical_result) {
            if ($data->medical_result->is_fit === 1) {
                $medicalRemarks = "FIT TO ENROLL";
            } elseif ($data->medical_result->is_fit === 2) {
                $medicalRemarks = "NOT FIT TO ENROLL DUE TO " . $data->medical_result->remarks;
            } else {
                $medicalRemarks = 'PENDING DUE TO ' . $data->medical_result->remarks;
            }
        } else {
            $medicalRemarks = '';
        }
        $dataList = array(
            $data->applicant_number,
            $data->applicant ? $data->applicant->last_name . ', ' . $data->applicant->first_name : $data->email,
            $data->course->course_name,
            $data->contact_number,
            $data->medical_appointment ? $data->medical_appointment->appointment_date : '',
            $medicalRemarks
        );
        return $dataList;
    }
    public function title(): string
    {
        return strtoupper($this->data['name']);
    }
}
