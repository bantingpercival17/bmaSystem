<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class StudentAccount extends Authenticatable
{
    use HasApiTokens, HasFactory;
    protected $fillable = [
        'student_id',
        'email',
        'personal_email',
        'student_number',
        'password',
        'is_actived',
        'is_removed',
    ];
    protected $hidden = [
        'password',
    ];
    public function student()
    {
        return $this->belongsTo(StudentDetails::class, 'student_id')->with('enrollment_assessment');
    }
}
