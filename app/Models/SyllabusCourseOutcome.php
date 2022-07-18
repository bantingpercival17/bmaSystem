<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusCourseOutcome extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_syllabus_id',
        'course_outcome',
        'program_outcome'
    ];
}
