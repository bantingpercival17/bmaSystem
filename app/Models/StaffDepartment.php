<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffDepartment extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $fillable = [
        'staff_id',
        'role_id',
        'department_id',
        'position',
        'is_removed'
    ];
}
