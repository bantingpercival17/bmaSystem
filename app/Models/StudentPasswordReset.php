<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPasswordReset extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'password_string',
        'is_status',
        'is_removed'
    ];
}
