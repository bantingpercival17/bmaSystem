<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrollmentAssessment extends Model
{
    use HasFactory;
    protected $fillable = [
        "student_id",
        "academic_id",
        "course_id",
        "year_level",
        "curriculum_id",
        "bridging_program",
        "staff_id",
        "is_removed"
    ];
    public function course()
    {
        return $this->belongsTo(CourseOffer::class, 'course_id');
    }
    public function payment_assessments()
    {
        return $this->hasOne(PaymentAssessment::class, 'enrollment_id');
    }
    public function academic()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_id');
    }
}
