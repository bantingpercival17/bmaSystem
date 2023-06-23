<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCancellation extends Model
{
    use HasFactory;
    protected $fillable = [
        'enrollment_id',
        'type_of_cancellations',
        'date_of_cancellation',
        'cancellation_evidence',
        'staff_id'
    ];
}
