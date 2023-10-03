<?php

namespace App\Http\Livewire;

use App\Models\Staff;
use Livewire\Component;

class EmployeeView extends Component
{
    public $searchInput;
    public $activeCard = 'profile';
    public function render()
    {
        $employee = $this->setEmployee(base64_decode(request()->query('employee')));
        $employeeList = $this->searchEmployee($this->searchInput);
        return view('livewire.employee-view', compact('employeeList', 'employee'));
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
}
