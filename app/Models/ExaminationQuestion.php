<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExaminationQuestion extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $fillable = [
        'category_id',
        'question',
        'image_path',
        'score'
    ];
    public function question()
    {
        return $this->belongsTo(ExaminationCategory::class, 'category_id');
    }
    public function choices()
    {
        return $this->hasMany(ExaminationQuestionChoice::class, 'question_id');
    }
    public function choices_v2()
    {
        return $this->hasMany(ExaminationQuestionChoice::class, 'question_id')->select('id', 'choice_name', 'question_id', 'image_path')->where('is_removed', false);
    }
    public function choices_v3()
    {
        return $this->hasMany(ExaminationQuestionChoice::class, 'question_id')->select('id', 'choice_name', 'question_id', 'image_path', 'is_answer')->where('is_removed', false)->inRandomOrder();
    }
}
