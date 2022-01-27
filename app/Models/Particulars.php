<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Particulars extends Model
{
    use HasFactory;
    protected $fillable = ['particular_name', 'particular_tag', 'particular_type', 'department', 'is_removed'];


    public function particular_fee()
    {
        return $this->hasMany(ParticularFees::class, 'particular_id')->where('academic_id', Auth::user()->staff->current_academic()->id);
    }
}
