<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradePublish extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'academic_id',
        'staff_id',
        'is_removed',
    ];
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
