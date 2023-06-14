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

    public $studentLists = [];
    public $searchInput;

    public function render()
    {
        $courseLists = CourseOffer::all();
        $_courses = CourseOffer::all();
        $_curriculums = Curriculum::where('is_removed', false)->get();
        $_academic = Auth::user()->staff->current_academic();
        /*  $studentsList = StudentDetails::select('student_details.id', 'student_details.first_name', 'student_details.last_name')
            ->leftJoin('enrollment_applications as ea', 'ea.student_id', 'student_details.id')
            ->where('ea.academic_id', $_academic->id)
            ->whereNull('ea.is_approved')
            ->where('ea.is_removed', false)->paginate(10); */
        $studentsList = [];
        //$this->studentsList = StudentDetails::select('student_details.id', 'student_details.first_name', 'student_details.last_name')->paginate(10);
        return view('livewire.registrar.enrollment-view', compact('_courses', 'courseLists', '_curriculums', 'studentsList'));
    }
    function searchStudents()
    {
        if ($this->searchInput) {
            $student_detials = new StudentDetails();
            //$this->studentLists = [];
            $this->studentLists = $student_detials->student_search($this->searchInput);
        } else {
            //$this->studentsList = [];
        }
    }
}
