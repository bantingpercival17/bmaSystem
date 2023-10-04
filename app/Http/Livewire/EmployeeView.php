<?php

namespace App\Http\Livewire;

use App\Models\Department;
use App\Models\Role;
use App\Models\Staff;
use App\Models\StaffDepartment;
use App\Models\User;
use Livewire\Component;

class EmployeeView extends Component
{
    public $searchInput;
    public $employee;
    public $formRole = false;
    public $activeCard = 'profile';
    public $department = 1;
    public $role = 1;
    public $position;
    public $status = 1;
    public $testingValue;
    public function render()
    {
        $this->employee = request()->query('employee') ? $this->setEmployee(base64_decode(request()->query('employee'))) : $this->employee;
        $employeeList = $this->searchEmployee($this->searchInput);
        $departmentList = Department::where('is_removed', false)->get();
        $employeeRoles = $this->employee ? $this->employee->roles : [];
        $roles = Role::all();
        return view('livewire.employee-view', compact('employeeList', 'departmentList', 'roles', 'employeeRoles'));
    }
    function searchEmployee($searchInput)
    {
        $data = [];
        if ($searchInput) {
            $value = explode(',', $searchInput);
            if (count($value) > 1) {
                $data = Staff::where('last_name', 'like', '%' . $value[0] . '%')->where('first_name', 'like', '%' . $value[0] . '%');
            } else {
                $data = Staff::where('last_name', 'like', '%' . $searchInput . '%');
            }
            $data = $data->orderBy('last_name', 'asc')->get();
        }
        return $data;
    }
    function setEmployee($data)
    {
        $employee = [];
        if ($data != '') {
            $employee = Staff::find($data);
        }
        return $employee;
    }
    function switchCard($data)
    {
        $this->activeCard = $data;
    }
    function addRole()
    {
        $this->formRole = $this->formRole == false ? true : false;
    }
    function storeRole()
    {
        $roleDetails = array(
            'staff_id' => $this->employee->id,
            'role_id' => $this->role,
            'department_id' => $this->department,
            'position' => strtoupper($this->position),
            'is_active' => $this->status
        );
        $user = User::find($this->employee->user_id);
        $hasAllRoles = false;

        foreach ($this->employee->user->roles as $role) {
            if ($role->id == $this->role) {
                $hasAllRoles = true;
                break; // Exit the loop early if the user does not have a role
            }
        }
        if (!$hasAllRoles) {
            $user->attachRole($this->role);
        }
        $verifyData = StaffDepartment::where([
            'staff_id' => $this->employee->id,
            'role_id' => $this->role,
            'department_id' => $this->department,
            'position' => strtoupper($this->position),
        ])->first();
        if (!$verifyData) {
            StaffDepartment::create($roleDetails);
        }
        $this->role = 1;
        $this->department = 1;
        $this->status = 1;
        $this->position =  '';
    }
}
