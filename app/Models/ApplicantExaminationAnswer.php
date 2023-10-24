<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantExaminationAnswer extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $fillable = [
        'examination_id',
        'question_id',
        'choices_id'
    ];
}
