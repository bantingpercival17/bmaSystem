<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;
    protected $fillable = [
        'section_name', 'academic_id', 'course_id', 'year_level', 'created_by', 'is_removed'
    ];
    public function student_section()
    {
        return $this->hasMany(StudentSection::class, 'section_id')
            ->join('student_accounts as sa', 'sa.student_id', 'student_sections.student_id')
            ->where('student_sections.is_removed', false)->orderBy('sa.student_number', 'asc');
    }
    public function student_sections()
    {
        return $this->hasMany(StudentSection::class, 'section_id')
            ->select('student_sections.student_id', 'student_details.first_name', 'student_details.last_name')
            //->select('student_details.*')
            //->select('student_sections.student_id', 'student_details.first_name', 'student_details.last_name')
            ->join('student_details', 'student_details.id', 'student_sections.student_id')
            ->where('student_sections.is_removed', false)
            ->orderBy('student_details.last_name', 'asc')->orderBy('student_details.first_name', 'asc');
    }
    public function student_with_bdg_sections()
    {
        return $this->hasMany(StudentSection::class, 'section_id')
            ->select('student_details.*', 'student_sections.id as student_section_id')
            ->join('student_details', 'student_details.id', 'student_sections.student_id')
            /*  ->select('student_sections.student_id', 'sa.first_name', 'sa.last_name')
            ->join('student_details as sa', 'sa.id', 'student_sections.student_id') */
            ->join('enrollment_assessments as ea', 'ea.student_id', 'student_details.id')
            ->where('ea.academic_id', $this->academic_id)
            ->where('ea.bridging_program', 'with')
            ->where('student_sections.is_removed', false)
            ->orderBy('student_details.last_name', 'asc')->orderBy('student_details.first_name', 'asc');
    }
    public function academic()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_id');
    }
    public function subject_class()
    {
        return $this->hasMany(SubjectClass::class, 'section_id')->where('is_removed', false);
    }
    public function course()
    {
        return $this->belongsTo(CourseOffer::class, 'course_id');
    }
    public function student()
    {
        return $this->belongsTo(StudentDetails::class, 'student_id');
    }
    public function curriculum_subject_class()
    {
        return $this->hasOne(SubjectClass::class, 'section_id')/* ->where('curriculum_subject_id', $_data->id) */;
    }
}
