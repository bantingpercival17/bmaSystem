<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Staff extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'staff_no',
        'first_name',
        'last_name',
        'middle_name',
        'job_description',
        'department',
        'created_by'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function subjects_handles()
    {
        return $this->hasMany(SubjectClass::class, 'staff_id')->where('academic_id', Crypt::decrypt(request()->input('_academic')));
    }

    // Staff Attendance
    public function attendance()
    {
        return $this->hasMany(EmployeeAttendance::class, 'staff_id')->where('created_at', 'like', '%' . now()->format('Y-m-d') . '%')->latest();
    }
    public function daily_attendance()
    {
        return $this->hasOne(EmployeeAttendance::class, 'staff_id')->where('created_at', 'like', '%' . date('Y-m-d') . '%')->latest();
    }
}
