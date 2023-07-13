<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentAdditionalFees extends Model
{
    use HasFactory;
    protected $fillable = ['enrollment_id', 'assessment_id', 'fees_id', 'status', 'is_removed'];

    function enrollment_assessment()
    {
        return $this->belongsTo(EnrollmentAssessment::class, 'enrollment_id');
    }
}
