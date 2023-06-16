<?php

namespace App\Http\Livewire\Medical;

use App\Models\ApplicantAccount;
use App\Models\CourseOffer;
use App\Models\MedicalAppointmentSchedule;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ApplicantView extends Component
{
    use WithPagination;
    public $layout;
    public $academic;
    public $searchInput;
    public $selecteCategories = 'waiting_for_scheduled';
    public $selectCourse = 'ALL COURSE';
    public $applicants = [];
    public $selectedCourse = 'ALL COURSE';
    public $selectedCategory = 'WAITING FOR SCHEDULED';

    public function render()
    {
        $courseDashboard = CourseOffer::all();
        $courses = CourseOffer::all();
        $selectContent = array(
            array('waiting for Scheduled', 'waiting_for_scheduled'),
            array('scheduled', 'medical_scheduled'),
            array('waiting for Medical result', 'waiting_for_medical_result'),
            array('passed', 'medical_result_passed'),
            array('pending', 'medical_result_pending'),
            array('failed', 'medical_result_failed')
        );
        $dates = MedicalAppointmentSchedule::orderBy('date', 'asc')->where('is_close', false)->get();
        $this->academic =  request()->query('_academic') ?: $this->academic;

        if ($this->selecteCategories == '') {
            $this->applicants = ApplicantAccount::select('applicant_accounts.*')
                ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
                ->where('applicant_briefings.is_removed', false)
                ->groupBy('applicant_accounts.id')
                ->join('applicant_briefings', 'applicant_briefings.applicant_id', 'applicant_accounts.id')
                ->where('applicant_accounts.is_removed', false)
                ->leftJoin('applicant_medical_appointments as ama', 'ama.applicant_id', 'applicant_accounts.id')
                ->whereNull('ama.applicant_id')->get();
        } else {
            $this->filtered();
        }

        return view('livewire.medical.applicant-view', compact('courseDashboard', 'selectContent', 'dates', 'courses'));
    }

    function searchStudents()
    {
        $this->filtered();
    }
    function categoryChange()
    {
        $this->selectedCategory = strtoupper(str_replace('_', ' ', $this->selecteCategories));
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
    function filtered()
    {
        $this->applicants = [];
        $query =  ApplicantAccount::select('applicant_accounts.*')
            //->where('applicant_accounts.academic_id', base64_decode(request()->query('_academic')))
            ->where('applicant_accounts.academic_id', base64_decode($this->academic))
            ->where('applicant_briefings.is_removed', false)
            ->groupBy('applicant_accounts.id')
            ->join('applicant_briefings', 'applicant_briefings.applicant_id', 'applicant_accounts.id')
            ->where('applicant_accounts.is_removed', false);
        // Sort By Courses
        if ($this->selectCourse != 'ALL COURSE') {
            $query = $query->where('applicant_accounts.course_id', $this->selectCourse);
        }
        if ($this->searchInput != '') {
            $_student = explode(',', $this->searchInput); // Seperate the Sentence
            $_count = count($_student);
            $query = $query->join('applicant_detials', 'applicant_detials.applicant_id', 'applicant_accounts.id'); // Join to Applicant Details
            if ($_count > 1) {
                $query = $query->where('applicant_detials.last_name', 'like', '%' . $_student[0] . '%')
                    ->where('applicant_detials.first_name', 'like', '%' . trim($_student[1]) . '%')
                    ->orderBy('applicant_detials.last_name', 'asc');
            } else {
                $query = $query->where('applicant_detials.last_name', 'like', '%' . $_student[0] . '%')
                    ->orderBy('applicant_detials.last_name', 'asc');
            }
        }



        if ($this->selecteCategories == 'waiting_for_scheduled') {
            $this->applicants = $query->leftJoin('applicant_medical_appointments as ama', 'ama.applicant_id', 'applicant_accounts.id')
                ->whereNull('ama.applicant_id')->get();
        }
        if ($this->selecteCategories == 'medical_scheduled') {
            $this->applicants = $query->join('applicant_medical_appointments as ama', 'ama.applicant_id', 'applicant_briefings.applicant_id')
                ->where('ama.is_removed', false)
                ->where('is_approved', false)
                ->groupBy('applicant_accounts.id')->get();
        }
        if ($this->selecteCategories == 'waiting_for_medical_result') {
            $this->applicants =  $query->join('applicant_medical_appointments as ama', 'ama.applicant_id', 'applicant_briefings.applicant_id')
                ->where('ama.is_removed', false)
                ->where('is_approved', true)->leftJoin('applicant_medical_results', 'applicant_medical_results.applicant_id', 'ama.applicant_id')
                ->whereNull('applicant_medical_results.applicant_id')
                ->groupBy('applicant_accounts.id')->get();
        }
        if ($this->selecteCategories == 'medical_result_passed') {
            $this->applicants =  $query->join('applicant_medical_appointments as ama', 'ama.applicant_id', 'applicant_briefings.applicant_id')
                ->where('ama.is_removed', false)
                ->where('is_approved', true)
                ->join('applicant_medical_results', 'applicant_medical_results.applicant_id', 'applicant_briefings.applicant_id')
                ->where('applicant_medical_results.is_fit', true)
                ->where('applicant_medical_results.is_removed', false)
                ->groupBy('applicant_accounts.id')->get();
        }
        if ($this->selecteCategories == 'medical_result_pending') {
            $this->applicants =  $query->join('applicant_medical_appointments as ama', 'ama.applicant_id', 'applicant_briefings.applicant_id')
                ->where('ama.is_removed', false)
                ->where('is_approved', true)
                ->join('applicant_medical_results', 'applicant_medical_results.applicant_id', 'applicant_briefings.applicant_id')
                ->where('applicant_medical_results.is_pending', false)
                ->where('applicant_medical_results.is_removed', false)
                ->groupBy('applicant_accounts.id')->get();
        }
        if ($this->selecteCategories == 'medical_result_failed') {
            $this->applicants =  $query->join('applicant_medical_appointments as ama', 'ama.applicant_id', 'applicant_briefings.applicant_id')
                ->where('ama.is_removed', false)
                ->where('is_approved', true)
                ->join('applicant_medical_results', 'applicant_medical_results.applicant_id', 'applicant_briefings.applicant_id')
                ->where('applicant_medical_results.is_fit', 2)
                ->where('applicant_medical_results.is_removed', false)
                ->groupBy('applicant_accounts.id')->get();
        }
    }
}
