<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprehensiveExamination extends Model
{
    use HasFactory;
    protected $fillable = ['competence_name', 'competence_code', 'file_name', 'course_id'];
}
