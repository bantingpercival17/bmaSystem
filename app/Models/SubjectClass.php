<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectClass extends Model
{
    use HasFactory;
    protected $fillable = ['staff_id', 'curriculum_subject_id', 'academic_id', 'section_id', 'created_by', 'is_removed'];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
    public function academic()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_id');
    }
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
    public function curriculum_subject()
    {
        return $this->belongsTo(CurriculumSubject::class, 'curriculum_subject_id');
    }
    public function curriculum_subjects()
    {
        return $this->belongsTo(CurriculumSubject::class, 'curriculum_subject_id')->with('subject');
    }
    public function submitted_grade($_form, $_period)
    {
        //return $this->hasOne(GradeSubmission::class,'subject_class_id')->latest();
        return $this->hasOne(GradeSubmission::class, 'subject_class_id')->where('form', $_form)->where('period', $_period)->latest()->first();
    }
    public function midterm_grade_submission()
    {
        return $this->hasOne(GradeSubmission::class, 'subject_class_id')
            ->where('form', 'ad1')
            ->where('period', 'midterm')->latest();
    }
    public function midterm_grade_remarks()
    {
        return $this->hasMany(GradeSubmission::class, 'subject_class_id')
            ->where('form', 'ad1')
            ->where('period', 'midterm')
            ->orderBy('created_at', 'asc');
    }
    public function finals_grade_submission()
    {
        return $this->hasOne(GradeSubmission::class, 'subject_class_id')
            ->where('form', 'ad1')
            ->where('period', 'finals')->latest();
    }
    public function finals_grade_remarks()
    {
        return $this->hasMany(GradeSubmission::class, 'subject_class_id')
            ->where('form', 'ad1')
            ->where('period', 'finals')
            ->orderBy('created_at', 'asc');
    }
    public function grade_submission()
    {
        return $this->hasOne(GradeSubmission::class, 'subject_class_id')->latest();
    }
    public function grade_final_verification()
    {
        return $this->hasOne(GradeVerification::class, 'subject_class_id')->where('is_removed',false)/* ->where('is_approved',true) */->latest();
    }
    public function student_computed_grade($student)
    {
        return $this->hasOne(GradeComputed::class, 'subject_class_id')->where('removed_at', false)->where('student_id', $student);
    }
    public function student_semestral_subject_grade()
    {
        return  $this->hasOne(GradeComputed::class, 'subject_class_id')->where('removed_at', false)->where('student_id', auth()->user()->student_id);
        return auth()->user()->student->percentage_grade(base64_encode($data->final_grade));

    }
    public function e_clearance()
    {
        return $this->hasOne(StudentClearance::class, 'subject_class_id')->where('student_id', base64_decode(request()->input('_student')))->where('is_removed', false);
    }
    public function class_schedule()
    {
        return $this->hasMany(SubjectClassSchedule::class, 'subject_class_id')->where('is_removed', false);
    }
    public function course_syllabus()
    {
        return $this->hasOne(SubjectClassCourseSyllabus::class, 'subject_id')->where('is_removed', false);
    }

    public function grade_publish()
    {
        return GradeComputed::where('subject_class_id', $this->id)->get();
    }
}
