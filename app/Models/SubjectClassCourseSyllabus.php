<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectClassCourseSyllabus extends Model
{
    use HasFactory;
    protected $fillable = ['subject_id', 'course_syllabus_id'];

    public function syllabus()
    {
      return $this->belongsTo(CourseSyllabus::class,'course_syllabus_id');
    }
}
