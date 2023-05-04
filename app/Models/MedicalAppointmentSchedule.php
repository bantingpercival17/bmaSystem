<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalAppointmentSchedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'capacity',
        'is_close'
    ];
}
