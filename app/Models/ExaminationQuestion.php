<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExaminationQuestion extends Model
{
    use HasFactory;
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
}
