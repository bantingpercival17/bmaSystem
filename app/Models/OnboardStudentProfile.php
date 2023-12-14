<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnboardStudentProfile extends Model
{
    use HasFactory;
    protected $fillable = ['student_id', 'trb_number', 'facebook_link', 'mismo_account', 'is_removed'];
}
