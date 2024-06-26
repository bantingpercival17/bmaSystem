<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CourseSyllabus extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject_id',
        'course_id',
        'course_description',
        'prerequisite',
        'co_requisite',
        'semester',
        'creator_id'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id')->where('is_removed', false);
    }
    public function stcw_reference()
    {
        return $this->hasMany(SyllabusStcwReference::class, 'course_syllabus_id')->where('is_removed', false);
    }
    public function course_outcome()
    {
        return $this->hasMany(SyllabusCourseOutcome::class, 'course_syllabus_id')->where('is_removed', false);
    }
    public function details()
    {
        return $this->hasOne(SyllabusCourseDetails::class, 'course_syllabus_id')->where('is_removed', false);
    }
    public function learning_outcomes()
    {
        return $this->hasMany(SyllabusCourseLearningOutcome::class, 'course_syllabus_id')->orderBy('weeks', 'asc')->where('is_removed', false);
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'creator_id');
    }
}
