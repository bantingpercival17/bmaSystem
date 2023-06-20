<?php

namespace App\Http\Livewire\Medical;

use App\Http\Controllers\Controller;
use App\Models\CourseOffer;
use App\Models\MedicalAppointmentSchedule;
use App\Models\Section;
use App\Models\StudentDetails;
use App\Models\StudentMedicalResult;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class StudentView extends Component
{
    public $dataLists = [];
    public $searchInput;
    public $academic;
    public $selectCourse = 'ALL COURSE';
    public $selectedCourse = '';
    public $selectCategories = 'waiting_for_medical_result';
    public $selectedCategory = '';
    public $levels = [1, 2, 3, 4, 11, 12];
    public $selectLevel = 'ALL LEVELS';
    public $sections = [];
    public $selectSection = 'ALL SECTION';
    public $showData;
    protected $listeners = ['medicalResult'];
    public function render()
    {
        $courses = CourseOffer::all();
        $courseDashboard = $courses;
        $this->academic =  request()->query('_academic') ?: $this->academic;
        $selectContent = array(
            array('waiting for Medical result', 'waiting_for_medical_result'),
            array('passed', 'medical_result_passed'),
            array('pending', 'medical_result_pending'),
            array('failed', 'medical_result_failed')
        );
        $this->filterData();
        $this->sectionFilteration();
        return view('livewire.medical.student-view', compact('courseDashboard', 'selectContent', 'courses'));
    }
    function chooseCourse()
    {
    }

    function filterData()
    {
        $students = StudentDetails::select('student_details.id', 'student_details.first_name', 'student_details.last_name', 'student_details.middle_initial', 'enrollment_assessments.id as enrollment_id')
            ->join('enrollment_assessments', 'enrollment_assessments.student_id', 'student_details.id')
            ->where('enrollment_assessments.academic_id',  base64_decode($this->academic))
            ->where('enrollment_assessments.is_removed', false);
        // Filtering Student by Course
        if ($this->selectCourse != 'ALL COURSE') {
            $students = $students->where('enrollment_assessments.course_id', $this->selectCourse);
        }
        // Filtering Student by Course
        if ($this->selectLevel != 'ALL LEVELS') {
            $students = $students->where('enrollment_assessments.year_level', $this->selectLevel);
        }
        // Filtering Student by Section
        if ($this->selectSection != 'ALL SECTION') {
            $students = $students->join('student_sections', 'student_sections.student_id', 'student_details.id')
                ->where('student_sections.is_removed', false)
                ->where('student_sections.section_id', $this->selectSection);
        }
        switch ($this->selectCategories) {
            case 'waiting_for_medical_result':
                $students = $students->leftJoin('student_medical_results', 'student_medical_results.enrollment_id', 'enrollment_assessments.id')
                    ->whereNull('student_medical_results.enrollment_id');
                break;
            case 'medical_result_passed':
                $students = $students->join('student_medical_results', 'student_medical_results.enrollment_id', 'enrollment_assessments.id')
                    ->where('student_medical_results.is_removed', false)
                    ->where('student_medical_results.is_fit', 1);
                break;
            case 'medical_result_pending':
                $students = $students->join('student_medical_results', 'student_medical_results.enrollment_id', 'enrollment_assessments.id')
                    ->where('student_medical_results.is_removed', false)
                    ->where('student_medical_results.is_pending', 0);
                break;
            case 'medical_result_failed':
                $students = $students->join('student_medical_results', 'student_medical_results.enrollment_id', 'enrollment_assessments.id')
                    ->where('student_medical_results.is_removed', false)
                    ->where('student_medical_results.is_fit', 2);
                break;
            default:
                # code...
                break;
        }
        $this->dataLists = $students->orderBy('student_details.last_name', 'asc')->get();
    }
    function sectionFilteration()
    {
        // Set the Section List per Academic Year
        $section = Section::where('academic_id', base64_decode($this->academic));
        // Filtering by Course
        if ($this->selectCourse != 'ALL COURSE') {
            $section = $section->where('course_id', $this->selectCourse);
        }
        // Filtering by Year Level
        if ($this->selectLevel != 'ALL LEVELS') {
            $section = $section->where('year_level', 'like', '%' . $this->selectLevel . '%');
        }
        //$this->dataLists = $section->get();
        if ($this->selectCourse != 'ALL COURSE' && $this->selectLevel != 'ALL LEVELS') {
            $this->sections = $section->get();
        } else {
            $this->sections = [];
        }
    }
    function medicalResult($student, $enrollment, $result, $remarks)
    {
        try {
            $_student = StudentDetails::find($student);
            if ($result) {
                $_details = array('student_id' => $student, 'is_fit' => $result, 'remarks' => $result == null ?: base64_encode($remarks), 'staff_id' => Auth::user()->staff->id, 'enrollment_id' => $enrollment);
            } else {
                $_details = array('student_id' => $student, 'is_pending' => 0, 'remarks' => base64_encode($remarks), 'staff_id' => Auth::user()->staff->id, 'enrollment_id' => $enrollment);
            }
            $_medical_result = StudentMedicalResult::where('student_id', $_student->id)->where('enrollment_id', $enrollment)->where('is_removed', false)->first();
            //$this->showData = $result;
            if ($_medical_result) {
                $_medical_result->is_removed = true;
                $_medical_result->save();
                StudentMedicalResult::create($_details);
            } else {
                StudentMedicalResult::create($_details);
            }
            $this->dispatchBrowserEvent('swal:alert', [
                'title' => 'Complete!',
                'text' => 'Successfully Transact',
                'type' => 'success',
            ]);
        } catch (Exception $error) {
            $this->dispatchBrowserEvent('swal:alert', [
                'title' => 'Error!',
                'text' =>  $error->getMessage(),
                'type' => '',
            ]);
            $controller = new Controller();
            $controller->debugTracker($error);
        }
    }
    function medicalResultDialogBox($student, $enrollment, $result, $title)
    {
        $this->dispatchBrowserEvent('swal:confirmInputStudent', [
            'title' => $title,
            'text' => '',
            'type' => 'info',
            'confirmButtonText' => 'Submit',
            'cancelButtonText' => 'Cancel',
            'method' => 'medicalResult',
            'input' => 'text',
            'inputPlaceholder' => 'Enter a remarks',
            'params' => ['student' => $student, 'result' => $result, 'enrollment' => $enrollment],
        ]);
    }
}
