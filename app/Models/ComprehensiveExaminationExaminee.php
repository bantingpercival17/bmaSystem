<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprehensiveExaminationExaminee extends Model
{
    use HasFactory;
    protected $fillable = ['student_id', 'approved_staff_id'];
    function student()
    {
        return $this->belongsTo(StudentDetails::class, 'student_id');
    }
    function examination_scheduled()
    {
        return $this->hasOne(ComprehensiveExaminationScheduled::class, 'examinee_id')->orderBy('id', 'desc')->where('is_removed', false);
    }
}
