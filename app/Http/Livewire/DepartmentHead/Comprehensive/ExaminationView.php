<?php

namespace App\Http\Livewire\DepartmentHead\Comprehensive;

use App\Models\ComprehensiveExaminationExaminee;
use App\Models\CourseOffer;
use Livewire\Component;

class ExaminationView extends Component
{
    public $addExaminationContent = false;
    public $examinationContent = true;
    public $searchInput = null;
    public $category = 'for_examination_schedule';
    public $course = 0;
    public function render()
    {
        $examinees = $this->dataFilter($this->searchInput, $this->category, $this->course);
        $courses = CourseOffer::where('id', '!=', '3')->get();
        $categories = array('for_examination_schedule', 'for_examination', 'examination_complete');
        return view('livewire.department-head.comprehensive.examination-view', compact('courses', 'examinees', 'categories'));
    }
    function openAddContent()
    {
        $this->addExaminationContent = true;
    }
    function dataFilter($student, $categories, $course)
    {
        $examinees = ComprehensiveExaminationExaminee::select('comprehensive_examination_examinees.*')->where('comprehensive_examination_examinees.is_removed', false)
            ->orderBy('comprehensive_examination_examinees.id', 'desc');
        if ($categories == 'for_examination_schedule') {
            $examinees = $examinees->leftJoin('comprehensive_examination_scheduleds', 'comprehensive_examination_scheduleds.examinee_id', 'comprehensive_examination_examinees.id')
                //->where('comprehensive_examination_scheduleds.is_removed', false)
                ->where('comprehensive_examination_scheduleds.examinee_id', null);
        }
        if ($categories == 'for_examination') {
            $examinees = $examinees->join('comprehensive_examination_scheduleds', 'comprehensive_examination_scheduleds.examinee_id', 'comprehensive_examination_examinees.id')
                ->where('comprehensive_examination_scheduleds.is_removed', false);
        }
        if ($course !== 0) {
            $examinees = $examinees->join('enrollment_assessments', 'enrollment_assessments.student_id', 'comprehensive_examination_examinees.student_id')
                ->groupBy('enrollment_assessments.student_id')->where('enrollment_assessments.course_id', $course);
        }
        return $examinees->get();
    }
}
