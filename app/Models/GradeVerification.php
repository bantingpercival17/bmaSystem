<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeVerification extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject_class_id',
        'is_approved',
        'comments',
        'approved_by',
        'is_removed'
    ];
}
