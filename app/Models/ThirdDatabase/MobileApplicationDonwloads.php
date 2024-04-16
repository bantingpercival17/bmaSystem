<?php

namespace App\Models\ThirdDatabase;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileApplicationDonwloads extends Model
{
    use HasFactory;
    protected $fillable = ['app_id', 'version_id', 'student_id', 'is_removed'];
}
