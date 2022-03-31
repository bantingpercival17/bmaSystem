<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    use HasFactory;
    protected $fillable = ['student_id', 'description', 'time_in', 'time_out'];
    public function student()
    {
        return $this->belongsTo(StudentDetails::class, 'student_id');
    }
}
