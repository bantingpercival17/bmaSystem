<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipboardExamination extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'examination_code',
        'staff_id'
    ];

    public function assessment_questions()
    {
        return $this->hasMany(ShipboardExaminationAnswer::class, 'examination_id')->select('id','question_id')->with('question.choices_v2')->inRandomOrder();
    }
    public function result()
    {
        return $this->hasMany(ShipboardExaminationAnswer::class, 'examination_id')
            ->join('examination_question_choices', 'examination_question_choices.id', 'shipboard_examination_answers.choices_id')
            ->where('examination_question_choices.is_answer', true);
    }
}
