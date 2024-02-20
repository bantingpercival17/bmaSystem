<?php

namespace App\Http\Livewire\Registrar\Subjects;

use App\Models\AcademicYear;
use App\Models\CourseOffer;
use App\Models\Curriculum;
use Livewire\Component;

class CurriculumSubject extends Component
{
    public $academic = null;
    public $course;
    public $selectedCourse = null;
    public $selectedCurriculum = null;
    public $selectCourse = null;
    public $selectCurriculum = null;
    public function render()
    {
        $courseLists = CourseOffer::all();
        $curriculumLists = Curriculum::where('is_removed', false)->orderBy('id', 'desc')->get();
        $this->academic = $this->setAcademicYear();
        $this->course = request()->query('_course') ? base64_decode(request()->query('_course')) : 1;
        if ($this->selectCourse == null) {
            $course = CourseOffer::find($this->course);
            $this->selectedCourse = $course->course_name;
            $this->selectCourse = $this->course;
        }
        $curriculum = Curriculum::where('is_removed', false)
            ->orderBy('id', 'desc')
            ->first();
        if ($this->selectCurriculum == null) {
            $this->selectedCurriculum = $curriculum->curriculum_name;
        }
        $curriculum = $this->selectCurriculum == null ? $curriculum->id : $this->selectCurriculum;
        $curriculum = Curriculum::find($curriculum);
        $courseDetails = CourseOffer::find($this->course);
        $level = $this->selectCourse == 3 ? [11, 12] : [4, 3, 2, 1];
        $layoutDetails = array('course_level' => $level, 'semester' => ['First Semester', 'Second Semester']);
        return view('livewire.registrar.subjects.curriculum-subject', compact('courseLists', 'curriculumLists', 'layoutDetails', 'curriculum', 'courseDetails'));
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
    function categoryCourse()
    {
        if ($this->selectedCourse) {
            $data = CourseOffer::find($this->selectCourse);
            $data = $data->course_name;
            $this->selectedCourse = strtoupper($data);
        }
    }
    function categoryCurriculum()
    {
        if ($this->selectedCurriculum) {
            $data = Curriculum::find($this->selectCurriculum);
            $data = $data->curriculum_name;
            $this->selectedCurriculum = strtoupper($data);
        }
    }
    function downloadFiles(){
        
    }
}
