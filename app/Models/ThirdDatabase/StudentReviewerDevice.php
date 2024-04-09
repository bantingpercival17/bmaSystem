<?php

namespace App\Models\ThirdDatabase;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentReviewerDevice extends Model
{
    use HasFactory;
    protected $connection = 'mysql3';
    protected $fillable = ['student_id', 'device_details', 'is_allow', 'is_removed'];
}
