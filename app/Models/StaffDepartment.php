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
        'is_active',
        'is_removed'
    ];
    function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
