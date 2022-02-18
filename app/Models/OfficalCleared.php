<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficalCleared extends Model
{
    use HasFactory;
    protected $fillable = ['student_id', 'academic_id', 'course_id', 'is_cleared', 'is_removed'];
    public function student()
    {
        return $this->belongsTo(StudentDetails::class, 'student_id');
    }
}
