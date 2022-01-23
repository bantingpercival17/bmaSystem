<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentNonAcademicClearance extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'non_academic_type',
        'comments', // nullable
        'staff_id',
        'is_approved', // nullable
        'is_removed'
    ];
}
