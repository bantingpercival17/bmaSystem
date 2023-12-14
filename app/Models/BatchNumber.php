<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchNumber extends Model
{
    use HasFactory;
    protected $fillable = ['batch_name', 'batch_number', 'is_removed'];
}
