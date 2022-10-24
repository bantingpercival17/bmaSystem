<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'assessment_id',
        'or_number',
        'payment_amount',
        'payment_method',
        'remarks',
        'payment_transaction',
        'transaction_date',
        'staff_id',
        'is_removed'
    ];
    public function enrollment_assessment()
    {
        return $this->belongsTo(EnrollmentAssessment::class, 'enrollment_id');
    }
    public function payment_assessment()
    {
        return $this->belongsTo(PaymentAssessment::class, 'assessment_id');
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
    public function payment_void()
    {
        return $this->hasOne(VoidTransaction::class, 'payment_id')->where('is_removed', false);
    }
}
