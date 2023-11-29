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
    function fee_details()
    {
        return $this->belongsTo(AdditionalFees::class, 'fees_id')->with('particular');
    }
    function fee_total_paid()
    {
        $fee_name = $this->fee_details->particular->particular_name;
        return  PaymentTransaction::where('assessment_id', $this->assessment_id)->where('is_removed', false)
            ->where('remarks', 'like', '%' . strtoupper($fee_name) . '%')->sum('payment_amount');
    }
}
