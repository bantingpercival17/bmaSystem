<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantExaminationSchedule extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $fillable = [
        'examination_id',
        'applicant_id',
        'schedule_date'
    ];
}
