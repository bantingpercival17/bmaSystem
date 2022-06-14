<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantBriefing extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    public function account()
    {
        return $this->belongsTo(ApplicantAccount::class, 'applicant_id');
    }
}
