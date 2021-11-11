<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AcademicYear extends Model
{
    use HasFactory;
    protected $fillable = ['school_year', 'semester', 'is_active', 'created_by', 'is_removed'];

    public function teacher_subjects()
    {
        $_staff = Auth::user()->staff;
        return $this->hasMany(SubjectClass::class, 'academic_id')->where('staff_id',$_staff->id);
    }
}
