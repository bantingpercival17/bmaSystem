<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalFees extends Model
{
    use HasFactory;
    protected $fillable = ['particular_id', 'amount', 'is_removed'];

    function particular()
    {
        return $this->belongsTo(Particulars::class, 'particular_id');
    }
}
