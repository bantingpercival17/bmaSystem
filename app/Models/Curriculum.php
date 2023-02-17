<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Curriculum extends Model
{
    protected $table = 'curriculum';
    use HasFactory;
    protected $fillable = ['curriculum_name', 'curriculum_year', 'created_by', 'is_removed'];

    public function subject($_data)
    {
        return $this->hasMany(CurriculumSubject::class, 'curriculum_id')
            ->select('curriculum_subjects.*')
            ->join('subjects', 'subjects.id', 'curriculum_subjects.subject_id')
            ->where('curriculum_subjects.course_id', $_data[0])
            ->where('curriculum_subjects.year_level', $_data[1])
            ->where('curriculum_subjects.semester', $_data[2])
            ->where('curriculum_subjects.is_removed', false);
    }
    public function subject_lists($_data)
    {
        return $this->hasMany(CurriculumSubject::class, 'curriculum_id')
            ->select('curriculum_subjects.*')
            ->join('subjects', 'subjects.id', 'curriculum_subjects.subject_id')
            ->where('curriculum_subjects.course_id', $_data[0])
            ->where('curriculum_subjects.year_level', $_data[1])
            ->where('curriculum_subjects.semester', $_data[2])
            ->where('curriculum_subjects.is_removed', false);
    }

    public function student_enrolled()
    {
        return $this->hasMany(EnrollmentAssessment::class, 'curriculum_id')->where('is_removed', false)->where('academic_id', Auth::user()->staff->current_academic()->id)->where('year_level', request()->input('_year_level'));
    }
}
