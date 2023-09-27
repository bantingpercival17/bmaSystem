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

class ApplicantProfileView extends Component
{
    public $selectCategories = 'for_checking';
    public $selectCourse = 'ALL COURSE';
    public $selectedCourse = 'ALL COURSE';
    public $documentLink = null;
    public $searchInput;
    public $academic = null;
    public $profile = [];
    public $activeTab  = 'profile';
    protected $listeners = ['bmaAlumnia'];
    public $modalComponent;

    public function mount()
    {
        $this->modalComponent = new ModalComponent();
    }
    public function render()
    {
        $filterContent = array('created_accounts', 'registered_applicants', /* 'registration_with_document', */ 'for_checking', 'not_qualified', 'qualified_for_entrance_examination');
        $filterCourses = CourseOffer::all();
        $this->academic = $this->academicValue();
        $this->profile = request()->query('_applicant') ? ApplicantAccount::find(base64_decode(request()->query('_applicant'))) : $this->profile;
        $this->selectCategories =  request()->query('_catergory') ?: $this->selectCategories;
        $applicantView = new ApplicantView();
        #$dataLists = $this->filterData();
        $dataLists = $applicantView->filterApplicantData($this->searchInput, $this->selectCourse, $this->selectCategories, $this->academic);
        return view('livewire.registrar.applicant.applicant-profile-view', compact('filterContent', 'filterCourses', 'dataLists'));
    }
    function academicValue()
    {
        if ($this->academic === null) {
            $_academic = AcademicYear::where('is_active', 1)->first();
            $data = base64_encode($_academic->id);
        } else {
            $data =  request()->query('_academic') ?: $this->academic;
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
    function filterData()
    {
        $dataLists = [];
        $query = ApplicantAccount::select('applicant_accounts.*')
            ->where('applicant_accounts.is_removed', false)
            ->where('applicant_accounts.academic_id', base64_decode($this->academic));
        // Sort By Courses
        if ($this->selectCourse != 'ALL COURSE') {
            $query = $query->where('applicant_accounts.course_id', $this->selectCourse);
        }
        if ($this->searchInput != '') {
            $_student = explode(',', $this->searchInput); // Seperate the Sentence
            $_count = count($_student);
            if ($_count > 1) {
                $query = $query->where('applicant_detials.last_name', 'like', '%' . $_student[0] . '%')
                    ->where('applicant_detials.first_name', 'like', '%' . trim($_student[1]) . '%')
                    ->orderBy('applicant_detials.last_name', 'asc');
            } else {
                $query = $query->where('applicant_detials.last_name', 'like', '%' . $_student[0] . '%')
                    ->orderBy('applicant_detials.last_name', 'asc');
            }
        }
        switch ($this->selectCategories) {
            case 'created_accounts':
                $dataLists = $query->leftJoin('bma_website.applicant_detials', 'bma_website.applicant_detials.applicant_id', 'applicant_accounts.id')
                    ->whereNull('bma_website.applicant_detials.applicant_id');
                break;
                /*   case 'registered_applicants':
                $dataLists = $query->join('bma_website.applicant_detials', 'bma_website.applicant_detials.applicant_id', 'applicant_accounts.id')
                    ;
                break; */
            case 'registered_applicants':
                $dataLists = $query
                    ->join('bma_website.applicant_detials', 'bma_website.applicant_detials.applicant_id', 'applicant_accounts.id')
                    ->leftJoin('bma_website.applicant_documents', 'bma_website.applicant_documents.applicant_id', 'applicant_accounts.id')
                    ->whereNull('bma_website.applicant_documents.applicant_id');
                break;
            case 'for_checking':
                $dataLists = $query->join('bma_website.applicant_detials', 'bma_website.applicant_detials.applicant_id', 'applicant_accounts.id')
                    ->join('bma_website.applicant_documents', 'bma_website.applicant_documents.applicant_id', '=', 'applicant_accounts.id')
                    ->select('applicant_accounts.*', DB::raw('(SELECT COUNT(`is_approved`)
                FROM `bma_website.applicant_documents`
                WHERE `bma_website.applicant_documents`.`applicant_id` = `applicant_accounts`.`id`
                  AND `bma_website.applicant_documents`.`is_removed` = 0
                  AND `bma_website.applicant_documents`.`is_approved` = 1) AS ApprovedDocuments'), DB::raw('(
                    SELECT COUNT(bma_portal.documents.id)
                    FROM bma_portal.documents
                    WHERE bma_portal.documents.department_id = 2
                      AND bma_portal.documents.is_removed = false
                      AND bma_portal.documents.year_level = (
                          SELECT IF(bma_portal.applicant_accounts.course_id = 3, 11, 4) as result
                          FROM bma_portal.applicant_accounts
                          WHERE bma_portal.applicant_accounts.id = 1
                      ))as documentCount'))
                    ->leftJoin('applicant_not_qualifieds as anq', 'anq.applicant_id', 'applicant_accounts.id')
                    ->whereNull('anq.applicant_id')
                    ->groupBy('applicant_accounts.id')
                    ->havingRaw('COUNT(bma_website.applicant_documents.applicant_id) >= documentCount and ApprovedDocuments < documentCount');
                break;
            case 'not_qualified':
                $dataLists = $query->join('applicant_not_qualifieds', 'applicant_not_qualifieds.applicant_id', 'applicant_accounts.id')
                    ->where('applicant_not_qualifieds.academic_id', base64_decode($this->academic));
                break;
            case 'qualified_for_entrance_examination':
                $dataLists = $query->join('applicant_not_qualifieds', 'applicant_not_qualifieds.applicant_id', 'applicant_accounts.id')
                    ->where('applicant_not_qualifieds.academic_id', base64_decode($this->academic))
                    ->leftJoin('applicant_payments', 'applicant_payments.applicant_id', 'applicant_accounts.id')/* Applicant Payment */
                    ->whereNull('applicant_payments.applicant_id');
                break;
            default:
                $dataLists = [];
                break;
        }
        return $dataLists->paginate(10);
    }
    function swtchTab($data)
    {
        $this->activeTab = $data;
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
        $_data = array('applicant_id' => $data, 'staff_id' => Auth::user()->staff->id);
        ApplicantAlumnia::create($_data);
        $this->dispatchBrowserEvent('swal:alert', [
            'title' => 'Complete!',
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
    function showDocuments($data)
    {
        $this->modalComponent->openModal();
        $this->documentLink = null;
        $this->documentLink = $data;
    }
}
