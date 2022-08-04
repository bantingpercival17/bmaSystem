<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusCourseLearningOutcomeSubTopic extends Model
{
    use HasFactory;
    protected $fillable = [
        'sub_topic_id',
        'learning_outcome_content',
    ];
}
