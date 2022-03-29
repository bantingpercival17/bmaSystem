<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AcademicYear extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $fillable = ['school_year', 'semester', 'is_active', 'created_by', 'is_removed'];

    public function teacher_subjects()
    {
        $_staff = Auth::user()->staff;
        return $this->hasMany(SubjectClass::class, 'academic_id')->where('staff_id', $_staff->id);
    }

    public function enrollment_list()
    {
        return $this->hasMany(EnrollmentAssessment::class, 'academic_id')
            ->select('enrollment_assessments.student_id', 'enrollment_assessments.created_at', 'enrollment_assessments.course_id')
            ->join('payment_assessments as pa', 'pa.enrollment_id', 'enrollment_assessments.id')
            ->join('payment_transactions as pt', 'pt.assessment_id', 'pa.id')
            ->where('pt.remarks', 'Upon Enrollment')
            ->where('pt.is_removed', false)
            ->groupBy('pt.assessment_id')
            ->orderBy('enrollment_assessments.created_at', 'DESC')
            /* ->where('enrollment_assessments.year_level', $_data) */
            ->where('enrollment_assessments.is_removed', false);
    }
}
