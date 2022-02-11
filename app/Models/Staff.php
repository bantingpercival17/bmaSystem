<?php

namespace App\Models;

use App\Http\Controllers\EmployeeController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
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
    public function profile_pic($_data)
    {
        if (file_exists(public_path('assets/img/staff/' . strtolower(str_replace(' ', '_', $_data->user->name)) . '.jpg'))) {
            $_image = strtolower(str_replace(' ', '_', $_data->user->name)) . '.jpg';
        } else {
            $_image = 'avatar.png';
        }
        return '/assets/img/staff/' . $_image;
    }
    public function subject_handles()
    {
        return $this->hasMany(SubjectClass::class, 'staff_id')
            ->where('academic_id', Auth::user()->staff->current_academic()->id)
            ->where('is_removed', false);
    }
    public function grade_submission_midterm()
    {
        return $this->hasMany(SubjectClass::class, 'staff_id')
            ->leftJoin('grade_submissions as gs', 'gs.subject_class_id', 'subject_classes.id')
            ->where('gs.form', 'ad1')
            ->where('gs.period', 'finals')
            ->where('gs.is_approved')
            /* ->with('midterm_grade_submission') */
            ->where('subject_classes.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('subject_classes.is_removed', false);
    }
    public function grade_submission_finals()
    {
        return $this->hasMany(SubjectClass::class, 'staff_id')->with('finals_grade_submission')
            ->where('subject_classes.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('subject_classes.is_removed', false);
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
    public function daily_attendance_report()
    {
        return $this->hasOne(EmployeeAttendance::class, 'staff_id')->where('created_at', 'like', '%' . request()->input('_date') . '%')->latest();
    }
    public function attendance_list()
    {
        return $this->hasMany(EmployeeAttendance::class, 'staff_id');
    }
    public function date_attendance($_date)
    {
        return $this->hasOne(EmployeeAttendance::class, 'staff_id')->where('created_at', 'like', '%' . $_date . '%')->first();
    }
    public function current_academic()
    {
        $_academic = request()->input('_academic') ? AcademicYear::find(base64_decode(request()->input('_academic'))) : AcademicYear::where('is_active', 1)->first();
        return $_academic;
    }
    public function academics()
    {
        return AcademicYear::where('is_removed', false)->orderBy('id', 'Desc')->get();
    }
}
