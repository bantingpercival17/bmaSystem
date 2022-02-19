<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticularFees extends Model
{
    use HasFactory;
    protected $fillable = ['particular_id', 'particular_amount', 'academic_id', 'is_removed'];

    public function particular()
    {
        return $this->belongsTo(Particulars::class, 'particular_id');
    }
}
