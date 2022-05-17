<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffPayroll extends Model
{
    use HasFactory;
    protected $connection = 'mysql3';
    protected $fillable = ['cut_off', 'period'];
}
