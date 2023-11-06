<?php

namespace App\Http\Livewire\Dean;

use App\Models\AcademicYear;
use App\Models\CourseOffer;
use Livewire\Component;

class GradeSubmissionView extends Component
{
    public $academic;
    public $selectCourse = 'ALL COURSE';
    public $selectedCourse = '';
    public $levels = [1, 2, 3, 4, 11, 12];
    public $selectLevel = 'ALL LEVELS';
    public function render()
    {
        # Get the academic year
        $this->academic = $this->academicValue();
        $courses = CourseOffer::all();
        return view('livewire.dean.grade-submission-view',compact( 'courses'));
    }
    function academicValue()
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

}
