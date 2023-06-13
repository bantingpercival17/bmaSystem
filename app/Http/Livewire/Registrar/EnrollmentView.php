<?php

namespace App\Http\Livewire\Registrar;

use App\Models\CourseOffer;
use App\Models\Curriculum;
use App\Models\StudentDetails;
use Livewire\Component;

class EnrollmentView extends Component
{
    //public $students = [];
    public function render()
    {
        $_courses = CourseOffer::all();
        $_curriculums = Curriculum::where('is_removed', false)->get();
        $student_detials = new StudentDetails();
        $students = $student_detials->enrollment_application_list();
            #$_students = $_request->_student ? $_student_detials->student_search($_request->_student) : $_student_detials->enrollment_application_list();
            #$_students = $_request->_course ? $_student_detials->enrollment_application_list_view_course($_request->_course) : $_students;
            
        return view('livewire.registrar.enrollment-view', compact('_courses','_curriculums','students'));
    }
}
