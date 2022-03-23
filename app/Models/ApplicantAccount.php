<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ApplicantAccount extends  Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $connection = 'mysql2';
    protected $fillable = [
        'name',
        'email',
        'password',
        'applicant_number',
        'course_id',
        'academic_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
