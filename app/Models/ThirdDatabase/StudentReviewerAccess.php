<?php

namespace App\Models\ThirdDatabase;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentReviewerAccess extends Model
{
    use HasFactory;
    protected $connection = 'mysql3';
    protected $fillable = ['student_id', 'ip_address', 'device_details'];
}
