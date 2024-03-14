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
    protected $hidden = [
        'created_at',
        'updated_at',
        'is_removed'
    ];

    public function choices_v2()
    {
        return $this->belongsTo(ExaminationQuestion::class, 'question_id')->select('id','choice_name','image_path');
    }
    public function choices_with_answer()
    {
        return $this->belongsTo(ExaminationQuestion::class, 'question_id')->select('id','choice_name','image_path');
    }
}
