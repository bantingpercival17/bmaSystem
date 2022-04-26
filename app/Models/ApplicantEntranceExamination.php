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
        //$_item =  $this->applicant->course_id == 3 ? 100 : 200;
        return  $this->hasMany(ApplicantExaminationAnswer::class, 'examination_id')
            ->join('bma_portal.examination_question_choices as eqc', 'eqc.id', 'applicant_examination_answers.question_id')
            ->where('eqc.is_answer', true)->sum('eqc.is_answer as total');
        #$_percent = ($_score / $_item) * 100;
        #$_passing = $this->applicant->course_id == 3 ? 50 : 50;
        #return $_percent >= $_passing ? true : false;
    }
}
