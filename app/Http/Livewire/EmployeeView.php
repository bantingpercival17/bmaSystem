<?php

namespace App\Http\Livewire;

use App\Models\Staff;
use Livewire\Component;

class EmployeeView extends Component
{
    public $employeeList = [];
    public $searchInput;
    public $employee = [];
    public function render()
    {
        $employeeList = $this->employeeList;
        $employee = $this->employee;
        return view('livewire.employee-view', compact('employeeList', 'employee'));
    }
    function searchEmployee()
    {
        if ($this->searchInput) {
            $this->employeeList = Staff::where('last_name', 'like', '%' . $this->searchInput . '%')->orWhere('last_name', 'like', '%' . $this->searchInput . '%')
                ->orderBy('last_name', 'asc')
                ->get();
        } else {
            $this->employeeList = [];
        }
    }
    function setEmployee($data)
    {
        $employee = Staff::find($data);
       
        if ($employee) {
            $this->employee = $employee;
            $this->employeeList = [];
        }
    }
}
