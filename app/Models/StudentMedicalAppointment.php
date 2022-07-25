<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentMedicalAppointment extends Model
{
    use HasFactory;


    public function student()
    {
        return $this->belongsTo(StudentDetails::class, 'student_id');
    }
}
