<?php

namespace App\Http\Livewire\Medical;

use App\Http\Controllers\Controller;
use App\Mail\ApplicantEmail;
use App\Models\ApplicantAccount;
use App\Models\ApplicantMedicalResult;
use App\Models\CourseOffer;
use App\Models\MedicalAppointmentSchedule;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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
    public $applicantID;
    public $remarks;
    public $remarksPending;
    protected $listeners = ['medicalResult'];
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
    function modalData($data)
    {
        $this->applicantID  = $data;
    }
    function setApplicantId()
    {
        $this->applicantID = "hello";
    }
    function storeFailMedical()
    {
        //$this->medical_result(base64_encode(2));
    }
    function storePendingMedical()
    {
        $this->medical_result(false);
    }
    function medical_result($result)
    {
        try {
            $_applicant = ApplicantAccount::find(base64_decode($this->applicantID));
            if ($result) {
                $_details = array('applicant_id' => base64_decode($this->applicantID), 'is_fit' => base64_decode($result), 'remarks' => $this->remarks);
            } else {
                $_details = array('applicant_id' => base64_decode($this->applicantID), 'is_pending' => base64_decode($result), 'remarks' => $this->remarks);
            }
            $_medical_result = ApplicantMedicalResult::where('applicant_id', $_applicant->id)->where('is_removed', false)->first();
            if ($_medical_result) {
                $_medical_result->is_removed = true;
                $_medical_result->save();
                ApplicantMedicalResult::create($_details);
            } else {
                ApplicantMedicalResult::create($_details);
            }
            $_email_model = new ApplicantEmail();
            $_email = $_applicant->email;
            //$_email = 'p.banting@bma.edu.ph';
            /* if ($result) {
                if (base64_decode($result) == 1) {
                    // Email Passed
                    Mail::to($_email)->bcc('p.banting@bma.edu.ph')->send($_email_model->medical_result_passed($_applicant));
                } else {
                    // Email Failed
                    Mail::to($_email)->bcc('p.banting@bma.edu.ph')->send($_email_model->medical_result_passed($_applicant));
                }
            } else {
                //Email "Pending";
                Mail::to($_email)->bcc('p.banting@bma.edu.ph')->send($_email_model->medical_result_passed($_applicant));
            } */
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
    function medicalResult($applicant,  $result, $remarks)
    {
        try {
            $_applicant = ApplicantAccount::find($applicant);
            switch ($result) {
                case 1:
                    // Medical Passed
                    $_details = array('applicant_id' => $applicant, 'is_fit' => 1, 'remarks' => null);
                    break;
                case 2:
                    // Medical Failed
                    $_details = array('applicant_id' => $applicant, 'is_fit' => 2, 'remarks' => $remarks);
                    break;
                case 3:
                    // Medical Passed
                    $_details = array('applicant_id' => $applicant, 'is_pending' => 0, 'remarks' => $remarks);
                    break;
                default:
                    # code...
                    break;
            }
            $medical_result = ApplicantMedicalResult::where('applicant_id', $_applicant->id)->where('is_removed', false)->first();
            if ($medical_result) {
                $medical_result->is_removed = true;
                $medical_result->save();
                ApplicantMedicalResult::create($_details);
            } else {
                ApplicantMedicalResult::create($_details);
            }
            $_email_model = new ApplicantEmail();
            $_email = $_applicant->email;
            //$_email = 'p.banting@bma.edu.ph';
            if ($result) {
                if (base64_decode($result) == 1) {
                    // Email Passed
                    Mail::to($_email)->bcc('p.banting@bma.edu.ph')->send($_email_model->medical_result_passed($_applicant));
                } else {
                    // Email Failed
                    Mail::to($_email)->bcc('p.banting@bma.edu.ph')->send($_email_model->medical_result_passed($_applicant));
                }
            } else {
                //Email "Pending";
                Mail::to($_email)->bcc('p.banting@bma.edu.ph')->send($_email_model->medical_result_passed($_applicant));
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
                'type' => 'danger',
            ]);
            $controller = new Controller();
            $controller->debugTracker($error);
        }
    }
    function medicalResultDialogBox($applicant, $result, $title)
    {
        $this->dispatchBrowserEvent('swal:confirmInput', [
            'title' => $title,
            'text' => '',
            'type' => 'info',
            'confirmButtonText' => 'Submit',
            'cancelButtonText' => 'Cancel',
            'method' => 'medicalResult',
            'input' => 'text',
            'inputPlaceholder' => 'Enter a remarks',
            'params' => ['applicant' => $applicant, 'result' => $result],
        ]);
    }
    function medicalResult2($applicant, $result, $remarks)
    {
        $this->dispatchBrowserEvent('swal:alert', [
            'title' => 'Complete!',
            'text' => $applicant . "-" . $result . '-' . $remarks,
            'type' => 'success',
        ]);
    }
}
