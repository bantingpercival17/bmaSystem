<?php

namespace App\Exports;

use App\Models\ApplicantAccount;
use App\Models\ApplicantDetials;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class ApplicantMedicalSchedule implements FromCollection, WithHeadings, WithTitle, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return ApplicantAccount::select('applicant_accounts.*')->join('applicant_detials', 'applicant_accounts.id', 'applicant_detials.applicant_id')
            ->join('applicant_medical_appointments as ama', 'ama.applicant_id', 'applicant_accounts.id')
            ->where('applicant_accounts.is_removed', false)->where('ama.is_removed', false)/* ->where('ama.is_approved', false) */
            ->orderBy('ama.appointment_date','desc')->groupBy('applicant_accounts.id')->get();
       
    }
    public function headings(): array

    {
        return [
            'FULL NAME',
            'CONTACT NUMBER',
            'APPOINTMENT DATE'
        ];
    }
    public function title(): string
    {
        return strtoupper("appointment");
    }
    public function map($_data): array
    {
        return [
            $_data->applicant->last_name . ', ' . $_data->applicant->first_name . ' ' . $_data->applicant->middle_name[0] . ". ",
            $_data->contact_number,
            $_data->medical_appointment->appointment_date 
        ];
    }
}
