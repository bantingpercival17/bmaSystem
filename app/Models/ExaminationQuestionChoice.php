<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExaminationQuestionChoice extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $fillable = [
        'question_id',
        'choice_name',
        'image_path',
        'is_answer'
    ];
}
