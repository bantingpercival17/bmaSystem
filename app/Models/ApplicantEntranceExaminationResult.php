<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantEntranceExaminationResult extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $fillable = ['applicant_id', 'examination_id', 'score', 'result', 'examination_date', 'remarks', 'is_removed'];
    public function account()
    {
        return $this->belongsTo(ApplicantAccount::class, 'applicant_id');
    }
}
