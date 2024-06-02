<?php

namespace App\Http\Livewire\Medical;

use App\Http\Controllers\Controller;
use App\Mail\ApplicantEmail;
use App\Models\AcademicYear;
use App\Models\ApplicantAccount;
use App\Models\ApplicantMedicalAppointment;
use App\Models\ApplicantMedicalResult;
use App\Models\CourseOffer;
use App\Models\MedicalAppointmentSchedule;
use App\Report\MedicalReport;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
    public $applicantAccountTable;
    public $tblDocuments;
    public $tblApplicantDetails;
    public $tblApplicantDocuments;
    public $tblApplicantNotQualifieds;
    public $tblApplicantPayment;
    public $tblApplicantAlumia;
    public $tblApplicantExamination;
    public $tblApplicantExaminationAnswer;
    public $tblApplicantOrientationScheduled;
    public $tblApplicantOrientation;
    public function __construct()
    {
        $this->applicantAccountTable = env('DB_DATABASE') . '.applicant_accounts';
        $this->tblDocuments = env('DB_DATABASE') . '.documents';
        $this->tblApplicantDetails = env('DB_DATABASE_SECOND') . '.applicant_detials';
        $this->tblApplicantDocuments = env('DB_DATABASE_SECOND') . '.applicant_documents';
        $this->tblApplicantNotQualifieds = env('DB_DATABASE_SECOND') . '.applicant_not_qualifieds';
        $this->tblApplicantPayment = env('DB_DATABASE_SECOND') . '.applicant_payments';
        $this->tblApplicantAlumia = env('DB_DATABASE_SECOND') . '.applicant_alumnias';
        $this->tblApplicantExamination = env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations';
        $this->tblApplicantExaminationAnswer = env('DB_DATABASE_SECOND') . '.applicant_examination_answers';
        $this->tblApplicantOrientationScheduled = env('DB_DATABASE_SECOND') . '.applicant_briefing_schedules';
    }
    public function render()
    {
        $courseDashboard = CourseOffer::all();
        $courses = CourseOffer::all();
        $selectContent = array(
            array('shs Alumia For Medical Schedule', 'shs_alumia_for_medical_schedule'),
            array('for medical Schedule', 'waiting_for_scheduled'),
            //array('scheduled', 'medical_scheduled'),
            array('waiting for Medical result', 'waiting_for_medical_result'),
            array('passed', 'medical_result_passed'),
            array('pending', 'medical_result_pending'),
            array('failed', 'medical_result_failed')
        );
        $dates = MedicalAppointmentSchedule::orderBy('date', 'asc')->where('is_close', false)->get();
        $this->academic = $this->academicValue();

        /* if ($this->selecteCategories == '') {
            $this->applicants = ApplicantAccount::select('applicant_accounts.*')
                ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
                ->where('applicant_accounts.is_removed', false)
                ->leftJoin(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', 'applicant_accounts.id')
                ->groupBy('applicant_accounts.id')
                ->whereNull('ama.applicant_id')->get();
        } else {
            $this->filtered();
        } */
        $this->applicants = $this->filter_data($this->selecteCategories, $this->academic, $this->selectCourse, $this->searchInput);
        return view('livewire.medical.applicant-view', compact('courseDashboard', 'selectContent', 'dates', 'courses'));
    }

    function academicValue()
    {
        if (request()->query('_academic')) {
            return request()->query('_academic');
        } else {
            if ($this->academic === null) {
                $activeAcademic = AcademicYear::where('is_active', 1)->first();
                if ($activeAcademic) {
                    return base64_encode($activeAcademic->id);
                }
            } else {
                return $this->academic;
            }
        }
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
    function filter_category($query, $search, $category)
    {


        // Search Sorting
        if ($search !== '') {
            if ($category === 'created_accounts') {
                return $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                })->orderBy('created_at', 'desc');
            } else {
                $tblApplicantDetails = env('DB_DATABASE_SECOND') . '.applicant_detials';
                if (($category !== 'total_registrants' && $category !== 'registered_applicants_v1')) {
                    $query = $query->join($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', '=', 'applicant_accounts.id');
                }


                $query = $query->where(function ($query) use ($search, $tblApplicantDetails) {
                    $query->where($tblApplicantDetails . '.last_name', 'like', '%' . explode(',', $search)[0] . '%');
                    if (strpos($search, ',') !== false) {
                        $query->where($tblApplicantDetails . '.first_name', 'like', '%' . trim(explode(',', $search)[1]) . '%');
                    }
                });

                return ($category !== 'total_registrants' && $category !== 'registered_applicants_v1') ?
                    $query->orderBy($tblApplicantDetails . '.last_name', 'asc') :
                    $query;
            }
        }

        return $query;
    }
    function filter_data($category, $academic, $course, $search)
    {
        $tblApplicantExamination = env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations';
        $tblApplicantExaminationResult = env('DB_DATABASE_SECOND') . '.applicant_entrance_examination_results';
        $applicantAccountTable = env('DB_DATABASE') . '.applicant_accounts';
        $tblDocuments = env('DB_DATABASE') . '.documents';
        $tblApplicantDetails = env('DB_DATABASE_SECOND') . '.applicant_detials';
        $tblApplicantDocuments = env('DB_DATABASE_SECOND') . '.applicant_documents';
        $tblApplicantNotQualifieds =  env('DB_DATABASE_SECOND') . '.applicant_not_qualifieds';
        $tblApplicantPayment = env('DB_DATABASE_SECOND') . '.applicant_payments';
        $tblApplicantAlumia = env('DB_DATABASE_SECOND') . '.applicant_alumnias';
        $tblApplicantExamination = env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations';
        $tblApplicantMedicalScheduled = env('DB_DATABASE_SECOND') . '.applicant_medical_appointments';
        $tblApplicantMedicalResult = env('DB_DATABASE_SECOND') . '.applicant_medical_results';
        $dataList = ApplicantAccount::select('applicant_accounts.*')
            ->where('applicant_accounts.is_removed', false)
            ->where('applicant_accounts.academic_id', base64_decode($academic));
        if ($course != 'ALL COURSE') {
            $dataList = $dataList->where('applicant_accounts.course_id', $course);
        }
        if ($category == 'waiting_for_scheduled') {
            $dataList->join($tblApplicantExamination, $tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                ->join($tblApplicantExaminationResult, $tblApplicantExaminationResult . '.examination_id', $tblApplicantExamination . '.id')
                ->where($tblApplicantExamination . '.is_removed', false)
                ->where($tblApplicantExamination . '.is_finish', true)
                ->where($tblApplicantExaminationResult . '.result', true)
                ->leftJoin($tblApplicantMedicalScheduled, $tblApplicantMedicalScheduled . '.applicant_id', 'applicant_accounts.id')
                ->whereNull($tblApplicantMedicalScheduled . '.applicant_id')
                ->orderBy($tblApplicantExamination . '.examination_start', 'desc')
                ->groupBy('applicant_accounts.id');
        } elseif ($category == 'shs_alumia_for_medical_schedule') {
            $dataList->join($tblApplicantAlumia, $tblApplicantAlumia . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantAlumia . '.is_removed', false)
                ->orderBy('applicant_accounts.created_at', 'desc')
                ->leftJoin($tblApplicantMedicalScheduled, $tblApplicantMedicalScheduled . '.applicant_id', 'applicant_accounts.id')
                ->whereNull($tblApplicantMedicalScheduled . '.applicant_id')
                ->groupBy('applicant_accounts.id');
        } elseif ($category == 'waiting_for_medical_result') {
            $dataList->join($tblApplicantMedicalScheduled, $tblApplicantMedicalScheduled . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantMedicalScheduled . '.is_removed', false)
                ->leftJoin($tblApplicantMedicalResult, $tblApplicantMedicalResult . '.applicant_id', 'applicant_accounts.id')
                ->whereNull($tblApplicantMedicalResult . '.applicant_id')
                ->groupBy('applicant_accounts.id');
        } elseif ($category == 'medical_result_passed' || $category == 'medical_result_pending' || $category == 'medical_result_failed') {
            if ($category == 'medical_result_passed') {
                $dataList->join($tblApplicantMedicalScheduled, $tblApplicantMedicalScheduled . '.applicant_id', 'applicant_accounts.id')
                    ->where($tblApplicantMedicalScheduled . '.is_removed', false)
                    ->join($tblApplicantMedicalResult, $tblApplicantMedicalResult . '.applicant_id', 'applicant_accounts.id')
                    ->where($tblApplicantMedicalResult . '.is_fit', 1)
                    ->where($tblApplicantMedicalResult . '.is_removed', false)
                    ->orderBy($tblApplicantMedicalResult . '.created_at', 'desc')
                    ->groupBy('applicant_accounts.id');
            } elseif ($category == 'medical_result_pending') {
                $dataList->join($tblApplicantMedicalScheduled, $tblApplicantMedicalScheduled . '.applicant_id', 'applicant_accounts.id')
                    ->where($tblApplicantMedicalScheduled . '.is_removed', false)
                    ->join($tblApplicantMedicalResult, $tblApplicantMedicalResult . '.applicant_id', 'applicant_accounts.id')
                    ->where($tblApplicantMedicalResult . '.is_pending', false)
                    ->where($tblApplicantMedicalResult . '.is_removed', false)
                    ->orderBy($tblApplicantMedicalResult . '.created_at', 'desc')
                    ->groupBy('applicant_accounts.id');
            } elseif ($category == 'medical_result_failed') {
                $dataList->join($tblApplicantMedicalScheduled, $tblApplicantMedicalScheduled . '.applicant_id', 'applicant_accounts.id')
                    ->where($tblApplicantMedicalScheduled . '.is_removed', false)
                    ->join($tblApplicantMedicalResult, $tblApplicantMedicalResult . '.applicant_id', 'applicant_accounts.id')
                    ->where($tblApplicantMedicalResult . '.is_fit', 2)
                    ->where($tblApplicantMedicalResult . '.is_removed', false)
                    ->orderBy($tblApplicantMedicalResult . '.created_at', 'desc')
                    ->groupBy('applicant_accounts.id');
            }
        } else {
            $dataList;
        }

        $dataList = $this->filter_category($dataList, $search, $category);

        return $dataList->get();
    }
    function filtered()
    {
        $this->applicants = [];
        $query =  ApplicantAccount::select('applicant_accounts.*')
            ->where('applicant_accounts.academic_id', base64_decode($this->academic))
            ->join($this->tblApplicantExamination, $this->tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
            ->where($this->tblApplicantExamination . '.is_removed', false)
            ->where($this->tblApplicantExamination . '.is_finish', true)
            ->where(function ($query) {
                $query->select(DB::raw('COUNT(*)'))
                    ->from($this->tblApplicantExaminationAnswer)
                    ->join(env('DB_DATABASE') . '.examination_question_choices', env('DB_DATABASE') . '.examination_question_choices.id', '=', $this->tblApplicantExaminationAnswer . '.choices_id')
                    ->where(env('DB_DATABASE') . '.examination_question_choices.is_answer', true)
                    ->whereColumn($this->tblApplicantExaminationAnswer . '.examination_id', env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.id');
            }, '>=', function ($query) {
                $query->select(DB::raw('IF(applicant_accounts.course_id = 3,70, 100)'));
            })
            ->groupBy('applicant_accounts.id')
            ->where('applicant_accounts.is_removed', false);
        /* $query2 = ApplicantAccount::select('applicant_accounts.*')
            ->join('bma_website.applicant_alumnias', 'bma_website.applicant_alumnias.applicant_id', '=', 'applicant_accounts.id')
            ->where('applicant_accounts.academic_id', base64_decode($this->academic))
            ->where('applicant_accounts.is_removed', 0);
        $query = $query->union($query2); */
        // Sort By Courses
        if ($this->selectCourse != 'ALL COURSE') {
            $query = $query->where('applicant_accounts.course_id', $this->selectCourse);
        }
        if ($this->searchInput != '') {
            $_student = explode(',', $this->searchInput); // Seperate the Sentence
            $_count = count($_student);
            $query = $query->join(env('DB_DATABASE_SECOND') . '.applicant_detials', env('DB_DATABASE_SECOND') . '.applicant_detials.applicant_id', 'applicant_accounts.id'); // Join to Applicant Details
            if ($_count > 1) {
                $query = $query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . $_student[0] . '%')
                    ->where(env('DB_DATABASE_SECOND') . '.applicant_detials.first_name', 'like', '%' . trim($_student[1]) . '%')
                    ->orderBy(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'asc');
            } else {
                $query = $query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . $_student[0] . '%')
                    ->orderBy(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'asc');
            }
        }
        if ($this->selecteCategories == 'waiting_for_scheduled') {
            $this->applicants = $query->leftJoin(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', 'applicant_accounts.id')
                ->whereNull('ama.applicant_id')->get();
        }
        if ($this->selecteCategories == 'medical_scheduled') {
            $this->applicants = $query->join(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', env('DB_DATABASE') . '.applicant_accounts.id')
                ->where('ama.is_removed', false)
                ->where('is_approved', false)
                ->groupBy('applicant_accounts.id')->get();
        }
        if ($this->selecteCategories == 'waiting_for_medical_result') {
            $this->applicants =  $query->join(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', env('DB_DATABASE') . '.applicant_accounts.id')
                ->where('ama.is_removed', false)
                ->where('is_approved', true)->leftJoin(env('DB_DATABASE_SECOND') . '.applicant_medical_results', env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id', 'ama.applicant_id')
                ->whereNull(env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id')
                ->groupBy('applicant_accounts.id')->get();
        }
        if ($this->selecteCategories == 'medical_result_passed') {
            $this->applicants =  $query->join(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', env('DB_DATABASE') . '.applicant_accounts.id')
                ->where('ama.is_removed', false)
                ->where('is_approved', true)
                ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_results', env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id', env('DB_DATABASE') . '.applicant_accounts.id')
                ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_fit', true)
                ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_removed', false)
                ->groupBy('applicant_accounts.id')->get();
        }
        if ($this->selecteCategories == 'medical_result_pending') {
            $this->applicants =  $query->join(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', env('DB_DATABASE') . '.applicant_accounts.id')
                ->where('ama.is_removed', false)
                ->where('is_approved', true)
                ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_results', env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id', env('DB_DATABASE') . '.applicant_accounts.id')
                ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_pending', false)
                ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_removed', false)
                ->groupBy('applicant_accounts.id')->get();
        }
        if ($this->selecteCategories == 'medical_result_failed') {
            $this->applicants =  $query->join(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', env('DB_DATABASE') . '.applicant_accounts.id')
                ->where('ama.is_removed', false)
                ->where('is_approved', true)
                ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_results', env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id', env('DB_DATABASE') . '.applicant_accounts.id')
                ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_fit', 2)
                ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_removed', false)
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
    function appointmentScheduled($appointment, $status)
    {
        $_appointment = ApplicantMedicalAppointment::find(base64_decode($appointment));
        $message = null;
        if ($status) {
            $_appointment->is_approved = 1;
            $_appointment->save();
            $_email_model = new ApplicantEmail();
            //$_email = 'p.banting@bma.edu.ph';
            $_email = $_appointment->account->email;
            Mail::to($_email)->bcc('p.banting@bma.edu.ph')->send($_email_model->medical_appointment_schedule($_appointment->account));
            $message = 'Appointment Approved';
        } else {
            $_appointment->is_approved = 0;
            $_appointment->is_removed = true;
            $_appointment->save();
            $message = 'Medical Schedule Disapproved';
        }
        $this->dispatchBrowserEvent('swal:alert', [
            'title' => 'Complete!',
            'text' => $message,
            'type' => 'success',
        ]);
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
    function generateReport()
    {
        return redirect(route('medical.applicant-report') . '?_academic=' . $this->academic);
    }
}
