<?php

namespace App\Http\Livewire;

use App\Models\Department;
use App\Models\Role;
use App\Models\Staff;
use App\Models\StaffDepartment;
use Livewire\Component;

class EmployeeView extends Component
{
    public $searchInput;
    public $employee;
    public $formRole = false;
    public $activeCard = 'profile';
    public $department;
    public $role;
    public $position;
    public $status;
    public function render()
    {
        $this->employee = request()->query('employee') ? $this->setEmployee(base64_decode(request()->query('employee'))) : $this->employee;
        $employeeList = $this->searchEmployee($this->searchInput);
        $departmentList = Department::where('is_removed', false)->get();
        $roles = Role::all();
        return view('livewire.employee-view', compact('employeeList', 'departmentList', 'roles'));
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
            'position' => $this->position,
            'is_active' => $this->status
        );
        StaffDepartment::create($roleDetails);
    }
}
