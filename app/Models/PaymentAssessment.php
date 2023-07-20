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
        'upon_enrollment',
        'monthly_payment',
        'staff_id',
        'is_removed',
    ];
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
    public function enrollment_assessment()
    {
        return $this->belongsTo(EnrollmentAssessment::class, 'enrollment_id');
    }
    public function course_semestral_fee()
    {
        return $this->belongsTo(CourseSemestralFees::class, 'course_semestral_fee_id');
    }
    public function total_paid_amount()
    {
        return $this->hasMany(PaymentTransaction::class, 'assessment_id')->where('payment_transaction', 'TUITION FEE')->where('is_removed', false);
    }
    public function payment_transaction()
    {
        return $this->hasMany(PaymentTransaction::class, 'assessment_id')->where('is_removed', false);
    }
    public function online_enrollment_payment()
    {
        return $this->hasOne(PaymentTrasanctionOnline::class, 'assessment_id')->where('is_removed', false);
    }
    public function online_payment_transaction()
    {
        return $this->hasMany(PaymentTrasanctionOnline::class, 'assessment_id')->orderBy('is_approved')->where('is_removed', false);
    }
    public function payment_assessment_paid()
    {
        return $this->hasOne(PaymentTransaction::class, 'assessment_id')/* ->where('remarks', 'Upon Enrollment') */->where('is_removed', false);
    }
    public function payment_transaction_online()
    {
        return $this->hasOne(PaymentTransaction::class, 'assessment_id')->where('remarks', 'Upon Enrollment')->where('is_removed', false);
    }
    public function payment_remarks($data)
    {
        $_data =  $this->hasOne(PaymentTransaction::class, 'assessment_id')->where('remarks', $data)->where('is_removed', false)->first();
        return $_data ?  $_data->payment_amount : '';
    }
    function additional_fees()
    {
        return $this->hasMany(PaymentAdditionalFees::class, 'assessment_id')->where('is_removed', false);
    }
    function account_card_details()
    {
        $transactionList = array();
        array_push($transactionList, array(
            'date' => $this->created_at->format('Y-m-d'),
            'orNumber' => '',
            'remarks' => 'TUITION FEE',
            'debit' => null,
            'credit' => $this->total_payment
        ));
        //$transactions = $this->hasMany(PaymentTransaction::class, 'assessment_id')->where('is_removed', false);
        $transactions = $this->payment_transaction;
        $addtionalFees = $this->additional_fees;
        foreach ($addtionalFees as $key => $value) {
            array_push($transactionList, array(
                'date' => $this->created_at->format('Y-m-d'),
                'orNumber' => '',
                'remarks' => $value->fee_details->particular->particular_name,
                'debit' => null,
                'credit' => $value->fee_details->amount
            ));
        }
        foreach ($transactions as $key => $transaction) {
            array_push($transactionList, array(
                'date' => $transaction->transaction_date,
                'orNumber' => $transaction->or_number,
                'remarks' => $transaction->remarks,
                'debit' => $transaction->payment_amount,
                'credit' => null
            ));
        }
        usort($transactionList, function ($a, $b) {
            $dateA = strtotime($a['date']);
            $dateB = strtotime($b['date']);
            return $dateA - $dateB;
        });

        return $transactionList;
    }
}
