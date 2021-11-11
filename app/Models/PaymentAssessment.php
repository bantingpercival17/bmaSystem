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
        'voucher_amount',
        'total_payment',
        'staff_id',
        'is_removed',
    ];
}
