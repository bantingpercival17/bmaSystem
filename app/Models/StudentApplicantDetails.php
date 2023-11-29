<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentApplicantDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'applicant_id',
        'is_removed',
    ];
    function student_details()
    {
        return $this->belongsTo(StudentDetails::class, 'student_id');
    }
}
