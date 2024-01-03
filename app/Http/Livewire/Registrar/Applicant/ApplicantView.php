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
        $dataLists = $this->filterApplicantData($this->searchInput, $this->selectCourse, $this->selectCategories, $this->academic);
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
        return  array(
            array('User Accounts', array('created_accounts', 'registered_applicants', 'total_registrants')),
            array('Information Verification', array('for_checking', 'not_qualified', 'qualified', 'no_of_qualified_examinees')),
            array('Aluminus', array('bma_senior_high')),
            array('Entrance Examination', array('examination_payment', 'entrance_examination', 'examination_passed', 'examination_failed', 'took_the_exam')),
            /*   array('Briefing Orientation', array('expected_attendees', 'total_attendees')), */
            array('Medical Examination', array('for_medical_schedule', 'medical_schedule', 'waiting_for_medical_results', 'medical_result')),
            array('Enrollment', array('qualified_to_enrollment'))
        );
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
            $data = request()->query('_category') ?: $this->selectCategories;
        }
        Cache::put('category', $data, 120);
        if (Cache::has('category')) {
            $data = Cache::get('category');
        }
        #$data = $data ?: 'created_accounts';
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
        return $dataLists->get();
    }
}
