<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class ExecutiveOfficeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('executive');
        set_time_limit(0);
    }
    public function index()
    {
        $_employees = Staff::select('staff.id', 'staff.user_id', 'staff.first_name', 'staff.last_name', 'staff.department', 'ea.staff_id', 'ea.description', 'ea.created_at')
            ->leftJoin('employee_attendances as ea', 'ea.staff_id', 'staff.id')
            //->where('ea.created_at', 'like', '%' . now()->format('Y-m-d') . '%')
            ->groupBy('staff.id')
            ->orderBy('ea.updated_at', 'desc')->get();
        return view('administrator.employee.attendance', compact('_employees'));
    }
}
