<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprehensiveExamination extends Model
{
    use HasFactory;
    protected $fillable = ['function', 'competence_name', 'competence_code', 'file_name', 'course_id'];
    function examinee_details()
    {
        return $this->hasMany(ComprehensiveExaminationResult::class, 'comprehensive_id')/* ->where('examinee_id', $comprehensive_details->id) */->where('is_removed', false);
    }
    function examinee_result()
    {
        return $this->hasOne(ComprehensiveExaminationResult::class, 'comprehensive_id')->where('is_removed', false)->orderBy('result', 'desc');
    }
    function examination_details()
    {
        return $this->hasMany(ComprehensiveExaminationResult::class, 'comprehensive_id')->where('is_removed', false);
    }
}
