<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentGadget extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'gadget_type',
        'gadget_brand',
        'gadget_serial',
        'is_removed'
    ];
    public function student()
    {
        return $this->belongsTo(StudentDetails::class, 'student_id')->with(['account','enrollment_assessment']);
    }
}
