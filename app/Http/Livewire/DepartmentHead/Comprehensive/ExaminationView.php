<?php

namespace App\Http\Livewire\DepartmentHead\Comprehensive;

use App\Models\ComprehensiveExaminationExaminee;
use App\Models\CourseOffer;
use Livewire\Component;

class ExaminationView extends Component
{
    public $addExaminationContent = false;
    public $examinationContent = true;
    public function render()
    {
        $examinees = ComprehensiveExaminationExaminee::where('is_removed', false)->orderBy('id', 'desc')->get();
        $courses = CourseOffer::where('id', '!=', '3')->get();
        return view('livewire.department-head.comprehensive.examination-view', compact('courses', 'examinees'));
    }
    function openAddContent()
    {
        $this->addExaminationContent = true;
    }
}
