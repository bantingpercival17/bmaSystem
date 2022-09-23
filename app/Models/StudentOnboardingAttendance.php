<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentOnboardingAttendance extends Model
{
    use HasFactory;
    protected $fillable =  [
        'student_id', 'course_id', 'academic_id', 'time_in', 'time_in_status', 'time_in_remarks', 'time_in_process_by', 'time_out', 'time_out_status', 'time_out_remarks', 'time_out_process_by',
    ];
    public function student()
    {
        return $this->belongsTo(StudentDetails::class, 'student_id');
    }
    public function academic()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_id');
    }
    public function course()
    {
        return $this->belongsTo(CourseOffer::class, 'course_id');
    }
}
