<?php

namespace App\Http\Livewire\Employee;

use App\Models\Department;
use App\Models\Role;
use App\Models\Staff;
use App\Models\User;
use Livewire\Component;

class AttendanceView extends Component
{
    public $searchInput;
    public $searchSelect;
    public $employee;
    public $department = 1;
    public $role = 1;
    public $position;
    public $status = 1;
    public function render()
    {
        $this->employee = request()->query('employee') ? $this->setEmployee(base64_decode(request()->query('employee'))) : $this->employee;
        $employeeList = $this->searchEmployee($this->searchInput, $this->searchSelect);
        $departmentList = Department::where('is_removed', false)->get();
        $employeeRoles = $this->employee ? $this->employee->roles : [];
        $roles = Role::all();
        return view('livewire.employee.attendance-view', compact('employeeList', 'departmentList', 'roles', 'employeeRoles'));
    }
    function searchEmployee($searchInput, $searchSelect)
    {
        $data = Staff::where('is_removed', false)
            ->join('employee_attendances', 'employee_attendances.staff_id', 'staff.id')
            // ->where('employee_attendances.created_at', 'like', '%' . now()->format('Y-m-d') . '%')
            ->groupBy('employee_attendances.staff_id');
        if ($searchInput) {
            $value = explode(',', $searchInput);
            if (count($value) > 1) {
                $data = $data->where('staff.last_name', 'like', '%' . $value[0] . '%')->where('staff.first_name', 'like', '%' . $value[0] . '%');
            } else {
                $data = $data->where('staff.last_name', 'like null', '%' . $searchInput . '%');
            }
        }
        if ($searchSelect) {
            if ($searchSelect == 2) {
                $data = $data->where('employee_attendances.created_at', 'like', '%' . now()->format('Y-m-d') . '%')->orderBy('created_at', 'desc');
            }
            if ($searchSelect == 3) {
                $data = $data->where('employee_attendances.created_at', 'like', '%' . now()->format('Y-m-d') . '%');
            }
        }
        return $data->orderBy('staff.last_name', 'asc')->get();
    }
}
