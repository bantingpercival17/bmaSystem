<?php

namespace App\Http\Livewire\Registrar\Enrollment;

use App\Models\AcademicYear;
use App\Models\CourseOffer;
use App\Models\EnrollmentAssessment;
use App\Models\StudentCancellation;
use App\Models\StudentDetails;
use App\Models\StudentSection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class EnrolledStudentView extends Component
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
    public $enrollmentData;
    public $enrollmentDate;
    public $enrollmentReason;
    public $enrollmentType = 'dropped';
    public $isOpen;
    public $isLoading = false;
    public function render()
    {
        $courses = CourseOffer::orderBy('id', 'desc')->get();
        $selectCourses = $courses;
        $this->academic = $this->academicValue();
        $dataLists = $this->filterData(base64_decode($this->academic));
        return view('livewire.registrar.enrollment.enrolled-student-view', compact('selectCourses', 'courses', 'dataLists'));
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
        Cache::put('academic', $data, 60);
        return $data;
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
            ->join('payment_assessments', 'enrollment_assessments.id', 'payment_assessments.enrollment_id')
            ->join('payment_transactions', 'payment_assessments.id', 'payment_transactions.assessment_id')
            ->where('enrollment_assessments.academic_id', $academic)
            ->groupBy('enrollment_assessments.id')
            ->orderBy('payment_transactions.created_at', 'DESC');
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
    function enrollment_cancellation($data)
    {
        $this->isLoading = true;
        $this->enrollmentData = EnrollmentAssessment::find($data);
        $this->isOpen = $data;
        $this->isLoading = false;
    }
    function enrollmentCancellationStore()
    {
        $data = array(
            'enrollment_id' => $this->enrollmentData->id,
            'type_of_cancellations' => $this->enrollmentType,
            'date_of_cancellation' => $this->enrollmentDate,
            'cancellation_evidence' => '',
            'staff_id' => auth()->user()->staff->id
        );
        if ($this->enrollmentType != 'dropped') {
            StudentSection::where('enrollment_id', $this->enrollmentData->id)->update(['is_removed' => true]);
        }
        StudentCancellation::create($data);
        $this->isOpen = '';
        $this->enrollmentType = 'dropped';
        $this->enrollmentDate = '';
        $this->dispatchBrowserEvent('swal:alert', [
            'title' => 'Enrollment Cancellation!',
            'text' => 'Successfully Transact',
            'type' => 'success',
        ]);
        /*  $this->dispatchBrowserEvent('swal:alert', [
            'title' => 'Enrollment Cancellation!',
            'text' => 'Successfully Transact!.',
            'type' => 'success',
        ]); */
    }
}
