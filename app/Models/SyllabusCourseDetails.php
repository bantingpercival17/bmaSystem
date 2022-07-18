<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusCourseDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_syllabus_id',
        'course_intake_limitations',
        'faculty_requirements',
        'teaching_facilities_and_equipment',
        'teaching_aids',
        'references'
    ];
}
