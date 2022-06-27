<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantAlumnia extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $fillable = [
        'applicant_id',
        'staff_id',
        'is_removed',
    ];
    public function account()
    {
        return $this->belongsTo(ApplicantAccount::class, 'applicant_id');
    }
}
