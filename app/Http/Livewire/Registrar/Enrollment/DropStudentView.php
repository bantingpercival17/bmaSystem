<?php

namespace App\Http\Livewire\Registrar\Enrollment;

use App\Models\CourseOffer;
use App\Models\StudentDetails;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DropStudentView extends Component
{
    public $searchInput;
    public $academic;
    public $selectCourse = 'ALL COURSE';
    public $selectedCourse = 'ALL COURSE';
    public $selectCategories = 'waiting_for_medical_result';
    public $selectedCategory = '';
    public $levels = [1, 2, 3, 4, 11, 12];
    public $selectLevel = 'ALL LEVELS';
    public $sections = [];
    public $selectSection = 'ALL SECTION';
    public $showData;
    public function render()
    {
        $courses = CourseOffer::orderBy('id', 'desc')->get();
        $selectCourses = $courses;
        $_academic = Auth::user()->staff->current_academic();
        $this->academic =  request()->query('_academic') ?: $this->academic;
        $academic = base64_decode($this->academic) ?: $_academic->id;
        $dataLists = $this->filterData($academic);
        return view('livewire.registrar.enrollment.drop-student-view', compact('selectCourses', 'courses', 'dataLists'));
    }
    function categoryCourse()
    {
        $course = 'ALL COURSE';
        if ($this->selectCourse != 'ALL COURSE') {
            $course = CourseOffer::find($this->selectCourse);
            $course = $course->course_name;
        }
        $this->selectedCourse = strtoupper($course);
    }

    function filterData($academic)
    {
        $query = StudentDetails::select('student_details.*')
            ->join('enrollment_assessments', 'enrollment_assessments.student_id', 'student_details.id')
            ->join('student_cancellations', 'student_cancellations.enrollment_id', 'enrollment_assessments.id')
            ->where('enrollment_assessments.academic_id', $academic)
            ->groupBy('enrollment_assessments.id')
            ->orderBy('student_cancellations.created_at', 'DESC');
        if ($this->searchInput != '') {
            $_student = explode(',', $this->searchInput); // Seperate the Sentence
            $_count = count($_student);
            if ($_count > 1) {
                $query = $query->where('student_details.last_name', 'like', '%' . $_student[0] . '%')
                    ->where('student_details.first_name', 'like', '%' . trim($_student[1]) . '%')
                    ->orderBy('student_details.last_name', 'asc');
            } else {
                $query = $query->where('student_details.last_name', 'like', '%' . $_student[0] . '%')
                    ->orderBy('student_details.last_name', 'asc');
            }
        }
        if ($this->selectCourse != 'ALL COURSE') {
            $query = $query->where('enrollment_assessments.course_id', $this->selectCourse);
        }
        // Filtering Student by Course
        if ($this->selectLevel != 'ALL LEVELS') {
            $query = $query->where('enrollment_assessments.year_level', $this->selectLevel);
        }
        return $query->paginate(20);
    }
}
