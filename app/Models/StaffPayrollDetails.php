<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffPayrollDetails extends Model
{
    use HasFactory;
    protected $connection = 'mysql3';
    protected $fillable = ['payroll_id', 'salary_id'];
}
