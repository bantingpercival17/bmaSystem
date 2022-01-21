<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrollmentApplication extends Model
{
    use HasFactory;
    protected $fillable = ['student_id', 'academic_id', 'enrollment_place', 'staff_id', 'is_approved', 'is_removed'];
}
