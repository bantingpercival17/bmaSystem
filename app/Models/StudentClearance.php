<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentClearance extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'subject_class_id',
        'comments', // nullable
        'staff_id',
        'is_approved', // nullable
        'is_removed'
    ];
}
