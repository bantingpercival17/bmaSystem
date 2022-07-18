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
        'theoretical',
        'demonstration',
        'weeks',
        'references',
        'teaching_aids',
        'term'
    ];
}
