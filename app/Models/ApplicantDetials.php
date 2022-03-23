<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantDetials extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $fillable = [
        "first_name",
        "last_name",
        "middle_name",
        "extention_name",
        "birthday",
        "birth_place",
        "sex",
        "nationality",
        "religion",
        "civil_status",
        "street",
        "barangay",
        "municipality",
        "province",
        "zip_code",
        "is_removed"
    ];
}
