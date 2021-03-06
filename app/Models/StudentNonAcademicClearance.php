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
        'academic_id',
        'staff_id',
        'is_approved', // nullable
        'is_removed'
    ];
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
