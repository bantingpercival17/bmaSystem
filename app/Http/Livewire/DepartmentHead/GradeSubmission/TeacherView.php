<?php

namespace App\Http\Livewire\DepartmentHead\GradeSubmission;

use App\Models\Role;
use App\Models\Staff;
use App\Models\StaffDepartment;
use App\Models\SubjectClass;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TeacherView extends Component
{
    public $viewPage = false;
    public $teacherListSearch;
    public $staffView;
    public $subjectView;
    public $activeCard = 'overview';
    public User $user;
    public $midtermCard = false;
    public $finalsCard = false;
    public $showModal = false;
    public $documentLink = null;
    function mount()
    {
        $this->user = Auth::user();
    }
    public function render()
    {
        # Get Department Head Id
        $department =  Role::where('name', 'department-head')->first();
        $user = Auth::user();
        # Get the Details of the Department Head 
        $department = StaffDepartment::where('role_id', $department->id)->where('staff_id', $this->user->staff->id)->where('is_active', true)->first();
        # Find the Teacher Role and get the ID
        $teacherRole = Role::where('name', 'teacher')->first();
        # Get all the Teacher base on the Role and Department
        $teacherLists = $this->teacherLists($this->teacherListSearch, $teacherRole, $department);

        if (request()->query('staff')) {
            $this->staffView =  request()->query('staff') ? $this->setEmployee(base64_decode(request()->query('staff'))) : $this->staffView;
            $this->viewPage = true;
        }
        $this->subjectView = request()->query('subject') ? $this->setSubjectClass(base64_decode(request()->query('subject'))) : $this->subjectView;
        # Set the view and the need list
        return view('livewire.department-head.grade-submission.teacher-view', compact('teacherLists', 'teacherRole', 'department'));
    }

    function teacherLists($searchInput, $teacherRole, $department)
    {
        # Get the Staff Model and Select the following columns
        # and Join to the Staff Department with the conditions datas
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
            ->where('staff_departments.role_id', $teacherRole->id)
            ->where('staff_departments.department_id', $department->department_id)
            ->where('staff.is_removed', false)
            ->orderBy('staff.last_name', 'asc')
            ->orderBy('staff.first_name', 'asc');
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
        # Get the List of Teachers
        $teachers = $teachers->get();
        # Return value to the main function
        return $teachers;
    }
    function setEmployee($data)
    {
        $employee = [];
        if ($data != '') {
            $employee = Staff::find($data);
        }
        return $employee;
    }
    function setSubjectClass($subject)
    {
        $this->activeCard = 'subject';
        $subjectDetails = SubjectClass::find($subject);
        $this->midtermCard = $subjectDetails->midterm_grade_submission ? true : false;
        $this->finalsCard = $subjectDetails->finals_grade_submission ? true : false;
        return $subjectDetails;
    }
    function switchCard($data)
    {
        $this->activeCard = $data;
    }
    function showDocuments($data, $period, $form)
    {
        $link = route('department-head.report-view') . '?_subject=' . base64_encode($data) . '&_preview=pdf&_period=' . $period . '&_form=' . $form;
        /* 
        {{ route('department-head.report-view') }}?_subject={{ base64_encode($_subject_class->id) }}&_period=midterm&_preview=pdf&_form=ad1"
        */
        $this->showModal = true;
        $this->documentLink = null;
        $this->documentLink = $link;
    }
    function hideDocuments()
    {
        $this->showModal = false;
        $this->documentLink = null;
    }
    function showConfirmDialogBox($data)
    {

        switch (base64_decode($data)) {
            case 'disapproved':
                $value = array('text' => 'Do you want to disapproved this Grading Sheet?', 'method' => 'disapprovedEnrollment');
                break;
            case 'approved':
                $value = array('text' => 'Do you want to approved this Grading Sheet?', 'method' => 'approvedEnrollment');
                break;
        }
        $this->dispatchBrowserEvent('swal:confirm', [
            'title' => 'Grade Verification',
            'text' => $value['text'],
            'type' => 'info',
            'confirmButtonText' => 'Yes',
            'cancelButtonText' => 'Cancel',
            'method' => $value['method'],
            'params' => ['data' => $data],
        ]);
    }
}
