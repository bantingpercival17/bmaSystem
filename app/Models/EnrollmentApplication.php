<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrollmentApplication extends Model
{
    use HasFactory;
    protected $fillable = ['student_id', 'academic_id', 'enrollment_place', 'staff_id', 'is_approved', 'course_id', 'strand', 'enrollment_category', 'is_removed'];

    public function course()
    {
        return $this->belongsTo(CourseOffer::class, 'course_id');
    }
    public function academic()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_id');
    }
    public function color_course()
    {
        $_course_color = $this->course_id == 1 ? 'bg-info' : '';
        $_course_color = $this->course_id == 2 ? 'bg-primary' : $_course_color;
        $_course_color = $this->course_id == 3 ? 'bg-warning text-white' : $_course_color;
        return $_course_color;
    }
}
