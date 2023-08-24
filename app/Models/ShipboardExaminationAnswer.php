<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipboardExaminationAnswer extends Model
{
    use HasFactory;
    protected $fillable = [
        'examination_id',
        'question_id',
        'choices_id'
    ];
    public function assessment_questions()
    {
        return $this->belongsTo(ShipboardExamination::class, 'examination_id');
    }
    public function question()
    {
        return $this->belongsTo(ExaminationQuestion::class, 'question_id')->select('id', 'question', 'image_path');
    }
}
