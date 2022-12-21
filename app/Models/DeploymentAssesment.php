<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeploymentAssesment extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'agency_id'
    ];
    public function student()
    {
        return $this->belongsTo(StudentDetails::class, 'student_id');
    }
    public function shipboard_companies()
    {
        return $this->belongsTo(ShippingAgencies::class, 'agency_id');
    }
}
