<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantEntranceExamination extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $fillable = ['applicant_id', 'examination_code'];

    public function applicant()
    {
        return $this->belongsTo(ApplicantAccount::class, 'applicant_id');
    }
    function examination_scheduled()
    {
        return $this->hasOne(ApplicantExaminationSchedule::class, 'examination_id')->where('is_removed', false);
    }
    function examination_scheduled_history()
    {
        return $this->hasMany(ApplicantExaminationSchedule::class, 'examination_id');
    }
    public function examination_result()
    {
        $_transmutation_college = array(
            array(0, 19, 20),
            array(20, 29, 30),
            array(30, 39, 35),
            array(40, 49, 40),
            array(50, 59, 45),
            array(60, 69, 50),
            array(70, 79, 55),
            array(80, 89, 60),
            array(90, 99, 65),
            array(100, 109, 70),
            array(110, 119, 73),
            array(120, 129, 75),
            array(130, 139, 78),
            array(140, 149, 82),
            array(150, 159, 85),
            array(160, 169, 88),
            array(170, 179, 92),
            array(180, 189, 95),
            array(190, 199, 98),
            array(200, 200, 100),
        );
        $_transmutation_shs = array(
            array(0, 9, 20),
            array(10, 14, 30),
            array(15, 19, 35),
            array(20, 24, 40),
            array(25, 29, 45),
            array(30, 34, 50),
            array(35, 39, 55),
            array(40, 44, 60),
            array(45, 49, 65),
            array(50, 54, 70),
            array(55, 59, 73),
            array(60, 64, 75),
            array(65, 69, 78),
            array(70, 74, 82),
            array(75, 79, 85),
            array(80, 84, 88),
            array(85, 89, 92),
            array(90, 94, 95),
            array(95, 99, 98),
            array(100, 100, 100),
        );
        $_percentage = 0;
        $_percent = $this->applicant->course_id != 3 ? $_transmutation_college : $_transmutation_shs;
        $_item =  $this->applicant->course_id == 3 ? 100 : 200;
        /*   return  $this->hasMany(ApplicantExaminationAnswer::class, 'examination_id')
            ->join(env("DB_DATABASE").'.examination_question_choices as eqc', 'eqc.id', env('DB_DATABASE_SECOND').'.applicant_examination_answers.choices_id')
            ->where('eqc.is_answer', true)->count(); */
        $_grade = $this->hasMany(ApplicantExaminationAnswer::class, 'examination_id')
            ->join(env('DB_DATABASE') . '.examination_question_choices as eqc', 'eqc.id', env('DB_DATABASE_SECOND') . '.applicant_examination_answers.choices_id')
            ->where('eqc.is_answer', true)->count();
        foreach ($_percent as $key => $value) {
            $_percentage = $_grade >= $value[0]  && $_grade <= $value[1] ? $value[2] : $_percentage;
        }
        #$percent = ($_grade / $_item) * 100;
        $passing = $this->applicant->course_id == 3 ? 70 : 70;
        $examinationResult = $_percentage >= $passing ? true : false;
        return [$_grade, $_percentage, $examinationResult];
    }
    public function choice_answer($_data)
    {
        return $this->hasOne(ApplicantExaminationAnswer::class, 'examination_id')->where('question_id', $_data)->where('is_removed', false);
    }

    public function examination_questioner()
    {
        return $this->hasMany(ApplicantExaminationAnswer::class, 'examination_id')->orderBy('question_id')->limit(200);
    }
    function check_place($email, $date)
    {
        return UserDeviceDetails::where('client_name', $email)->where('created_at', 'like', '%' . $date . '%')->orderBy('id', 'desc')->first();
    }
    function examination_result_v2()
    {
        return $this->hasOne(ApplicantEntranceExaminationResult::class, 'examination_id')->where('is_removed', false);
    }
}
