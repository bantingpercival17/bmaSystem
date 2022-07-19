<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusCourseLearningOutcome extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_syllabus_id',
        'course_outcome_id',
        'learning_outcomes',
        'theoretical',
        'demonstration',
        'weeks',
        'reference',
        'teaching_aids',
        'term'
    ];
    public function course_outcome()
    {
        return $this->belongsTo(SyllabusCourseOutcome::class, 'course_outcome_id');
    }
}
