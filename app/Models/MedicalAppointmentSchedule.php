<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalAppointmentSchedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'capacity',
        'is_close'
    ];
    public function number_of_applicant()
    {
        return ApplicantMedicalAppointment::where('appointment_date', $this->date)->where('is_approved', true)->count();
    }
}
