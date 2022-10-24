<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoidTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'payment_id',
        'void_reason',
        'is_approved',
        'staff_id',
        'is_removed'
    ];
    public function payment()
    {
        return $this->belongsTo(PaymentTransaction::class, 'payment_id');
    }
}
