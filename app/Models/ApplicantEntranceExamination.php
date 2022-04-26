<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantEntranceExamination extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $fillable = ['applicant_id', 'examination_code'];


    public function examination_result()
    {
        return  $this->hasMany(ApplicantExaminationAnswer::class, 'examination_id')
            ->join('bma_portal.examination_question_choices as eqc', 'eqc.id', 'bma_website.applicant_examination_answers.choices_id')
            ->where('eqc.is_answer', true);
    }
}
