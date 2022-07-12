<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipboardExaminationAnswer extends Model
{
    use HasFactory;
    protected $fillable = [
        'examination_id',
        'question_id'
    ];
}
