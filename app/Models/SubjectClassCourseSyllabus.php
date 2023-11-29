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
    function syllabus_details(){
      return $this->belongsTo(CourseSyllabus::class,'course_syllabus_id')->select('id','subject_id','course_id','course_description')->with('learning_outcomes');
    }
}
