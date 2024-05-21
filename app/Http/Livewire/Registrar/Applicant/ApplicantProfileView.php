<?php

namespace App\Http\Livewire\Registrar\Applicant;

use App\Http\Livewire\Components\ModalComponent;
use App\Models\AcademicYear;
use App\Models\ApplicantAccount;
use App\Models\ApplicantAlumnia;
use App\Models\CourseOffer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class ApplicantProfileView extends Component
{
    public $selectCategories = 'registered_applicants';
    public $selectCourse = 'ALL COURSE';
    public $selectedCourse = 'ALL COURSE';
    public $documentLink = null;
    public $searchInput;
    public $academic;
    public $profile = [];
    public $activeTab = 'documents';
    protected $listeners = ['bmaAlumnia'];
    public $showModal = false;

    public function render()
    {
        $filterCourses = CourseOffer::all();
        $this->academic = $this->academicValue();
        $this->profile = request()->query('_applicant') ? ApplicantAccount::find(base64_decode(request()->query('_applicant'))) : $this->profile;
        $this->selectCategories = request()->query('_catergory') ?: $this->selectCategories;
        $applicantView = new ApplicantView();
        if (Cache::has('menu')) {
            $this->activeTab = Cache::get('menu');
        }
        $filterContent = $applicantView->filterContent();
        $dataLists = $this->dataFilter($this->searchInput, $this->selectCourse, $this->selectCategories, $this->academic);
        return view('livewire.registrar.applicant.applicant-profile-view', compact('filterContent', 'filterCourses', 'dataLists'));
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
    function categoryCourse()
    {
        $course = 'ALL COURSE';
        if ($this->selectCourse != 'ALL COURSE') {
            $course = CourseOffer::find($this->selectCourse);
            $course = $course->course_name;
        }
        $this->selectedCourse = strtoupper($course);
    }
    function swtchTab($data)
    {
        $this->activeTab = $data;
        Cache::put('menu', $data, 60);
    }
    function dialogBoxSHS($data)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'title' => 'Are you sure?',
            'text' => 'This is BMA SHS Alumnia',
            'type' => 'info',
            'confirmButtonText' => 'Yes',
            'cancelButtonText' => 'Cancel',
            'method' => 'bmaAlumnia',
            'params' => ['data' => $data],
        ]);
    }
    function bmaAlumnia($data)
    {
        $_data = ['applicant_id' => $data, 'staff_id' => Auth::user()->staff->id];
        ApplicantAlumnia::create($_data);
        $this->dispatchBrowserEvent('swal:alert', [
            'title' => '',
            'text' => 'Successfully Transact',
            'type' => 'success',
        ]);
    }
    function resetPassword($applicant)
    {
        $applicant = ApplicantAccount::find($applicant);
        $length = 5;
        $_password = 'BMA-' . substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
        $applicant->password = Hash::make($_password);
        $applicant->save();
        $this->dispatchBrowserEvent('swal:alert', [
            'title' => 'Applicant Reset Password Complete!',
            'text' => 'Password: ' . $_password,
            'type' => 'success',
        ]);
    }
    function dataFilter($search, $course, $category, $academic)
    {
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

        switch ($category) {
            case 'total_registrants':
                $dataList = $dataList->join($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                    ->orderBy($tblApplicantDetails . '.created_at', 'desc');
                break;
            case 'registered_applicants_v1':
                $dataList = $dataList->join($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                    ->leftJoin($tblApplicantDocuments, $tblApplicantDocuments . '.applicant_id', 'applicant_accounts.id')
                    ->whereNull($tblApplicantDocuments . '.applicant_id');
                break;
            case 'registered_applicants':
                $dataList = $dataList->join($tblApplicantDocuments, $tblApplicantDocuments . '.applicant_id', 'applicant_accounts.id')
                    ->select(
                        'applicant_accounts.*',
                        DB::raw('(SELECT COUNT(*) FROM ' . $tblApplicantDocuments . ' WHERE ' . $tblApplicantDocuments . '.applicant_id = applicant_accounts.id AND ' . $tblApplicantDocuments . '.is_removed = 0 AND ' . $tblApplicantDocuments . '.is_approved = 1) AS ApprovedDocuments'),
                        DB::raw('(SELECT COUNT(*) FROM ' . $tblApplicantDocuments . ' WHERE ' . $tblApplicantDocuments . '.applicant_id = applicant_accounts.id AND ' . $tblApplicantDocuments . '.is_removed = 0 AND (' . $tblApplicantDocuments . '.is_approved is null or ' . $tblApplicantDocuments . '.is_approved = 1)) AS applicantDocuments'),
                        DB::raw('(SELECT COUNT(*) FROM ' . $tblDocuments . ' WHERE ' . $tblDocuments . '.department_id = 2 AND ' . $tblDocuments . '.is_removed = false AND ' . $tblDocuments . '.year_level = (SELECT IF(' . $applicantAccountTable . '.course_id = 3, 11, 4) FROM ' . $applicantAccountTable . ' WHERE ' . $applicantAccountTable . '.id = ' . $tblApplicantDocuments . '.applicant_id)) as documentCount')
                    )->havingRaw('applicantDocuments >= documentCount and ApprovedDocuments < documentCount')
                    ->groupBy('applicant_accounts.id')
                    ->orderBy('applicant_accounts.updated_at', 'desc');
                break;
            case 'approved':
                $dataList = $dataList->join($tblApplicantDocuments, $tblApplicantDocuments . '.applicant_id', 'applicant_accounts.id')
                    ->select(
                        'applicant_accounts.*',
                        DB::raw('(SELECT COUNT(*) FROM ' . $tblApplicantDocuments . ' WHERE ' . $tblApplicantDocuments . '.applicant_id = applicant_accounts.id AND ' . $tblApplicantDocuments . '.is_removed = 0 AND ' . $tblApplicantDocuments . '.is_approved = 1) AS ApprovedDocuments'),
                        DB::raw('(SELECT COUNT(*) FROM ' . $tblDocuments . ' WHERE ' . $tblDocuments . '.department_id = 2 AND ' . $tblDocuments . '.is_removed = false AND ' . $tblDocuments . '.year_level = (SELECT IF(' . $applicantAccountTable . '.course_id = 3, 11, 4) FROM ' . $applicantAccountTable . ' WHERE ' . $applicantAccountTable . '.id = ' . $tblApplicantDocuments . '.applicant_id)) as documentCount')
                    )
                    ->havingRaw('COUNT(' . $tblApplicantDocuments . '.applicant_id) >= documentCount and ApprovedDocuments >= documentCount')
                    ->leftJoin($tblApplicantAlumia, $tblApplicantAlumia . '.applicant_id', 'applicant_accounts.id')
                    ->whereNull($tblApplicantAlumia . '.applicant_id')
                    ->groupBy('applicant_accounts.id')
                    ->orderBy($tblApplicantDocuments . '.updated_at', 'desc');
                break;
            case 'pending':
                $dataList = $dataList->join($tblApplicantDocuments, 'applicant_documents.applicant_id', '=', 'applicant_accounts.id')
                    ->where($tblApplicantDocuments . '.is_approved', 2)
                    ->where($tblApplicantDocuments . '.is_removed', false)
                    ->groupBy('applicant_accounts.id')
                    ->orderBy($tblApplicantDocuments . '.updated_at', 'desc');
                break;
            case 'disapproved':
                $dataList =  $dataList->join($tblApplicantNotQualifieds, $tblApplicantNotQualifieds . '.applicant_id', 'applicant_accounts.id')
                    ->where($tblApplicantNotQualifieds . '.is_removed', false)
                    ->where($tblApplicantNotQualifieds . '.academic_id', base64_decode($academic))
                    ->groupBy('applicant_accounts.id')
                    ->orderBy($tblApplicantNotQualifieds . '.created_at', 'desc');
                break;
            case 'senior_high_school_alumni':
                $dataList = $dataList->join($tblApplicantAlumia, $tblApplicantAlumia . '.applicant_id', 'applicant_accounts.id')
                    ->where($tblApplicantAlumia . '.is_removed', false)
                    ->orderBy('applicant_accounts.created_at', 'desc');
                break;

            case 'waiting_examination_payment':
                $dataList = $dataList->join($tblApplicantDocuments, $tblApplicantDocuments . '.applicant_id', 'applicant_accounts.id')
                    ->select(
                        'applicant_accounts.*',
                        DB::raw('(SELECT COUNT(*) FROM ' . $tblApplicantDocuments . ' WHERE ' . $tblApplicantDocuments . '.applicant_id = applicant_accounts.id AND ' . $tblApplicantDocuments . '.is_removed = 0 AND ' . $tblApplicantDocuments . '.is_approved = 1) AS ApprovedDocuments'),
                        DB::raw('(SELECT COUNT(*) FROM ' . $tblDocuments . ' WHERE ' . $tblDocuments . '.department_id = 2 AND ' . $tblDocuments . '.is_removed = false AND ' . $tblDocuments . '.year_level = (SELECT IF(' . $applicantAccountTable . '.course_id = 3, 11, 4) FROM ' . $applicantAccountTable . ' WHERE ' . $applicantAccountTable . '.id = ' . $tblApplicantDocuments . '.applicant_id)) as documentCount')
                    )
                    ->havingRaw('COUNT(' . $tblApplicantDocuments . '.applicant_id) >= documentCount and ApprovedDocuments >= documentCount')
                    ->leftJoin($tblApplicantAlumia, $tblApplicantAlumia . '.applicant_id', 'applicant_accounts.id')
                    ->leftJoin($tblApplicantPayment, $tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                    ->whereNull($tblApplicantAlumia . '.applicant_id')
                    ->whereNull($tblApplicantPayment . '.applicant_id')
                    ->groupBy('applicant_accounts.id')
                    ->orderBy($tblApplicantDocuments . '.updated_at', 'desc');
                break;
            case 'examination_payment';
                $dataList = $dataList->join($tblApplicantPayment, $tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                    ->where(function ($query) {
                        $query->whereNull(env('DB_DATABASE_SECOND') . '.applicant_payments.is_approved')
                            ->orWhere(env('DB_DATABASE_SECOND') . '.applicant_payments.is_approved', false);
                    })
                    ->where($tblApplicantPayment . '.is_removed', false)
                    ->orderBy($tblApplicantPayment . '.updated_at', 'desc');
                break;
            case 'entrance_examination';
                $dataList->join($tblApplicantExamination, $tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                    ->orderBy($tblApplicantExamination . '.created_at', 'desc')
                    ->whereNull($tblApplicantExamination . '.is_finish');
                /*  $dataList->join($tblApplicantExamination, $tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                    ->where($tblApplicantExamination . '.is_removed', false)
                    ->where($tblApplicantExamination . '.is_finish', true)
                    ->orderBy($tblApplicantExamination . '.created_at', 'desc'); */
                break;
            case 'passed';
                $dataList = $this->examination_result($dataList, '>=')
                    ->orderBy($tblApplicantExamination . '.updated_at', 'desc');
                break;
            case 'failed';
                $dataList = $this->examination_result($dataList, '<')
                    ->orderBy($tblApplicantExamination . '.updated_at', 'desc');
                break;
            case 'for_medical_schedule':
                $dataList = $dataList = $this->examination_result($dataList, '>=')/* ->union($this->senior_high_alumia($dataList)) */
                    ->leftJoin($tblApplicantMedicalScheduled, $tblApplicantMedicalScheduled . '.applicant_id', 'applicant_accounts.id')
                    ->whereNull($tblApplicantMedicalScheduled . '.applicant_id')
                    ->groupBy('applicant_accounts.id');
                break;
            case 'waiting_for_medical_results':
                $dataList->join($tblApplicantMedicalScheduled, $tblApplicantMedicalScheduled . '.applicant_id', 'applicant_accounts.id')
                    ->where($tblApplicantMedicalScheduled . '.is_removed', false)
                    ->leftJoin($tblApplicantMedicalResult, $tblApplicantMedicalResult . '.applicant_id', 'applicant_accounts.id')
                    ->whereNull($tblApplicantMedicalResult . '.applicant_id')
                    ->groupBy('applicant_accounts.id');
                break;
            case 'fit':
                $dataList =  $this->medical_result($dataList, 1);
                break;
            case 'unfit':
                $dataList =   $this->medical_result($dataList, 2);
                break;
            case 'pending_result':
                $dataList->join($tblApplicantMedicalResult, $tblApplicantMedicalResult . '.applicant_id', 'applicant_accounts.id')
                    ->where($tblApplicantMedicalResult . '.is_removed', false)
                    ->where($tblApplicantMedicalResult . '.is_pending', 0)
                    ->orderBy($tblApplicantMedicalResult . '.created_at', 'desc');
                break;
            case 'qualified_for_enrollment':
                $dataList =  $this->medical_result($dataList, 1);
                break;
            case 'non_pbm':
                $dataList =  $this->medical_result($dataList, 1)
                    ->where('applicant_accounts.strand', '!=', 'Pre-Baccalaureate Maritime Strand');
                break;
            case 'pbm':
                $dataList =  $this->medical_result($dataList, 1)
                    ->where('applicant_accounts.strand', 'Pre-Baccalaureate Maritime Strand');
                break;
            default:
                $dataList;
                break;
        }
        $dataList = $this->filter_category($dataList, $search, $course, $category);
        return $dataList->get();
    }
    function senior_high_alumia($data)
    {
        $tblApplicantAlumia = env('DB_DATABASE_SECOND') . '.applicant_alumnias';
        return $data->join($tblApplicantAlumia, $tblApplicantAlumia . '.applicant_id', 'applicant_accounts.id')
            ->where($tblApplicantAlumia . '.is_removed', false)
            ->orderBy('applicant_accounts.created_at', 'desc');
    }
    function examination_result($data, $operation)
    {
        $tblApplicantExamination = env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations';
        return $data->join($tblApplicantExamination, $tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
            ->where($tblApplicantExamination . '.is_removed', false)
            ->where($tblApplicantExamination . '.is_finish', true)
            ->where(function ($query) {
                $query->select(DB::raw('COUNT(*)'))
                    ->from(env('DB_DATABASE_SECOND') . '.applicant_examination_answers')
                    ->join(env('DB_DATABASE') . '.examination_question_choices', env('DB_DATABASE') . '.examination_question_choices.id', '=', env('DB_DATABASE_SECOND') . '.applicant_examination_answers' . '.choices_id')
                    ->where(env('DB_DATABASE') . '.examination_question_choices.is_answer', true)
                    ->whereColumn(env('DB_DATABASE_SECOND') . '.applicant_examination_answers' . '.examination_id', env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.id');
            }, $operation, function ($query) {
                $query->select(DB::raw('IF(applicant_accounts.course_id = 3, 70, 100)'));
            });
    }
    function medical_result($query, $result)
    {
        $tblApplicantMedicalResult = env('DB_DATABASE_SECOND') . '.applicant_medical_results';
        return $query->join($tblApplicantMedicalResult, $tblApplicantMedicalResult . '.applicant_id', 'applicant_accounts.id')
            ->where($tblApplicantMedicalResult . '.is_removed', false)
            ->where($tblApplicantMedicalResult . '.is_fit', $result)
            ->orderBy($tblApplicantMedicalResult . '.created_at', 'desc');
    }
    function filter_category($query, $search, $course, $category)
    {
        if ($course != 'ALL COURSE') {
            $query = $query->where('applicant_accounts.course_id', $course);
        }
        // Search Sorting
        if ($search != '') {
            $tblApplicantDetails = env('DB_DATABASE_SECOND') . '.applicant_detials';
            if ($category == 'created_accounts') {
                $query->where('name', 'like', '%' . $search . '%')->orWhere('email', 'like', '%' . $search . '%')
                    ->orderBy('created_at', 'desc');
            } else {
                $_student = explode(',', $search); // Seperate the Sentence
                $_count = count($_student);
                if ($category !== 'total_registrants' && $category !== 'registered_applicants_v1') {
                    $query = $query->join($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', 'applicant_accounts.id');
                }
                if ($_count > 1) {
                    $query->where($tblApplicantDetails . '.last_name', 'like', '%' . $_student[0] . '%')
                        ->where($tblApplicantDetails . '.first_name', 'like', '%' . trim($_student[1]) . '%')
                        ->orderBy($tblApplicantDetails . '.last_name', 'asc');
                } else {
                    $query->where($tblApplicantDetails . '.last_name', 'like', '%' . $_student[0] . '%')
                        ->orderBy($tblApplicantDetails . '.last_name', 'asc');
                }
            }
        }
        return $query;
    }
}
