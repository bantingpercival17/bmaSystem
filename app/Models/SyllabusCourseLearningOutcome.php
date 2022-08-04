<?php

namespace App\Models;

use CreateSyllabusCourseLearningOutcomesTable;
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
    public function materials()
    {
        return $this->hasOne(SyllabusCourseLearningTopicMaterials::class, 'topic_id')->where('is_removed', false);
    }
    public function sub_topics()
    {
        return $this->hasMany(SyllabusCourseSubTopicLearningOutcome::class, 'topic_id')->where('is_removed', false);
    }
}
