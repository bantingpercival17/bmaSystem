<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipboardAssessmentDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'assesor_id',
        'practical_score',
        'oral_score'
    ];


    public function staff()
    {
        return $this->belongsTo(Staff::class, 'assesor_id');
    }
}
