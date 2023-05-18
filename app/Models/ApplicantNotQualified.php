<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantNotQualified extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $fillable = ['applicant_id', 'course_id', 'academic_id', 'staff_id',];

}
