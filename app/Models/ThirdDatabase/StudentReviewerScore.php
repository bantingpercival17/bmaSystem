<?php

namespace App\Models\ThirdDatabase;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentReviewerScore extends Model
{
    use HasFactory;
    protected $connection = 'mysql3';
    protected $fillable = ['student_id', 'score', 'examination_id','category_id'];
}
