<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationalDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        "student_id",
        "school_name",
        "school_address",
        "graduated_year",
        "school_category",
        "school_level",
        "is_removed"
    ];
}
