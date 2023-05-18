<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantBriefingSchedule extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $fillable = [
        'applicant_id',
        'staff_id',
        'schedule_date',
        'schedule_time',
        'category',
        'is_removed',
    ];
}
