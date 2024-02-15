<?php

namespace App\Http\Livewire\SummaryComponents;

use App\Models\CourseOffer;
use App\Models\Curriculum;
use Livewire\Component;

class EnrollmentSummaryOverview extends Component
{
    public $yearLevelList = [11, 12, 4, 3, 2, 1];
    public function render()
    {
        $courseLists = CourseOffer::all();
        $courses = CourseOffer::all();
        $_curriculums = Curriculum::where('is_removed', false)->get();
        return view('livewire.summary-components.enrollment-summary-overview', compact('courses', 'courseLists', '_curriculums'));
    }
}
