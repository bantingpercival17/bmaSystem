<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CurriculumSubject extends Model
{
    use HasFactory;
    protected $fillable = ['curriculum_id', 'subject_id', 'course_id', 'year_level', 'semester', 'created_by', 'is_removed'];
    public function course()
    {
        return $this->belongsTo(CourseOffer::class, 'course_id');
    }
    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class, 'curriculum_id');
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    public function section($_data)
    {
        return $this->hasMany(SubjectClass::class, 'curriculum_subject_id')
            ->where('academic_id', $_data)
            ->where('is_removed', false);
    }
    public function sectionList()
    {
        return $this->hasMany(SubjectClass::class, 'curriculum_subject_id')
            /*   ->where('academic_id', Auth::user()->staff->current_academic()->id) */
            ->where('is_removed', false);
    }
    public function subject_class()
    {
        return $this->hasMany(SubjectClass::class, 'curriculum_subject_id')->where('academic_id', Auth::user()->staff->current_academic()->id)->where('is_removed', false);
    }

    public function curriculum_subject_class($_data)
    {
        return $this->hasOne(SubjectClass::class, 'curriculum_subject_id')->select('subject_classes.*')->where('section_id', $_data)->where('is_removed', false)->first();
    }
}
