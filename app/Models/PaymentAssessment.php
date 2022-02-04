<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentAssessment extends Model
{
    use HasFactory;
    protected $fillable = [
        'enrollment_id',
        'payment_mode',
        'course_semestral_fee_id',
        'voucher_amount',
        'total_payment',
        'staff_id',
        'is_removed',
    ];

    public function enrollment_assessment()
    {
        return $this->belongsTo(EnrollmentAssessment::class, 'enrollment_id');
    }
    public function course_semestral_fee()
    {
        return $this->belongsTo(CourseSemestralFees::class, 'course_semestral_fee_id');
    }
    public function total_payment()
    {
        return $this->hasMany(PaymentTransaction::class, 'assessment_id')->where('payment_transaction', 'TUITION FEE')->sum('payment_amount');
    }
}
