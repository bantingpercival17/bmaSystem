<?php

namespace App\Http\Livewire\Registrar\Applicant;

use App\Models\AcademicYear;
use App\Models\ApplicantAccount;
use App\Models\CourseOffer;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ApplicantView extends Component
{
    public $selectCategories = 'created_accounts';
    public $selectCourse = 'ALL COURSE';
    public $selectedCourse = 'ALL COURSE';
    public $searchInput;
    public $academic = null;
    public $applicantAccountTable;
    public $tblDocuments;
    public $tblApplicantDetails;
    public $tblApplicantDocuments;
    public $tblApplicantNotQualifieds;
    public $tblApplicantPayment;
    public $tblApplicantAlumia;
    public $tblApplicantExamination;
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
    }
    public function render()
    {
        $filterContent = array('created_accounts', 'registered_applicants', /* 'registration_with_document', */ 'for_checking', 'not_qualified', 'qualified', 'qualified_for_entrance_examination', 'examination_payment', 'entrance_examination');
        $filterCourses = CourseOffer::all();
        $this->academic = $this->academicValue();
        $dataLists = $this->filterApplicantData($this->searchInput, $this->selectCourse, $this->selectCategories, $this->academic);
        return view('livewire.registrar.applicant.applicant-view', compact('filterContent', 'filterCourses', 'dataLists'));
    }
    function academicValue()
    {
        /*  if ($this->academic === null) {
            $_academic = AcademicYear::where('is_active', 1)->first();
            $data = base64_encode($_academic->id);
        } else {
            $data =  request()->query('_academic') ?: $this->academic;
        } */
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
    function filterApplicantData($searchInput, $selectCourse, $selectCategories, $academic)
    {
        $dataLists = [];
        $query = ApplicantAccount::select('applicant_accounts.*')
            ->where('applicant_accounts.is_removed', false)
            ->where('applicant_accounts.academic_id', base64_decode($academic));
        // Sort By Courses
        if ($selectCourse != 'ALL COURSE') {
            $query = $query->where('applicant_accounts.course_id', $selectCourse);
        }
        if ($searchInput != '') {
            $_student = explode(',', $searchInput); // Seperate the Sentence
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
        switch ($selectCategories) {
            case 'created_accounts':
                $dataLists = $query->leftJoin($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                    ->whereNull($this->tblApplicantDetails . '.applicant_id');
                /*  $dataLists = $query->leftJoin('bma_website.applicant_detials', 'bma_website.applicant_detials.applicant_id', 'applicant_accounts.id')
                    ->whereNull('bma_website.applicant_detials.applicant_id'); */
                break;
            case 'registered_applicants':
                $dataLists = $query
                    ->join($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                    ->leftJoin($this->tblApplicantDocuments, $this->tblApplicantDocuments . '.applicant_id', 'applicant_accounts.id')
                    ->whereNull($this->tblApplicantDocuments . '.applicant_id');
                break;
            case 'for_checking':
                $dataLists = $query
                    ->join($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                    ->join($this->tblApplicantDocuments, $this->tblApplicantDocuments . '.applicant_id', '=', 'applicant_accounts.id')
                    ->select(
                        'applicant_accounts.*',
                        DB::raw('(SELECT COUNT(' . $this->tblApplicantDocuments . '.is_approved)
                        FROM ' . $this->tblApplicantDocuments . '
                        WHERE ' . $this->tblApplicantDocuments . '.applicant_id = applicant_accounts.id
                        AND ' . $this->tblApplicantDocuments . '.is_removed = 0
                        AND ' . $this->tblApplicantDocuments . '.is_approved = 1) AS ApprovedDocuments'),
                        DB::raw('(
                            SELECT COUNT(' . $this->tblDocuments . '.id)
                            FROM ' . $this->tblDocuments . '
                            WHERE ' . $this->tblDocuments . '.department_id = 2
                            AND ' . $this->tblDocuments . '.is_removed = false
                            AND ' . $this->tblDocuments . '.year_level = (
                                SELECT IF(' . $this->applicantAccountTable . '.course_id = 3, 11, 4) as result
                                FROM ' . $this->applicantAccountTable . '
                                WHERE ' . $this->applicantAccountTable . '.id = ' . $this->tblApplicantDetails . '.applicant_id
                        )) as documentCount')
                    )
                    ->leftJoin($this->tblApplicantNotQualifieds . ' as anq', 'anq.applicant_id', 'applicant_accounts.id')
                    ->whereNull('anq.applicant_id')
                    ->groupBy('applicant_accounts.id')
                    ->havingRaw('COUNT(' . $this->tblApplicantDocuments . '.applicant_id) >= documentCount and ApprovedDocuments < documentCount');
                break;
            case 'not_qualified':
                $dataLists = $query->join($this->tblApplicantNotQualifieds, $this->tblApplicantNotQualifieds . '.applicant_id', $this->applicantAccountTable . '.id')
                    ->where($this->tblApplicantNotQualifieds . '.academic_id', base64_decode($academic));
                break;
            case 'qualified':
                $dataLists = $query
                    ->join($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                    ->join($this->tblApplicantDocuments, $this->tblApplicantDocuments . '.applicant_id', '=', 'applicant_accounts.id')
                    ->select(
                        'applicant_accounts.*',
                        DB::raw('(SELECT COUNT(' . $this->tblApplicantDocuments . '.is_approved)
                    FROM ' . $this->tblApplicantDocuments . '
                    WHERE ' . $this->tblApplicantDocuments . '.applicant_id = applicant_accounts.id
                    AND ' . $this->tblApplicantDocuments . '.is_removed = 0
                    AND ' . $this->tblApplicantDocuments . '.is_approved = 1) AS ApprovedDocuments'),
                        DB::raw('(
                        SELECT COUNT(' . $this->tblDocuments . '.id)
                        FROM ' . $this->tblDocuments . '
                        WHERE ' . $this->tblDocuments . '.department_id = 2
                        AND ' . $this->tblDocuments . '.is_removed = false
                        AND ' . $this->tblDocuments . '.year_level = (
                            SELECT IF(' . $this->applicantAccountTable . '.course_id = 3, 11, 4) as result
                            FROM ' . $this->applicantAccountTable . '
                            WHERE ' . $this->applicantAccountTable . '.id = ' . $this->tblApplicantDetails . '.applicant_id
                        )) as documentCount')
                    )
                    ->leftJoin($this->tblApplicantNotQualifieds . ' as anq', 'anq.applicant_id', 'applicant_accounts.id')
                    ->whereNull('anq.applicant_id')
                    ->groupBy('applicant_accounts.id')
                    ->havingRaw('COUNT(' . $this->tblApplicantDocuments . '.applicant_id) >= documentCount and ApprovedDocuments = documentCount');

                break;
            case 'qualified_for_entrance_examination':
                $dataLists = $query->join($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                    ->join($this->tblApplicantDocuments, $this->tblApplicantDocuments . '.applicant_id', '=', 'applicant_accounts.id')
                    ->select('applicant_accounts.*', DB::raw('(SELECT COUNT(' . $this->tblApplicantDocuments . '.is_approved)
                            FROM ' . $this->tblApplicantDocuments . '
                            WHERE ' . $this->tblApplicantDocuments . '.applicant_id = applicant_accounts.id
                            AND ' . $this->tblApplicantDocuments . '.is_removed = 0
                            AND ' . $this->tblApplicantDocuments . '.is_approved = 1) AS ApprovedDocuments'), DB::raw('(
                                SELECT COUNT(' . $this->tblDocuments . '.id)
                                FROM ' . $this->tblDocuments . '
                                WHERE ' . $this->tblDocuments . '.department_id = 2
                                AND ' . $this->tblDocuments . '.is_removed = false
                                AND ' . $this->tblDocuments . '.year_level = (
                                    SELECT IF(' . $this->applicantAccountTable . '.course_id = 3, 11, 4) as result
                                    FROM ' . $this->applicantAccountTable . '
                                    WHERE ' . $this->applicantAccountTable . '.id = ' . $this->tblApplicantDetails . '.applicant_id
                                ))as documentCount'))
                    ->leftJoin($this->tblApplicantNotQualifieds . ' as anq', 'anq.applicant_id', 'applicant_accounts.id')
                    ->leftJoin($this->tblApplicantPayment, $this->tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                    ->leftJoin($this->tblApplicantAlumia, $this->tblApplicantAlumia . '.applicant_id', 'applicant_accounts.id')
                    ->where($this->tblApplicantAlumia . '.applicant_id')
                    ->whereNull($this->tblApplicantPayment . '.applicant_id')
                    ->whereNull('anq.applicant_id')
                    ->groupBy('applicant_accounts.id')
                    ->havingRaw('COUNT(' . $this->tblApplicantDocuments . '.applicant_id) >= documentCount and ApprovedDocuments = documentCount');

                break;
            case 'examination_payment':
                $dataLists = $query->join($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                    ->join($this->tblApplicantDocuments, $this->tblApplicantDocuments . '.applicant_id', '=', 'applicant_accounts.id')
                    ->select('applicant_accounts.*', DB::raw('(SELECT COUNT(' . $this->tblApplicantDocuments . '.is_approved)
                        FROM ' . $this->tblApplicantDocuments . '
                        WHERE ' . $this->tblApplicantDocuments . '.applicant_id = applicant_accounts.id
                        AND ' . $this->tblApplicantDocuments . '.is_removed = 0
                        AND ' . $this->tblApplicantDocuments . '.is_approved = 1) AS ApprovedDocuments'), DB::raw('(
                            SELECT COUNT(' . $this->tblDocuments . '.id)
                            FROM ' . $this->tblDocuments . '
                            WHERE ' . $this->tblDocuments . '.department_id = 2
                            AND ' . $this->tblDocuments . '.is_removed = false
                            AND ' . $this->tblDocuments . '.year_level = (
                                SELECT IF(' . $this->applicantAccountTable . '.course_id = 3, 11, 4) as result
                                FROM ' . $this->applicantAccountTable . '
                                WHERE ' . $this->applicantAccountTable . '.id = ' . $this->tblApplicantDetails . '.applicant_id
                            ))as documentCount'))
                    ->join($this->tblApplicantPayment, $this->tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                    ->where(function ($query) {
                        $query->whereNull($this->tblApplicantPayment . '.is_approved')
                            ->orWhere($this->tblApplicantPayment . '.is_approved', false);
                    })
                    ->where($this->tblApplicantPayment . '.is_removed', false)

                    ->groupBy('applicant_accounts.id')
                    ->havingRaw('COUNT(' . $this->tblApplicantDocuments . '.applicant_id) >= documentCount and ApprovedDocuments = documentCount');
                break;

            case 'entrance_examination':
                $dataLists = $query->join($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                    ->join($this->tblApplicantPayment, $this->tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                    ->where($this->tblApplicantPayment . '.is_approved', true)
                    ->where($this->tblApplicantPayment . '.is_removed', false)
                    ->join($this->tblApplicantExamination, $this->tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                    ->where($this->tblApplicantExamination . '.is_removed', false)
                    ->whereNull($this->tblApplicantExamination . '.is_finish')
                    ->groupBy($this->tblApplicantExamination . '.applicant_id');
                break;
            default:
                $dataLists = [];
                break;
        }
        return $dataLists->get();
    }
}
