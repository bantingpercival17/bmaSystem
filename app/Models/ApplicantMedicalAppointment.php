<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantMedicalAppointment extends Model
{
    use HasFactory;
    protected $fillable = [
        'applicant_id',
        'appointment_date',
        'approved_by',
        'is_approved'
    ];
    protected $connection = 'mysql2';
    public function account()
    {
        return $this->belongsTo(ApplicantAccount::class, 'applicant_id');
    }
}
