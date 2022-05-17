<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffSalaryDetailes extends Model
{
    use HasFactory;
    protected $connection = 'mysql3';
    protected $fillable = [
        'staff_id',
        'salary_amount',
        'allowance_amount',
        'sss_contribution',
        'philhealth_contribution',
        'pagibig_contribution',
        'created_by_id',
        'is_removed'
    ];
}
