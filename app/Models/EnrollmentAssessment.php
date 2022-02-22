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
    public function student()
    {
        return $this->belongsTo(StudentDetails::class, 'student_id');
    }
    public function payment_assessments()
    {
        return $this->hasOne(PaymentAssessment::class, 'enrollment_id');
    }
    public function academic()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_id');
    }
    public function year_and_section($_data)
    {
        $_year_level = $_data->year_level == '11' ? 'Grade 11' : '-';
        $_year_level = $_data->year_level == '12' ? 'Grade 12' : $_year_level;
        $_year_level = $_data->year_level == '4' ? '4th Class' : $_year_level;
        $_year_level = $_data->year_level == '3' ? '3rd Class' : $_year_level;
        $_year_level = $_data->year_level == '2' ? '2nd Class' : $_year_level;
        $_year_level = $_data->year_level == '1' ? '1st Class' : $_year_level;
        $_student_section = $_data->student->section($_data->academic_id)->first();
        $_section = $_student_section ? $_student_section->section->section_name : '-';
        return $_year_level . " | " . $_section;
    }
    public function course_subjects($_assessment)
    {
        return CurriculumSubject::where('course_id', $_assessment->course_id)
            ->where('curriculum_id', $_assessment->curriculum_id)
            ->where('year_level', $_assessment->year_level)
            ->where('semester', $_assessment->academic->semester)
            ->get();
    }
    public function course_semestral_fees($_data)
    {
        return CourseSemestralFees::where([
            'course_id' => $_data->course_id,
            'curriculum_id' => $_data->curriculum_id,
            'academic_id' => $_data->academic_id,
            'year_level' => $_data->year_level,
            'is_removed' => false
        ])->first();
    }
    public function payment_transactions()
    {
        return $this->hasOne(PaymentTransaction::class, 'assessment_id')->where('remarks', 'Upon Enrollment');
    }
    public function enrollment_payment_assessment()
    {
        return $this->hasOne(PaymentAssessment::class, 'enrollment_id')->where('is_removed', false)/* ->with('payment_assessment_paid') */;
    }
}
