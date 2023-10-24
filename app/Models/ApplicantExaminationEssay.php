<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantExaminationEssay extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $fillable = [
        'examination_id',
        'essay_answer'
    ];
}
