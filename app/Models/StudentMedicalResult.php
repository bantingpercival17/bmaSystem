<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentMedicalResult extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'is_fit',
        'is_pending',
        'image_path',
        'remarks',
        'staff_id',

    ];
}
