<?php

namespace App\Http\Livewire\Teacher\CourseSyllabus;

use App\Models\CourseSyllabus;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class SubjectSyllabusView extends Component
{
    public $course_syllabus;
    public $activeCard = 'subject-information';
    public function render()
    {
        $this->course_syllabus = request()->query('course_syllabus') ? CourseSyllabus::find(base64_decode(request()->query('course_syllabus'))) : $this->course_syllabus;
        if (Cache::has('menu')) {
            $this->activeCard = Cache::get('menu');
        }
        return view('livewire.teacher.course-syllabus.subject-syllabus-view');
    }
    function swtchTab($data)
    {
        $this->activeCard = $data;
        Cache::put('menu', $data, 120);
    }
}
