<?php

namespace App\Http\Livewire\Registrar\Subjects\SubjectHandle;

use App\Models\AcademicYear;
use App\Models\Department;
use App\Models\Role;
use App\Models\Staff;
use Livewire\Component;

class TeacherView extends Component
{
    public $teacherListSearch;
    public $selectDepartment;
    public $selectedDepartment = null;
    public $academic;
    public function render()
    {
        $departmentList = array(
            'All',
            'General Education',
            'MARINE TRANSPORTATION',
            'MARINE ENGINEERING'
        );
        $this->selectedDepartment = $this->selectedDepartment ?: 'All';
        /*  $departmentList = Department::all(); */
        // Set the Academic Year
        $this->academic = $this->setAcademicYear();
        $teacherLists = $this->teacherLists($this->teacherListSearch, $this->selectedDepartment, $this->academic);

        return view('livewire.registrar.subjects.subject-handle.teacher-view', compact('departmentList', 'teacherLists'));
    }
    function setAcademicYear()
    {
        $data = $this->academic;
        if ($this->academic == '') {
            $_academic = AcademicYear::where('is_active', 1)->first();
            $data = base64_encode($_academic->id);
        }
        if (request()->query('_academic')) {
            $data = request()->query('_academic') ?: $this->academic;
        }
        return $data;
    }
    function teacherLists($searchInput, $department, $academic)
    {
        // Get the Role ID of Teacher
        $teacherRole = Role::where('name', 'teacher')->first();
        $teachers = Staff::select(
            'staff.id',
            'staff.user_id',
            'staff.first_name',
            'staff.last_name',
            'staff_departments.position',
            'staff_departments.role_id',
            'staff_departments.department_id'
        )
            ->join('staff_departments', 'staff_departments.staff_id', 'staff.id')
            ->join('subject_classes', 'subject_classes.staff_id', 'staff.id')
            ->where('subject_classes.academic_id', base64_decode($academic))
            ->where('subject_classes.is_removed', false)
            ->where('staff_departments.role_id', $teacherRole->id)
            ->where('staff.is_removed', false)
            ->orderBy('staff.last_name', 'asc')
            ->orderBy('staff.first_name', 'asc')
            ->groupBy('staff.id');
        # Search Staff Name
        if ($searchInput != '') {
            $value = explode(',', $searchInput); # Seperate the Word by coma
            $count = count($value); # Count the array list
            # if the count have greater then 1 value set the search into last name and first name
            if ($count > 1) {
                $teachers = $teachers->where('staff.last_name', 'like', '%' . $value[0] . '%')
                    ->where('staff.first_name', 'like', '%' . trim($value[1]) . '%')
                    ->orderBy('staff.last_name', 'asc');
            } else {
                $teachers = $teachers->where('staff.last_name', 'like', '%' . $value[0] . '%')
                    ->orderBy('staff.last_name', 'asc');
            }
        }
        # Sort by Categories and Period
        if ($department !== 'All') {
            $department = Department::where('name', $department)->first();
            $teachers = $teachers->where('staff_departments.department_id', $department->id);
        }
        # Get the List of Teachers
        $teachers = $teachers->get();
        # Return value to the main function
        return $teachers;
    }
}
