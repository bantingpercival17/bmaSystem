<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;
    protected $fillable = [
        'section_name', 'academic_id', 'course_id', 'year_level', 'created_by', 'is_removed'
    ];
    public function student_section()
    {
        return $this->hasMany(StudentSection::class, 'section_id')->join('student_accounts as sa', 'sa.student_id', 'student_sections.student_id')->orderBy('sa.student_number', 'asc');
    }
}
