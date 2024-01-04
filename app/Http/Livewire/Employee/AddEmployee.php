<?php

namespace App\Http\Livewire\Employee;

use App\Models\Department;
use App\Models\Role;
use App\Models\Staff;
use App\Models\StaffDepartment;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class AddEmployee extends Component
{
    public $employee = array(
        'first_name' => '',
        'last_name' => '',
        'middle_name' => '',
        'department' => '',
        'role' => '',
        'position' => '',
        'status' => ''
    );
    public $errorMessage = null;
    public function render()
    {
        $roles = Role::all();
        $departmentList = Department::where('is_removed', false)->get();
        return view('livewire.employee.add-employee', compact('roles', 'departmentList'));
    }
    function storeEmployee()
    {
        $this->validate([
            'employee.first_name' => 'required',
            'employee.last_name' => 'required',
            'employee.middle_name' => 'required',
            'employee.department' => 'required',
            'employee.role' => 'required',
            'employee.position' => 'required',
            'employee.status' => 'required'
        ]);
        $this->errorMessage = null;
        try {
            $account = array(
                'name' => ucwords(strtolower(trim($this->employee['first_name'] . ' ' . $this->employee['last_name']))),
                'email' => $this->initial_name($this->employee['first_name'], $this->employee['last_name']),
                'passsword' => Hash::make('bmafaculty')
            );
            // Create Account
            $account = User::create($account);
            // Add a Role
            $account->attachRole($this->employee['role']);
            $deparmentName = Department::find($this->employee['department']);
            $staff = array(
                'user_id' => $account->id,
                'staff_no' => date('YM-d'),
                'first_name' =>  ucwords(strtolower(trim($this->employee['first_name']))),
                'last_name' => ucwords(strtolower(trim($this->employee['last_name']))),
                'middle_name' => ucwords(strtolower(trim($this->employee['middle_name']))),
                'job_description' =>  ucwords(strtolower(trim($this->employee['position']))),
                'department' => $deparmentName->name,
                'is_removed' => false
            );
            $staff = Staff::create($staff);
            $verifyData = StaffDepartment::where([
                'staff_id' => $staff->id,
                'role_id' => $this->employee['role'],
                'department_id' => $this->employee['department'],
                'position' => ucwords(strtolower(trim($this->employee['position']))),
            ])->first();
            $roleDetails = array(
                'staff_id' => $staff->id,
                'role_id' => $this->employee['role'],
                'department_id' => $this->employee['department'],
                'position' => ucwords(strtolower(trim($this->employee['position']))),
                'is_active' => $this->employee['status']
            );
            if (!$verifyData) {
                StaffDepartment::create($roleDetails);
            }
            $this->reset('employee');
            return redirect(route('employee.view') . '?employee=' . base64_encode($staff->id));
        } catch (\Throwable $th) {
            $this->errorMessage = $th->getMessage();
        }
    }
    function initial_name($first_name, $last_name)
    {

        // Split the full name into an array of words
        $words = explode(' ', $first_name);
        // Initialize an empty string to store the initials
        $initials = '';
        // Loop through each word and append the first letter followed by a dot to the initials
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1)) . '.';
        }
        // Remove the trailing dot
        $initials = rtrim($initials, '.');
        return strtolower($initials . '.' . $last_name . '@bma.edu.ph'); // Output: J.D.
    }
}
