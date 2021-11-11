<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeComputed extends Model
{
    use HasFactory;
    protected $fillable = ['subject_class_id', 'student_id', 'midterm_grade', 'final_grade', 'removed_at'];
}
