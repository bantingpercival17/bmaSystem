<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentScholarshipGrant extends Model
{
    use HasFactory;
    protected $fillable = ['student_id', 'voucher_id', 'staff_id'];
    function student()
    {
        return $this->belongsTo(StudentDetails::class, 'student_id');
    }
}
