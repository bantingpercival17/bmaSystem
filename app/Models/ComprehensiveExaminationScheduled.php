<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprehensiveExaminationScheduled extends Model
{
    use HasFactory;
    protected $fillable = ['student_id', 'examinee_id', 'scheduled', 'scheduled_staff_id', 'attemps'];

    function compre_examinee()
    {
        return $this->belongsTo(ComprehensiveExaminationExaminee::class, 'examinee_id');
    }
}
