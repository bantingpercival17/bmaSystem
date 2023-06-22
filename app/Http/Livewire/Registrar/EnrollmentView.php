<?php

namespace App\Http\Livewire\Registrar;

use App\Models\CourseOffer;
use App\Models\Curriculum;
use App\Models\StudentDetails;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
//use Livewire\WithPagination;

class EnrollmentView extends Component
{
    //use WithPagination;

    public $searchInput;
    public $academic;

    public function render()
    {
        $courseLists = CourseOffer::all();
        $_courses = CourseOffer::all();
        $_curriculums = Curriculum::where('is_removed', false)->get();
        $_academic = Auth::user()->staff->current_academic();
        $this->academic =  request()->query('_academic') ?: $this->academic;
        $academic = base64_decode($this->academic) ?: $_academic->id;
        $studentsList = StudentDetails::select('student_details.id', 'student_details.first_name', 'student_details.last_name')
            ->leftJoin('enrollment_applications as ea', 'ea.student_id', 'student_details.id')
            ->where('ea.academic_id', $academic)
            ->whereNull('ea.is_approved')
            ->where('ea.is_removed', false)->paginate(10);
        if ($this->searchInput != '') {
            $student_detials = new StudentDetails();
            $studentsList = $student_detials->student_search($this->searchInput);
        }
        return view('livewire.registrar.enrollment-view', compact('_courses', 'courseLists', '_curriculums', 'studentsList'));
    }
    /* Disapproved Application */
    function confirmBox($data, $status)
    {
        if (base64_decode($status) == 'disapproved') {
            $value = array('text' => 'Do you want to disapproved this Enrollment Application?', 'method' => '');
        }
        if (base64_decode($status) == 'approved') {
            $value = array('text' => 'Do you want to approved this Enrollment Application?', 'method' => '');
        }
        $this->dispatchBrowserEvent('swal:confirm', [
            'title' => 'Enrollment Assessment',
            'text' => $value['text'],
            'type' => 'info',
            'confirmButtonText' => 'Yes',
            'cancelButtonText' => 'Cancel',
            'method' => $value['method'],
            'params' => ['data' => $data],
        ]);
    }
}
