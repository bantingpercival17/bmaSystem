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
        if ($this->profile) {
            $approvedDocuments = $this->profile->applicant_documents_status();
        }
        $filterContent = $applicantView->filterContent();
        $dataLists = $applicantView->filterApplicantData($this->searchInput, $this->selectCourse, $this->selectCategories, $this->academic);
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
    function showDocuments($data)
    {
        $this->showModal = true;
        $this->documentLink = null;
        $this->documentLink = $data;
    }
    function hideDocuments()
    {
        $this->showModal = false;
        $this->documentLink = null;
    }
}
