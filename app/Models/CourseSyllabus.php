<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSyllabus extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject_id',
        'course_id',
        'course_description',
        'prerequisite',
        'co_requisite',
        'semester',
        'creator_id'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id')->where('is_removed', false);
    }
}
