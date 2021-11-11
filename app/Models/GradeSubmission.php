<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeSubmission extends Model
{
    use HasFactory;
    protected $fillable = ['subject_class_id', 'form', 'period', 'is_approved', 'comments', 'approved_by'];

    public function subject_class()
    {
        return $this->belongsTo(SubjectClass::class, 'subject_class_id')->latest();
    }
}
