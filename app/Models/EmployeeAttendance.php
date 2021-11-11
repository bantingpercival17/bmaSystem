<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAttendance extends Model
{
    use HasFactory;
    protected $fillable = ['staff_id', 'description','time_in','time_out'];
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
