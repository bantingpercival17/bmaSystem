<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemestralFee extends Model
{
    use HasFactory;
    protected $fillable = ['particular_fee_id', 'course_semestral_fee_id',  'is_removed'];
    public function particular_fee()
    {
        return $this->hasOne(ParticularFees::class, 'particular_fee_id');
    }
}
