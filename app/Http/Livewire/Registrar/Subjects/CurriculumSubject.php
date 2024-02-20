<?php

namespace App\Http\Livewire\Registrar\Subjects;

use App\Models\AcademicYear;
use App\Models\CourseOffer;
use App\Models\Curriculum;
use App\Models\CurriculumSubject as ModelsCurriculumSubject;
use Illuminate\Support\Facades\Auth;
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
        $academicDetails = AcademicYear::find(base64_decode($this->academic));
        $this->course = $this->setCourse();
        $curriculum = $this->setCurriculum();
        $courseDetails = $this->setCourse();
        $subjectLists = $this->viewData($this->course, $curriculum);
        return view('livewire.registrar.subjects.curriculum-subject', compact('courseLists', 'curriculumLists', 'subjectLists', 'curriculum', 'courseDetails'));
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
    function setCourse()
    {
        $course = CourseOffer::find(1);
        if ($this->selectCourse !== null) {
            $course = CourseOffer::find($this->selectCourse);
        }
        $this->selectedCourse = strtoupper($course->course_name);
        return $course;
    }
    function setCurriculum()
    {
        $data = Curriculum::where('is_removed', false)
            ->orderBy('id', 'desc')
            ->first();
        if ($this->selectCurriculum) {
            $data = Curriculum::find($this->selectCurriculum);
        }
        $this->selectedCurriculum = strtoupper($data->curriculum_name);
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
    function downloadFiles()
    {
        // Exporting Program of Studies Pre Curriculum
    }
    function viewData($course, $curriculum)
    {
        $subjectLists = [];
        $levels = [11, 12];
        $levels = $course->id != 3 ? [4, 3, 2, 1] : $levels;
        $semester = ['First Semester', 'Second Semester'];
        foreach ($levels as $key => $level) {
            $first_semester = $this->curriculum_subject($course, $curriculum, $semester[0], $level);
            $second_semester = $this->curriculum_subject($course, $curriculum, $semester[1], $level);
            $subject_lists = compact('first_semester', 'second_semester');
            $level_name =  strtoupper(Auth::user()->staff->convert_year_level($level));
            $subjectLists[] = compact('level', 'level_name', 'semester', 'subject_lists');
        }
        return $subjectLists;
    }
    function curriculum_subject($course, $curriculum, $semester, $value)
    {
        return  ModelsCurriculumSubject::with('subject')
            ->where('curriculum_subjects.course_id', $course->id)
            ->where('curriculum_subjects.curriculum_id', $curriculum->id)
            ->where('curriculum_subjects.year_level', $value)
            ->where('curriculum_subjects.semester', $semester)
            ->where('curriculum_subjects.is_removed', false)
            ->orderBy('curriculum_subjects.id', 'asc')->get();
    }
}
