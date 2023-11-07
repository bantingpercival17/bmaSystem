<?php

namespace App\Http\Livewire\Dean;

use App\Models\AcademicYear;
use App\Models\CourseOffer;
use App\Models\Section;
use Livewire\Component;

class GradeSubmissionView extends Component
{
    public $academic;
    public $selectCourse = 'ALL COURSE';
    public $levels = [1, 2, 3, 4, 11, 12];
    public $selectLevel = 'ALL LEVELS';

    public $pageView = false;
    public $sectionData;
    public $showModal = false;
    public $documentLink = null;
    public function render()
    {
        # Get the academic year
        $this->academic = $this->academicValue();
        $courses = CourseOffer::all();
        $sectionList = $this->sectionList($this->academic, $this->selectCourse, $this->selectLevel);
        $sectionDetails = [];
        $this->sectionData = $this->sectionID();
        if ($this->sectionData) {
            $sectionDetails = Section::find(base64_decode($this->sectionData));
            $this->pageView = true;
        }
        return view('livewire.dean.grade-submission-view', compact('courses', 'sectionList', 'sectionDetails'));
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
    function sectionID()
    {
        $data = $this->sectionData;
        if (request()->query('section')) {
            $data = request()->query('section') ?: $this->academic;
        }
        return $data;
    }
    function sectionList($academic, $course, $level)
    {
        $section = Section::where('academic_id', base64_decode($academic))
            ->where('is_removed', false)
            ->where('course_id', '!=', 3)
            ->orderBy('section_name', 'asc');
        if ($course != 'ALL COURSE') {
            $section = $section->where('course_id', $course);
        }
        if ($level != 'ALL LEVELS') {
            $section = $section->where('year_level', 'like', '%' . $level . '%');
        }
        return $section->get();
    }
    function showDocuments($data, $period, $form)
    {
        $link = route('dean.grade-preview-report') . '?class=' . base64_encode($data) . '&period=' . $period . '&form=' . $form;
        $this->showModal = true;
        $this->documentLink = null;
        $this->documentLink = $link;
    }
    function hideDocuments()
    {
        $this->showModal = false;
        $this->documentLink = null;
    }
}
