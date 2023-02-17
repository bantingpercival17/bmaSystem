<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeComputed extends Model
{
    use HasFactory;
    protected $fillable = ['subject_class_id', 'student_id', 'midterm_grade', 'final_grade', 'removed_at'];

    public function subject_class()
    {
        return $this->belongsTo(SubjectClass::class, 'subject_class_id')->where('is_removed', false);
    }
    public function student()
    {
        return $this->belongsTo(StudentDetails::class, 'student_id');
    }
}
