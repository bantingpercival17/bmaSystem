<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipboardExamination extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'examination_code',
        'staff_id'
    ];
}
