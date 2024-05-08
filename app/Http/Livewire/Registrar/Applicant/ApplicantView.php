<?php

namespace App\Http\Livewire\Registrar\Applicant;

use App\Models\AcademicYear;
use App\Models\ApplicantAccount;
use App\Models\CourseOffer;
use App\Models\StaffDepartment;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class ApplicantView extends Component
{
    public $selectCategories;
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
        $this->tblApplicantOrientation = env('DB_DATABASE_SECOND') . '.applicant_briefings';
    }
    public function render()
    {
        $filterContent = $this->filterContent();
        //$filterContent = array('created_accounts', 'registered_applicants', 'for_checking', 'not_qualified', 'qualified', 'qualified_for_entrance_examination', 'examination_payment', 'entrance_examination', 'examination_passed');
        $filterCourses = CourseOffer::all();
        $this->academic = $this->academicValue();
        $this->selectCourse = $this->getCourse();
        $this->selectCategories = $this->getCategories();
        $dataLists = $this->filterData($this->searchInput, $this->selectCourse, $this->selectCategories, $this->academic);
        return view('livewire.registrar.applicant.applicant-view', compact('filterContent', 'filterCourses', 'dataLists'));
    }
    function filterContent()
    {
        $reguralUser =  array(
            array('Information Verification', array('registered_applicants', 'approved', 'disapproved', 'pending', 'senior_high_school_alumni')),
            array('Entrance Examination', array('examination_payment', 'entrance_examination', 'passed', 'failed')),
            array('Medical Examination', array('for_medical_schedule', 'waiting_for_medical_results', 'fit', 'unfit', 'pending')),
            array('Enrollment', array('qualified_for_enrollment', 'non_pbm', 'pbm'))
        );
        $admin = array('User Accounts', array('created_accounts', 'registered_applicants_v1', 'total_registrants'));
        $adminRole = StaffDepartment::where('role_id', 1)->where('staff_id', auth()->user()->staff->id)->first();
        if ($adminRole) {
            $sortList = array_merge($reguralUser, array($admin));
        } else {
            $sortList = $reguralUser;
        }
        return $sortList;
    }
    function academicValue()
    {
        if (empty($this->academic)) {
            $activeAcademic = AcademicYear::where('is_active', 1)->first();
            if ($activeAcademic) {
                return base64_encode($activeAcademic->id);
            }
        }
        if (request()->query('_academic')) {
            return request()->query('_academic');
        }
        return $this->academic;
    }
    function getCourse()
    {
        $data = $this->selectCourse;
        if (request()->query('_course')) {
            $data = base64_decode(request()->query('_course')) ?: $this->selectCourse;
        }
        return $data;
    }
    function getCategories()
    {
        $data = $this->selectCategories ?: 'registered_applicants';
        if (request()->query('_category')) {
            $data = request()->query('_category') ?: $data;
        }
        Cache::put('category', $data, 120);
        $cachedCategory = Cache::get('category');
        if ($cachedCategory) {
            $data = $cachedCategory;
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

        $query = ApplicantAccount::select('applicant_accounts.*')
            ->where('applicant_accounts.is_removed', false)
            ->where('applicant_accounts.academic_id', base64_decode($academic));
        $dataLists = $query;
        // Sort By Courses
        if ($selectCourse != 'ALL COURSE') {
            $query = $query->where('applicant_accounts.course_id', $selectCourse);
        }

        if ($selectCategories == 'created_accounts') {
            $dataLists = $query->leftJoin($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->whereNull($this->tblApplicantDetails . '.applicant_id');
        }
        if ($selectCategories == 'registered_applicants_v1') {
            $dataLists = $query
                ->join($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->leftJoin($this->tblApplicantDocuments, $this->tblApplicantDocuments . '.applicant_id', 'applicant_accounts.id')
                ->whereNull($this->tblApplicantDocuments . '.applicant_id');
        }
        if ($selectCategories == 'total_registrants') {
            $dataLists = $query
                ->join($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->orderBy($this->tblApplicantDetails . '.created_at', 'desc');
        }
        if ($selectCategories == 'senior_high_school_alumni') {
            $dataLists = $query->join($this->tblApplicantAlumia, $this->tblApplicantAlumia . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantAlumia . '.is_removed', false);
        }
        if ($selectCategories == 'registered_applicants') {
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
                    DB::raw('(SELECT COUNT(' . $this->tblApplicantDocuments . '.applicant_id)
                FROM ' . $this->tblApplicantDocuments . '
                WHERE ' . $this->tblApplicantDocuments . '.applicant_id = applicant_accounts.id
                AND ' . $this->tblApplicantDocuments . '.is_removed = 0
                AND (' . $this->tblApplicantDocuments . '.is_approved is null or ' . $this->tblApplicantDocuments . '.is_approved = 1)) AS applicantDocuments'),
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
                ->havingRaw('applicantDocuments >= documentCount and ApprovedDocuments < documentCount');
            #->havingRaw('COUNT(' . $this->tblApplicantDocuments . '.applicant_id) >= documentCount and ApprovedDocuments < documentCount');
        }
        if ($selectCategories == 'disapproved') {
            $dataLists = $query->join($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($this->tblApplicantNotQualifieds, $this->tblApplicantNotQualifieds . '.applicant_id', $this->applicantAccountTable . '.id')
                ->where($this->tblApplicantNotQualifieds . '.is_removed', false)
                ->where($this->tblApplicantNotQualifieds . '.academic_id', base64_decode($academic))
                ->groupBy('applicant_accounts.id');
        }
        if ($selectCategories == 'approved') {
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
        }
        if ($selectCategories == 'pending') {
            $query = $query
                ->join($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($this->tblApplicantDocuments, $this->tblApplicantDocuments . '.applicant_id', '=', 'applicant_accounts.id')
                ->where($this->tblApplicantDocuments . '.is_approved', 2)
                ->where($this->tblApplicantDocuments . '.is_removed', false)
                ->leftJoin($this->tblApplicantNotQualifieds . ' as anq', 'anq.applicant_id', 'applicant_accounts.id')
                ->whereNull('anq.applicant_id')
                ->groupBy('applicant_accounts.id');
        }
        if ($selectCategories == 'qualified_for_entrance_examination') {
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
        }
        if ($selectCategories == 'examination_payment') {
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
        }
        if ($selectCategories == 'entrance_examination') {
            $dataLists = $query->join($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($this->tblApplicantPayment, $this->tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantPayment . '.is_approved', true)
                ->where($this->tblApplicantPayment . '.is_removed', false)
                ->join($this->tblApplicantExamination, $this->tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantExamination . '.is_removed', false)
                ->whereNull($this->tblApplicantExamination . '.is_finish')
                ->groupBy($this->tblApplicantExamination . '.applicant_id');
        }
        if ($selectCategories == 'passed') {
            $dataLists =  $query->join($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($this->tblApplicantPayment, $this->tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantPayment . '.is_approved', true)
                ->where($this->tblApplicantPayment . '.is_removed', false)
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
                    $query->select(DB::raw('IF(applicant_accounts.course_id = 3, 20, 100)'));
                })
                ->groupBy('applicant_accounts.id')->orderBy($this->tblApplicantExamination . '.updated_at', 'desc');
        }
        if ($selectCategories == 'failed') {
            $dataLists =  $query->join($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($this->tblApplicantPayment, $this->tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantPayment . '.is_approved', true)
                ->where($this->tblApplicantPayment . '.is_removed', false)
                ->join($this->tblApplicantExamination, $this->tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantExamination . '.is_removed', false)
                ->where($this->tblApplicantExamination . '.is_finish', true)
                ->where(function ($query) {
                    $query->select(DB::raw('COUNT(*)'))
                        ->from($this->tblApplicantExaminationAnswer)
                        ->join(env('DB_DATABASE') . '.examination_question_choices', env('DB_DATABASE') . '.examination_question_choices.id', '=', $this->tblApplicantExaminationAnswer . '.choices_id')
                        ->where(env('DB_DATABASE') . '.examination_question_choices.is_answer', true)
                        ->whereColumn($this->tblApplicantExaminationAnswer . '.examination_id', env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.id');
                }, '<', function ($query) {
                    $query->select(DB::raw('IF(applicant_accounts.course_id = 3, 20, 100)'));
                })
                ->groupBy('applicant_accounts.id')->orderBy($this->tblApplicantExamination . '.created_at', 'desc');;
        }
        if ($selectCategories == 'no_of_qualified_examinees') {
            $dataLists =  $query->join($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($this->tblApplicantPayment, $this->tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantPayment . '.is_approved', true)
                ->where($this->tblApplicantPayment . '.is_removed', false)
                ->join($this->tblApplicantExamination, $this->tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantExamination . '.is_removed', false)
                ->where($this->tblApplicantExamination . '.is_finish', true)
                ->groupBy('applicant_accounts.id')->orderBy($this->tblApplicantExamination . '.created_at', 'desc');
        }
        if ($selectCategories == 'expected_attendees') {
            $dataLists =  $query->join($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($this->tblApplicantPayment, $this->tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantPayment . '.is_approved', true)
                ->where($this->tblApplicantPayment . '.is_removed', false)
                ->join($this->tblApplicantExamination, $this->tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantExamination . '.is_removed', false)
                ->where($this->tblApplicantExamination . '.is_finish', true)
                ->join($this->tblApplicantOrientation, $this->tblApplicantOrientation . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantOrientation . '.is_completed', false)
                ->groupBy('applicant_accounts.id')/* ->orderBy($this->tblApplicantOrientationScheduled . '.created_at', 'desc') */;
        }
        if ($selectCategories == 'total_attendees') {
            $dataLists =  $query->join($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($this->tblApplicantPayment, $this->tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantPayment . '.is_approved', true)
                ->where($this->tblApplicantPayment . '.is_removed', false)
                ->join($this->tblApplicantExamination, $this->tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantExamination . '.is_removed', false)
                ->where($this->tblApplicantExamination . '.is_finish', true)
                ->join($this->tblApplicantOrientation, $this->tblApplicantOrientation . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantOrientation . '.is_completed', true)
                ->groupBy('applicant_accounts.id')/* ->orderBy($this->tblApplicantOrientationScheduled . '.created_at', 'desc') */;
        }
        if ($selectCategories == 'for_medical_schedule') {
            $dataLists =  $query->join($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($this->tblApplicantPayment, $this->tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantPayment . '.is_approved', true)
                ->where($this->tblApplicantPayment . '.is_removed', false)
                ->join($this->tblApplicantExamination, $this->tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantExamination . '.is_removed', false)
                ->where($this->tblApplicantExamination . '.is_finish', true)
                /* ->join($this->tblApplicantOrientation, $this->tblApplicantOrientation . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantOrientation . '.is_completed', true) */
                ->leftJoin(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', 'applicant_accounts.id')
                ->whereNull('ama.applicant_id')
                ->groupBy('applicant_accounts.id')/* ->orderBy($this->tblApplicantOrientationScheduled . '.created_at', 'desc') */;
        }
        if ($selectCategories == 'medical_schedule') {
            $dataLists =  $query->join($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($this->tblApplicantPayment, $this->tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantPayment . '.is_approved', true)
                ->where($this->tblApplicantPayment . '.is_removed', false)
                ->join($this->tblApplicantExamination, $this->tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantExamination . '.is_removed', false)
                ->where($this->tblApplicantExamination . '.is_finish', true)
                /* ->join($this->tblApplicantOrientation, $this->tblApplicantOrientation . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantOrientation . '.is_completed', true) */
                ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', env('DB_DATABASE_SECOND') . '.applicant_briefings.applicant_id')
                ->where('ama.is_removed', false)
                ->where('ama.is_approved', false)
                ->groupBy('applicant_accounts.id')/* ->orderBy($this->tblApplicantOrientationScheduled . '.created_at', 'desc') */;
        }
        if ($selectCategories == 'waiting_for_medical_results') {
            $dataLists =  $query->join($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($this->tblApplicantPayment, $this->tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantPayment . '.is_approved', true)
                ->where($this->tblApplicantPayment . '.is_removed', false)
                ->join($this->tblApplicantExamination, $this->tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantExamination . '.is_removed', false)
                ->where($this->tblApplicantExamination . '.is_finish', true)
                /* ->join($this->tblApplicantOrientation, $this->tblApplicantOrientation . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantOrientation . '.is_completed', true) */
                ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', env('DB_DATABASE_SECOND') . '.applicant_briefings.applicant_id')
                ->where('ama.is_removed', false)
                ->where('ama.is_approved', true)
                ->leftJoin(env('DB_DATABASE_SECOND') . '.applicant_medical_results', env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id', 'ama.applicant_id')
                ->whereNull(env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id')
                ->groupBy('applicant_accounts.id')/* ->orderBy($this->tblApplicantOrientationScheduled . '.created_at', 'desc') */;
        }
        if ($selectCategories == 'result') {
            $dataLists =  $query->join($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($this->tblApplicantPayment, $this->tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantPayment . '.is_approved', true)
                ->where($this->tblApplicantPayment . '.is_removed', false)
                ->join($this->tblApplicantExamination, $this->tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantExamination . '.is_removed', false)
                ->where($this->tblApplicantExamination . '.is_finish', true)
                /* ->join($this->tblApplicantOrientation, $this->tblApplicantOrientation . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantOrientation . '.is_completed', true) */
                ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', env('DB_DATABASE_SECOND') . '.applicant_briefings.applicant_id')
                ->where('ama.is_removed', false)
                ->where('ama.is_approved', true)
                ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_results', env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id', env('DB_DATABASE_SECOND') . '.applicant_briefings.applicant_id')
                ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_removed', false)
                ->groupBy('applicant_accounts.id')->orderBy(env('DB_DATABASE_SECOND') . '.applicant_medical_results.created_at', 'desc');
        }
        if ($selectCategories == 'fit') {
            $query =  $query->join($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($this->tblApplicantPayment, $this->tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantPayment . '.is_approved', true)
                ->where($this->tblApplicantPayment . '.is_removed', false)
                ->join($this->tblApplicantExamination, $this->tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantExamination . '.is_removed', false)
                ->where($this->tblApplicantExamination . '.is_finish', true)
                /*  ->join($this->tblApplicantOrientation, $this->tblApplicantOrientation . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantOrientation . '.is_completed', true) */
                ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', 'applicant_accounts.id')
                ->where('ama.is_removed', false)
                ->where('ama.is_approved', true)
                ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_results', env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id', 'applicant_accounts.id')
                ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_fit', 1)
                ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_removed', false)
                ->groupBy('applicant_accounts.id')->orderBy(env('DB_DATABASE_SECOND') . '.applicant_medical_results.created_at', 'desc');
        }
        if ($selectCategories == 'unfit') {
            $query =  $query->join($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($this->tblApplicantPayment, $this->tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantPayment . '.is_approved', true)
                ->where($this->tblApplicantPayment . '.is_removed', false)
                ->join($this->tblApplicantExamination, $this->tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantExamination . '.is_removed', false)
                ->where($this->tblApplicantExamination . '.is_finish', true)
                /*  ->join($this->tblApplicantOrientation, $this->tblApplicantOrientation . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantOrientation . '.is_completed', true) */
                ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', 'applicant_accounts.id')
                ->where('ama.is_removed', false)
                ->where('ama.is_approved', true)
                ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_results', env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id', 'applicant_accounts.id')
                ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_fit', 2)
                ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_removed', false)
                ->groupBy('applicant_accounts.id')->orderBy(env('DB_DATABASE_SECOND') . '.applicant_medical_results.created_at', 'desc');
        }
        if ($selectCategories == 'pending') {
            $query =  $query->join($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($this->tblApplicantPayment, $this->tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantPayment . '.is_approved', true)
                ->where($this->tblApplicantPayment . '.is_removed', false)
                ->join($this->tblApplicantExamination, $this->tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantExamination . '.is_removed', false)
                ->where($this->tblApplicantExamination . '.is_finish', true)
                /*  ->join($this->tblApplicantOrientation, $this->tblApplicantOrientation . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantOrientation . '.is_completed', true) */
                ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', 'applicant_accounts.id')
                ->where('ama.is_removed', false)
                ->where('ama.is_approved', true)
                ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_results', env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id', 'applicant_accounts.id')
                ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_pending', 0)
                ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_removed', false)
                ->groupBy('applicant_accounts.id')->orderBy(env('DB_DATABASE_SECOND') . '.applicant_medical_results.created_at', 'desc');
        }
        if ($selectCategories == 'qualified_for_enrollment') {
            $dataLists =  $query->join($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($this->tblApplicantPayment, $this->tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantPayment . '.is_approved', true)
                ->where($this->tblApplicantPayment . '.is_removed', false)
                ->join($this->tblApplicantExamination, $this->tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantExamination . '.is_removed', false)
                ->where($this->tblApplicantExamination . '.is_finish', true)
                /* ->join($this->tblApplicantOrientation, $this->tblApplicantOrientation . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantOrientation . '.is_completed', true) */
                ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', env('DB_DATABASE_SECOND') . '.applicant_briefings.applicant_id')
                ->where('ama.is_removed', false)
                ->where('ama.is_approved', true)
                ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_results', env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id', env('DB_DATABASE_SECOND') . '.applicant_briefings.applicant_id')
                ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_removed', false)
                ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_fit', true)
                ->groupBy('applicant_accounts.id')->orderBy(env('DB_DATABASE_SECOND') . '.applicant_medical_results.created_at', 'desc');
        }
        if ($selectCategories == 'non_pbm') {
            $query =  $query->join($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($this->tblApplicantPayment, $this->tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantPayment . '.is_approved', true)
                ->where($this->tblApplicantPayment . '.is_removed', false)
                ->join($this->tblApplicantExamination, $this->tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantExamination . '.is_removed', false)
                ->where($this->tblApplicantExamination . '.is_finish', true)
                ->join($this->tblApplicantOrientation, $this->tblApplicantOrientation . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantOrientation . '.is_completed', true)
                ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', 'applicant_accounts.id')
                ->where('ama.is_removed', false)
                ->where('ama.is_approved', true)
                ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_results', env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id', 'applicant_accounts.id')
                ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_removed', false)
                ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_fit', true)
                ->where('applicant_accounts.strand', '!=', 'Pre-Baccalaureate Maritime Strand')
                ->groupBy('applicant_accounts.id')->orderBy(env('DB_DATABASE_SECOND') . '.applicant_medical_results.created_at', 'desc');
        }
        if ($selectCategories == 'pbm') {
            $query =  $query->join($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($this->tblApplicantPayment, $this->tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantPayment . '.is_approved', true)
                ->where($this->tblApplicantPayment . '.is_removed', false)
                ->join($this->tblApplicantExamination, $this->tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantExamination . '.is_removed', false)
                ->where($this->tblApplicantExamination . '.is_finish', true)
                ->join($this->tblApplicantOrientation, $this->tblApplicantOrientation . '.applicant_id', 'applicant_accounts.id')
                ->where($this->tblApplicantOrientation . '.is_completed', true)
                ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', 'applicant_accounts.id')
                ->where('ama.is_removed', false)
                ->where('ama.is_approved', true)
                ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_results', env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id', 'applicant_accounts.id')
                ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_removed', false)
                ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_fit', true)
                ->where('applicant_accounts.strand', 'Pre-Baccalaureate Maritime Strand')
                ->groupBy('applicant_accounts.id')->orderBy(env('DB_DATABASE_SECOND') . '.applicant_medical_results.created_at', 'desc');
        }
        if ($searchInput != '') {

            if ($selectCategories == 'created_accounts') {
                $dataLists = $dataLists->where('name', 'like', '%' . $searchInput . '%')->orWhere('email', 'like', '%' . $searchInput . '%')
                    ->orderBy('created_at', 'desc');
            } else {
                $_student = explode(',', $searchInput); // Seperate the Sentence
                $_count = count($_student);
                #$query = $query->join($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id');
                if ($_count > 1) {
                    $dataLists = $dataLists->where($this->tblApplicantDetails . '.last_name', 'like', '%' . $_student[0] . '%')
                        ->where($this->tblApplicantDetails . '.first_name', 'like', '%' . trim($_student[1]) . '%')
                        ->orderBy($this->tblApplicantDetails . '.last_name', 'asc');
                } else {
                    $dataLists = $dataLists->where($this->tblApplicantDetails . '.last_name', 'like', '%' . $_student[0] . '%')
                        ->orderBy($this->tblApplicantDetails . '.last_name', 'asc');
                }
            }
        }
        return $dataLists->limit(20)->get();
    }
    function filterData($search, $course, $category, $academic)
    {

        $applicantAccountTable = env('DB_DATABASE') . '.applicant_accounts';
        $tblDocuments = env('DB_DATABASE') . '.documents';
        $tblApplicantDetails = env('DB_DATABASE_SECOND') . '.applicant_detials';
        $tblApplicantDocuments = env('DB_DATABASE_SECOND') . '.applicant_documents';
        $tblApplicantNotQualifieds =  env('DB_DATABASE_SECOND') . '.applicant_not_qualifieds';
        $tblApplicantPayment = env('DB_DATABASE_SECOND') . '.applicant_payments';
        $tblApplicantAlumia = env('DB_DATABASE_SECOND') . '.applicant_alumnias';
        $tblApplicantExamination = env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations';
        $query = ApplicantAccount::select('applicant_accounts.*')
            ->where('applicant_accounts.is_removed', false)
            ->where('applicant_accounts.academic_id', base64_decode($academic));
        // Courses Sorting
        if ($course != 'ALL COURSE') {
            $query = $query->where('applicant_accounts.course_id', $course);
        }
        // Search Sorting
        if ($search != '') {

            if ($category == 'created_accounts') {
                $query->where('name', 'like', '%' . $search . '%')->orWhere('email', 'like', '%' . $search . '%')
                    ->orderBy('created_at', 'desc');
            } else {
                $_student = explode(',', $search); // Seperate the Sentence
                $_count = count($_student);
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
        // Category Sorting
        if ($category == 'created_accounts') {
            $query->leftJoin($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->whereNull($tblApplicantDetails . '.applicant_id');
        } else if ($category == 'registered_applicants_v1') {
            $query
                ->join($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->leftJoin($tblApplicantDocuments, $tblApplicantDocuments . '.applicant_id', 'applicant_accounts.id')
                ->whereNull($tblApplicantDocuments . '.applicant_id');
        } else if ($category == 'total_registrants') {
            $query->join($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->orderBy($tblApplicantDetails . '.created_at', 'desc');
        } else {
            $query->join($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->where('applicant_accounts.is_removed', false)
                ->where('applicant_accounts.academic_id', base64_decode($academic));
            // Sort By Courses
            if ($category == 'registered_applicants_v1') {
                $query = $query->leftJoin($tblApplicantDocuments, $tblApplicantDocuments . '.applicant_id', 'applicant_accounts.id')
                    ->whereNull($tblApplicantDocuments . '.applicant_id');
            } else if ($category == 'senior_high_school_alumni') {
                $query = $query->join($tblApplicantAlumia, $tblApplicantAlumia . '.applicant_id', 'applicant_accounts.id')
                    ->where($tblApplicantAlumia . '.is_removed', false);
            } else if ($category != 'registered_applicants_v1' || $category == 'senior_high_school_alumni') {
                // Applicant Documents Table
                $query = $query->join($tblApplicantDocuments, $tblApplicantDocuments . '.applicant_id', '=', 'applicant_accounts.id');
                if ($category != 'disapproved') {
                    // Quailified Applicant's
                    $query = $query->leftJoin($tblApplicantNotQualifieds . ' as anq', 'anq.applicant_id', 'applicant_accounts.id')
                        ->whereNull('anq.applicant_id')
                        ->groupBy('applicant_accounts.id');
                    if ($category == 'registered_applicants') {
                        // List of Applicants, That Completed All Documents
                        $query = $query->select(
                            'applicant_accounts.*',
                            DB::raw('(SELECT COUNT(*) FROM ' . $tblApplicantDocuments . ' WHERE ' . $tblApplicantDocuments . '.applicant_id = applicant_accounts.id AND ' . $tblApplicantDocuments . '.is_removed = 0 AND ' . $tblApplicantDocuments . '.is_approved = 1) AS ApprovedDocuments'),
                            DB::raw('(SELECT COUNT(*) FROM ' . $tblApplicantDocuments . ' WHERE ' . $tblApplicantDocuments . '.applicant_id = applicant_accounts.id AND ' . $tblApplicantDocuments . '.is_removed = 0 AND (' . $tblApplicantDocuments . '.is_approved is null or ' . $tblApplicantDocuments . '.is_approved = 1)) AS applicantDocuments'),
                            DB::raw('(SELECT COUNT(*) FROM ' . $tblDocuments . ' WHERE ' . $tblDocuments . '.department_id = 2 AND ' . $tblDocuments . '.is_removed = false AND ' . $tblDocuments . '.year_level = (SELECT IF(' . $applicantAccountTable . '.course_id = 3, 11, 4) FROM ' . $applicantAccountTable . ' WHERE ' . $applicantAccountTable . '.id = ' . $tblApplicantDetails . '.applicant_id)) as documentCount')
                        )->havingRaw('applicantDocuments >= documentCount and ApprovedDocuments < documentCount');
                    } else if ($category == 'pending') {
                        // Disapproved Document
                        $query = $query->where($tblApplicantDocuments . '.is_approved', 2)
                            ->where($tblApplicantDocuments . '.is_removed', false);
                    } else if ($category == 'approved') {
                        // Qualified Applicants
                        $query = $query->select(
                            'applicant_accounts.*',
                            DB::raw('(SELECT COUNT(*) FROM ' . $tblApplicantDocuments . ' WHERE ' . $tblApplicantDocuments . '.applicant_id = applicant_accounts.id AND ' . $tblApplicantDocuments . '.is_removed = 0 AND ' . $tblApplicantDocuments . '.is_approved = 1) AS ApprovedDocuments'),
                            DB::raw('(SELECT COUNT(*) FROM ' . $tblDocuments . ' WHERE ' . $tblDocuments . '.department_id = 2 AND ' . $tblDocuments . '.is_removed = false AND ' . $tblDocuments . '.year_level = (SELECT IF(' . $applicantAccountTable . '.course_id = 3, 11, 4) FROM ' . $applicantAccountTable . ' WHERE ' . $applicantAccountTable . '.id = ' . $tblApplicantDetails . '.applicant_id)) as documentCount')
                        )
                            ->havingRaw('COUNT(' . $tblApplicantDocuments . '.applicant_id) >= documentCount and ApprovedDocuments = documentCount');
                    } else {
                        // For Entrance Examination Payment
                        $query = $query->select(
                            'applicant_accounts.*',
                            DB::raw('(SELECT COUNT(*) FROM ' . $tblApplicantDocuments . ' WHERE ' . $tblApplicantDocuments . '.applicant_id = applicant_accounts.id AND ' . $tblApplicantDocuments . '.is_removed = 0 AND ' . $tblApplicantDocuments . '.is_approved = 1) AS ApprovedDocuments'),
                            DB::raw('(SELECT COUNT(*) FROM ' . $tblDocuments . ' WHERE ' . $tblDocuments . '.department_id = 2 AND ' . $tblDocuments . '.is_removed = false AND ' . $tblDocuments . '.year_level = (SELECT IF(' . $applicantAccountTable . '.course_id = 3, 11, 4) FROM ' . $applicantAccountTable . ' WHERE ' . $applicantAccountTable . '.id = ' . $tblApplicantDetails . '.applicant_id)) as documentCount')
                        )
                            ->havingRaw('COUNT(' . $tblApplicantDocuments . '.applicant_id) >= documentCount and ApprovedDocuments = documentCount');
                        // For Examination Payment
                        if ($category == 'examination_payment') {
                            $query = $query->join($tblApplicantPayment, $tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                                ->where(function ($query) {
                                    $query->whereNull(env('DB_DATABASE_SECOND') . '.applicant_payments.is_approved')
                                        ->orWhere(env('DB_DATABASE_SECOND') . '.applicant_payments.is_approved', false);
                                })
                                ->where($tblApplicantPayment . '.is_removed', false)
                                ->havingRaw('COUNT(' . $tblApplicantDocuments . '.applicant_id) >= documentCount and ApprovedDocuments = documentCount');
                        } else {
                            // Entrance Examination , Passed and Failed
                            $query = $query->join($tblApplicantPayment, $tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                                ->where($tblApplicantPayment . '.is_approved', true)
                                ->where($tblApplicantPayment . '.is_removed', false)
                                ->join($tblApplicantExamination, $tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                                ->where($tblApplicantExamination . '.is_removed', false);
                            if ($category == 'entrance_examination') {
                                $query = $query->whereNull($tblApplicantExamination . '.is_finish')
                                    ->groupBy($tblApplicantExamination . '.applicant_id');
                            } else if ($category == 'passed' || $category == 'failed') {
                                // Entrance Examination
                                $operation = $category == 'passed' ? '>=' : '<';
                                $query = $query->where($tblApplicantExamination . '.is_removed', false)
                                    ->where($tblApplicantExamination . '.is_finish', true)
                                    ->where(function ($query) {
                                        $query->select(DB::raw('COUNT(*)'))
                                            ->from(env('DB_DATABASE_SECOND') . '.applicant_examination_answers')
                                            ->join(env('DB_DATABASE') . '.examination_question_choices', env('DB_DATABASE') . '.examination_question_choices.id', '=', env('DB_DATABASE_SECOND') . '.applicant_examination_answers' . '.choices_id')
                                            ->where(env('DB_DATABASE') . '.examination_question_choices.is_answer', true)
                                            ->whereColumn(env('DB_DATABASE_SECOND') . '.applicant_examination_answers' . '.examination_id', env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.id');
                                    }, $operation, function ($query) {
                                        $query->select(DB::raw('IF(applicant_accounts.course_id = 3, 20, 100)'));
                                    })
                                    ->groupBy('applicant_accounts.id')->orderBy($tblApplicantExamination . '.updated_at', 'desc');
                            } else {
                                $query = $query->where($tblApplicantExamination . '.is_removed', false)
                                    ->where($tblApplicantExamination . '.is_finish', true)
                                    ->where(function ($query) {
                                        $query->select(DB::raw('COUNT(*)'))
                                            ->from(env('DB_DATABASE_SECOND') . '.applicant_examination_answers')
                                            ->join(env('DB_DATABASE') . '.examination_question_choices', env('DB_DATABASE') . '.examination_question_choices.id', '=', env('DB_DATABASE_SECOND') . '.applicant_examination_answers' . '.choices_id')
                                            ->where(env('DB_DATABASE') . '.examination_question_choices.is_answer', true)
                                            ->whereColumn(env('DB_DATABASE_SECOND') . '.applicant_examination_answers' . '.examination_id', env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.id');
                                    }, '>=', function ($query) {
                                        $query->select(DB::raw('IF(applicant_accounts.course_id = 3, 20, 100)'));
                                    })->groupBy('applicant_accounts.id');
                                if ($category === 'for_medical_schedule') {
                                    $query =  $query->leftJoin(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', 'applicant_accounts.id')
                                        ->whereNull('ama.applicant_id');
                                } else if ($category === 'waiting_for_medical_results') {
                                    $query =
                                        $query->join(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', 'applicant_accounts.id')
                                        ->where('ama.is_removed', false)
                                        ->where('ama.is_approved', true)
                                        ->leftJoin(env('DB_DATABASE_SECOND') . '.applicant_medical_results', env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id', 'ama.applicant_id')
                                        ->whereNull(env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id');
                                } else {
                                    $query->join(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', 'applicant_accounts.id')
                                        ->where('ama.is_removed', false)
                                        ->where('ama.is_approved', true)
                                        ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_results', env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id', 'applicant_accounts.id')
                                        ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_removed', false)
                                        ->orderBy(env('DB_DATABASE_SECOND') . '.applicant_medical_results.created_at', 'desc');
                                    if ($category == 'fit') {
                                        $query->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_fit', 1);
                                    } elseif ($category == 'unfit') {
                                        $query->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_fit', 2);
                                    } elseif ($category == 'pending_result') {
                                        $query->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_pending', 0);
                                    } else {
                                        if ($category == 'qualified_for_enrollment') {
                                            $query->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_fit', true)
                                                ->groupBy('applicant_accounts.id')
                                                ->orderBy(env('DB_DATABASE_SECOND') . '.applicant_medical_results.created_at', 'desc');
                                        } elseif ($category == 'non_pbm' || $category == 'pbm') {
                                            if ($category == 'non_pbm') {
                                                $query->where('applicant_accounts.strand', '!=', 'Pre-Baccalaureate Maritime Strand');
                                            } elseif ($category == 'pbm') {
                                                $query->where('applicant_accounts.strand', 'Pre-Baccalaureate Maritime Strand');
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    // Not Qualified Applicants
                    $query = $query->join($tblApplicantNotQualifieds, $tblApplicantNotQualifieds . '.applicant_id', $applicantAccountTable . '.id')
                        ->where($tblApplicantNotQualifieds . '.is_removed', false)
                        ->where($tblApplicantNotQualifieds . '.academic_id', base64_decode($academic))
                        ->groupBy('applicant_accounts.id');
                }
            }
        }
        return $query->limit(10)->get();
    }
    // Sort Data
    function registered_appplicants()
    {
    }
}
