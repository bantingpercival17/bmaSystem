<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class AdministrativeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('administrative');
    }
    public function index()
    {
        $_employees = Staff::select('staff.id', 'staff.user_id', 'staff.first_name', 'staff.last_name', 'staff.department', 'ea.staff_id', 'ea.description', 'ea.created_at')
            ->leftJoin('employee_attendances as ea', 'ea.staff_id', 'staff.id')
            ->groupBy('staff.id')
            ->orderBy('staff.last_name', 'asc')
            //->orderBy('ea.updated_at', 'desc')
            ->get();
        return view('administrative.attendance.view', compact('_employees'));
    }
}
