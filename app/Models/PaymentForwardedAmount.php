<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentForwardedAmount extends Model
{
    use HasFactory;
    protected $fillable = ['previous_assessment_id', 'forwarded_amount', 'current_assessment_id',  'is_removed'];
}
