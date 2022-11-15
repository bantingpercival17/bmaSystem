<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCanellation extends Model
{
    use HasFactory;
    protected $fillable = [
        'enrollment_id',
        'reason_of_cancellation',
        'type_of_cancellation',
        'date_of_cancellation',
        'is_removed',
    ];
}
