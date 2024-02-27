<?php

namespace App\Http\Livewire\DepartmentHead\Comprehensive;

use App\Models\CourseOffer;
use Livewire\Component;

class ExaminationView extends Component
{
    public function render()
    {
        $courses = CourseOffer::where('id', '!=', '3')->get();
        return view('livewire.department-head.comprehensive.examination-view', compact('courses'));
    }
}
