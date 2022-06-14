<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantMedicalResult extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $fillable = ['applicant_id','is_fit','is_pending','image_path','remarks'];
    public function account()
    {
        return $this->belongsTo(ApplicantAccount::class, 'applicant_id');
    }
}

