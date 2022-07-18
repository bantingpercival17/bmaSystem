<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusStcwKup extends Model
{
    use HasFactory;
    protected $fillable = [
        'stcw_competence_id',
        'kup_content'
    ];
}
