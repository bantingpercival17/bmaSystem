<?php

namespace App\Http\Livewire\Registrar\Applicant;

use App\Models\AcademicYear;
use App\Models\ApplicantAccount;
use App\Models\CourseOffer;
use App\Models\Documents;
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
        $filterCourses = CourseOffer::all();
        $this->academic = $this->academicValue();
        $this->selectCourse = $this->getCourse();
        $this->selectCategories = $this->getCategories();
        $dataLists = $this->dataFilter($this->searchInput, $this->selectCourse, $this->selectCategories, $this->academic);
        return view('livewire.registrar.applicant.applicant-view', compact('filterContent', 'filterCourses', 'dataLists'));
    }
    function filterContent()
    {
        $reguralUser =  array(
            array('Information Verification', array('registered_applicants', 'approved', 'disapproved', 'pending', 'senior_high_school_alumni')),
            array('Entrance Examination', array('waiting_examination_payment', 'examination_payment', 'entrance_examination', 'passed', 'failed')),
            array('Medical Examination', array('for_medical_schedule', 'waiting_for_medical_results', 'fit', 'unfit', 'pending_result')),
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
        if (request()->query('_academic')) {
            return request()->query('_academic');
        }
        if (empty($this->academic)) {
            $activeAcademic = AcademicYear::where('is_active', 1)->first();
            if ($activeAcademic) {
                return base64_encode($activeAcademic->id);
            }
        }
    }
    function getCourse()
    {
        $data = $this->selectCourse;
        if (request()->query('_course')) {
            $data = base64_decode(request()->query('_course')) ?: $this->selectCourse;
        }
        $this->selectCourse  = $data;
        $this->categoryCourse();
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
        return $query->get();
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
        $dataList = $this->filter_category($dataList, $search, $course, $category);

        if ($category == 'total_registrants') {
            $dataList = $dataList->join($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->orderBy($tblApplicantDetails . '.created_at', 'desc');
        } elseif ($category == 'registered_applicants_v1') {
            $dataList = $dataList->join($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->leftJoin($tblApplicantDocuments, $tblApplicantDocuments . '.applicant_id', 'applicant_accounts.id')
                ->whereNull($tblApplicantDocuments . '.applicant_id');
        } elseif ($category == 'registered_applicants') {
            $dataList = $dataList->join($tblApplicantDocuments, $tblApplicantDocuments . '.applicant_id', 'applicant_accounts.id')

                ->select(
                    'applicant_accounts.*',
                    //DB::raw('(SELECT COUNT(*) FROM ' . $tblApplicantDocuments . ' INNER JOIN ' . $tblDocuments . ' ON ' . $tblDocuments . '.id = ' . $tblApplicantDocuments . '.document_id WHERE ' . $tblApplicantDocuments . '.applicant_id = applicant_accounts.id AND ' . $tblApplicantDocuments . '.is_removed = 0 AND ' . $tblApplicantDocuments . '.is_approved = 1 AND ' . $tblDocuments . '.is_removed = false) AS ApprovedDocuments'),
                    //DB::raw('(SELECT COUNT(*) FROM ' . $tblApplicantDocuments . ' WHERE ' . $tblApplicantDocuments . '.applicant_id = applicant_accounts.id AND ' . $tblApplicantDocuments . '.is_removed = 0 AND ' . $tblApplicantDocuments . '.is_approved = 1) AS ApprovedDocuments'),
                    DB::raw('(SELECT COUNT(*) FROM ' . $tblApplicantDocuments . ' WHERE ' . $tblApplicantDocuments . '.applicant_id = applicant_accounts.id AND ' . $tblApplicantDocuments . '.is_removed = 0 AND ' . $tblApplicantDocuments . '.is_approved = 2) AS DisapprovedDocuments'),
                    DB::raw('(SELECT COUNT(*) FROM ' . $tblApplicantDocuments . ' WHERE ' . $tblApplicantDocuments . '.applicant_id = applicant_accounts.id AND ' . $tblApplicantDocuments . '.is_removed = 0 AND (' . $tblApplicantDocuments . '.is_approved is null or ' . $tblApplicantDocuments . '.is_approved = 1)) AS applicantDocuments'),
                    DB::raw('(SELECT COUNT(*) FROM ' . $tblDocuments . ' WHERE ' . $tblDocuments . '.department_id = 2 AND ' . $tblDocuments . '.is_removed = false AND ' . $tblDocuments . '.year_level = (SELECT IF(' . $applicantAccountTable . '.course_id = 3, 11, 4) FROM ' . $applicantAccountTable . ' WHERE ' . $this->applicantAccountTable . '.id = ' . $this->tblApplicantDocuments . '.applicant_id)) as documentCount')
                )
                ->withCount('documentApprovedV2')
                ->havingRaw('applicantDocuments >= documentCount and documentCount > document_approved_v2_count and DisapprovedDocuments <= 0')
                ->leftJoin($tblApplicantNotQualifieds, $tblApplicantNotQualifieds . '.applicant_id', 'applicant_accounts.id')
                ->whereNull($tblApplicantNotQualifieds . '.applicant_id')
                ->groupBy('applicant_accounts.id')
                ->orderBy('applicant_accounts.updated_at', 'desc');
        } elseif ($category == 'approved') {
            $dataList = $dataList->join($tblApplicantDocuments, $tblApplicantDocuments . '.applicant_id', 'applicant_accounts.id')
                ->select(
                    'applicant_accounts.*',
                    DB::raw('(SELECT COUNT(*) FROM ' . $tblApplicantDocuments . ' WHERE ' . $tblApplicantDocuments . '.applicant_id = applicant_accounts.id AND ' . $tblApplicantDocuments . '.is_removed = 0 AND ' . $tblApplicantDocuments . '.is_approved = 1) AS ApprovedDocuments'),
                    DB::raw('(SELECT COUNT(*) FROM ' . $tblDocuments . ' WHERE ' . $tblDocuments . '.department_id = 2 AND ' . $tblDocuments . '.is_removed = false AND ' . $tblDocuments . '.year_level = (SELECT IF(' . $applicantAccountTable . '.course_id = 3, 11, 4) FROM ' . $applicantAccountTable . ' WHERE ' . $applicantAccountTable . '.id = ' . $tblApplicantDocuments . '.applicant_id)) as documentCount')
                )
                ->havingRaw('COUNT(' . $tblApplicantDocuments . '.applicant_id) >= documentCount and ApprovedDocuments >= documentCount')
                ->leftJoin($tblApplicantNotQualifieds, $tblApplicantNotQualifieds . '.applicant_id', 'applicant_accounts.id')
                ->whereNull($tblApplicantNotQualifieds . '.applicant_id')
                ->leftJoin($tblApplicantAlumia, $tblApplicantAlumia . '.applicant_id', 'applicant_accounts.id')
                ->whereNull($tblApplicantAlumia . '.applicant_id')
                ->groupBy('applicant_accounts.id')
                ->orderBy($tblApplicantDocuments . '.updated_at', 'desc');
        } elseif ($category == 'pending') {
            $dataList = $dataList->join($tblApplicantDocuments, 'applicant_documents.applicant_id', '=', 'applicant_accounts.id')
                ->where($tblApplicantDocuments . '.is_approved', 2)
                ->where($tblApplicantDocuments . '.is_removed', false)
                ->groupBy('applicant_accounts.id')
                ->orderBy($tblApplicantDocuments . '.updated_at', 'desc');
        } elseif ($category == 'disapproved') {
            $dataList =  $dataList->join($tblApplicantNotQualifieds, $tblApplicantNotQualifieds . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantNotQualifieds . '.is_removed', false)
                ->where($tblApplicantNotQualifieds . '.academic_id', base64_decode($academic))
                ->groupBy('applicant_accounts.id')
                ->orderBy($tblApplicantNotQualifieds . '.created_at', 'desc');
        } elseif ($category == 'senior_high_school_alumni') {
            $dataList = $dataList->join($tblApplicantAlumia, $tblApplicantAlumia . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantAlumia . '.is_removed', false)
                ->orderBy('applicant_accounts.created_at', 'desc');
        } elseif ($category == 'waiting_examination_payment') {
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
        } elseif ($category == 'examination_payment') {
            $dataList = $dataList->join($tblApplicantPayment, $tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->where(function ($query) {
                    $query->whereNull(env('DB_DATABASE_SECOND') . '.applicant_payments.is_approved')
                        ->orWhere(env('DB_DATABASE_SECOND') . '.applicant_payments.is_approved', false);
                })
                ->where($tblApplicantPayment . '.is_removed', false)
                ->orderBy($tblApplicantPayment . '.updated_at', 'desc');
        } elseif ($category == 'entrance_examination') {
            $dataList->join($tblApplicantExamination, $tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                ->orderBy($tblApplicantExamination . '.created_at', 'desc')
                ->whereNull($tblApplicantExamination . '.is_finish');
        } elseif ($category == 'passed') {
            $dataList = $this->examination_result($dataList, '>=')
                ->orderBy($tblApplicantExamination . '.updated_at', 'desc');
        } elseif ($category == 'failed') {
            $dataList = $this->examination_result($dataList, '<')
                ->orderBy($tblApplicantExamination . '.updated_at', 'desc');
        } elseif ($category == 'for_medical_schedule') {
            $dataList = $dataList = $this->examination_result($dataList, '>=')/* ->union($this->senior_high_alumia($dataList)) */
                ->leftJoin($tblApplicantMedicalScheduled, $tblApplicantMedicalScheduled . '.applicant_id', 'applicant_accounts.id')
                ->whereNull($tblApplicantMedicalScheduled . '.applicant_id')
                ->groupBy('applicant_accounts.id');
        } elseif ($category == 'waiting_for_medical_results') {
            $dataList->join($tblApplicantMedicalScheduled, $tblApplicantMedicalScheduled . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantMedicalScheduled . '.is_removed', false)
                ->leftJoin($tblApplicantMedicalResult, $tblApplicantMedicalResult . '.applicant_id', 'applicant_accounts.id')
                ->whereNull($tblApplicantMedicalResult . '.applicant_id')
                ->groupBy('applicant_accounts.id');
        } elseif ($category == 'fit') {
            $dataList =  $this->medical_result($dataList, 1);
        } elseif ($category == 'unfit') {
            $dataList =   $this->medical_result($dataList, 2);
        } elseif ($category == 'pending_result') {
            $dataList->join($tblApplicantMedicalResult, $tblApplicantMedicalResult . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantMedicalResult . '.is_removed', false)
                ->where($tblApplicantMedicalResult . '.is_pending', 0)
                ->orderBy($tblApplicantMedicalResult . '.created_at', 'desc');
        } elseif ($category == 'qualified_for_enrollment') {
            $dataList =  $this->medical_result($dataList, 1);
        } elseif ($category == 'non_pbm') {
            $dataList =  $this->medical_result($dataList, 1)
                ->where('applicant_accounts.strand', '!=', 'Pre-Baccalaureate Maritime Strand');
        } elseif ($category ==  'pbm') {
            $dataList =  $this->medical_result($dataList, 1)
                ->where('applicant_accounts.strand', 'Pre-Baccalaureate Maritime Strand');
        } else {
            $dataList;
        }

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
        // Course Filtering
        if ($course !== 'ALL COURSE') {
            $query = $query->where('applicant_accounts.course_id', $course);
        }

        // Search Sorting
        if ($search !== '') {
            if ($category === 'created_accounts') {
                return $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                })->orderBy('created_at', 'desc');
            } else {
                $tblApplicantDetails = env('DB_DATABASE_SECOND') . '.applicant_detials';
                $query = $query->join($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', '=', 'applicant_accounts.id');

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
}
