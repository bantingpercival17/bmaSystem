<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTrasanctionOnline extends Model
{
    use HasFactory;
    protected $fillable = [
        'assessment_id',
        'enrollment_id',
        'amount_paid',
        'reference_number',
        'transaction_type',
        'reciept_attach_path',
        'is_approved',
        'comment_remarks',
        'or_number',
        'is_removed'
    ];
}
