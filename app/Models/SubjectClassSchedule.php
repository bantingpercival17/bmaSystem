<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectClassSchedule extends Model
{
    use HasFactory;
    protected $fillable = ['subject_class_id', 'day', 'start_time', 'end_time', 'created_by', 'is_removed'];
}
