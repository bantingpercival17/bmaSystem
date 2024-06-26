<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSection extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',        'section_id',   'enrollment_id',     'created_by',        'is_removed'
    ];
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
    public function student_section(){
        return $this->belongsTo(Section::class, 'section_id')->with('subject_details');
    }
    public function student()
    {
        return $this->belongsTo(StudentDetails::class, 'student_id')->where('is_removed', false);
    }
}
