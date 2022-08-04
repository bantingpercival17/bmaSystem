<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusCourseSubTopicLearningOutcome extends Model
{
    use HasFactory;
    protected $fillable = [
        'topic_id',
        'sub_topic',
    ];


    public function learning_outcome_list()
    {
        return $this->hasMany(SyllabusCourseLearningOutcomeSubTopic::class, 'sub_topic_id')->where('is_removed',false);
    }
}
