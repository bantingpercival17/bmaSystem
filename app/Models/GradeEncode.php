<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeEncode extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'subject_class_id',
        'period',
        'type',
        'score',
        'is_removed',
    ];
}
