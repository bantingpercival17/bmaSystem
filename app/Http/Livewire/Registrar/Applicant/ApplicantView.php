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
        $filterData = [$this->searchInput, $this->selectCourse, $this->selectCategories, $this->academic];
        return view('livewire.registrar.applicant.applicant-view', compact('filterContent', 'filterCourses', 'dataLists', 'filterData'));
    }
    function filterContent()
    {
        $reguralUser =  array(
            array('Information Verification', array('registered_applicants', 'approved', 'disapproved', 'pending', 'senior_high_school_alumni')),
            array('Entrance Examination', array('waiting_examination_payment', 'examination_payment', 'entrance_examination', 'passed', 'failed')),
            array('Medical Examination', array('shs_alumia_for_medical_schedule', 'for_medical_schedule', 'waiting_for_medical_results', 'fit', 'unfit', 'pending_result')),
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
    function dataFilter($search, $course, $category, $academic)
    {
        $dataList = [];
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
        // Course Filtering
        if ($course !== 'ALL COURSE') {
            $dataList = $dataList->where('applicant_accounts.course_id', $course);
        }
        if ($category === 'created_accounts') {
            $dataList = $dataList->leftJoin($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->whereNull($tblApplicantDetails . '.applicant_id');
        } else if ($category === 'registered_applicants_v1') {
            $dataList = $dataList->join($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->leftJoin($tblApplicantDocuments, $tblApplicantDocuments . '.applicant_id', 'applicant_accounts.id')
                ->whereNull($tblApplicantDocuments . '.applicant_id')
                ->orderBy('applicant_accounts.created_at', 'desc');
        } elseif ($category == 'total_registrants') {
            $dataList = $dataList->join($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->orderBy($tblApplicantDetails . '.created_at', 'desc');
        } elseif ($category == 'registered_applicants') {
            $dataList = $dataList->join($tblApplicantDocuments, $tblApplicantDocuments . '.applicant_id', 'applicant_accounts.id')
                ->select(
                    'applicant_accounts.id',
                    'applicant_accounts.name',
                    'applicant_accounts.email',
                    'applicant_accounts.course_id',
                    'applicant_accounts.academic_id',
                    'applicant_accounts.created_at'
                )
                ->withCount([
                    'documentApproved',
                    'applicantDocuments as applicant_documents_count' => function ($query) {
                        $query->where('applicant_documents.is_removed', 0)
                            ->join('documents', 'documents.id', '=', 'applicant_documents.document_id')
                            ->where('documents.is_removed', false)
                            ->where(function ($query) {
                                $query->where('applicant_documents.is_approved', 1)
                                    ->orWhereNull('applicant_documents.is_approved');
                            });
                    },
                    'applicantDocuments as disapproved_documents_count' => function ($query) {
                        $query->where('is_removed', 0)
                            ->where('is_approved', 2);
                    },
                ])->havingRaw('applicant_documents_count >= 6')
                ->havingRaw('document_approved_count < 6')
                ->havingRaw('disapproved_documents_count <= 0')
                /* ->select(
                    'applicant_accounts.*',
                    //DB::raw('(SELECT COUNT(*) FROM ' . $tblApplicantDocuments . ' INNER JOIN ' . $tblDocuments . ' ON ' . $tblDocuments . '.id = ' . $tblApplicantDocuments . '.document_id WHERE ' . $tblApplicantDocuments . '.applicant_id = applicant_accounts.id AND ' . $tblApplicantDocuments . '.is_removed = 0 AND ' . $tblApplicantDocuments . '.is_approved = 1 AND ' . $tblDocuments . '.is_removed = false) AS ApprovedDocuments'),
                    //DB::raw('(SELECT COUNT(*) FROM ' . $tblApplicantDocuments . ' WHERE ' . $tblApplicantDocuments . '.applicant_id = applicant_accounts.id AND ' . $tblApplicantDocuments . '.is_removed = 0 AND ' . $tblApplicantDocuments . '.is_approved = 1) AS ApprovedDocuments'),
                    DB::raw('(SELECT COUNT(*) FROM ' . $tblApplicantDocuments . ' WHERE ' . $tblApplicantDocuments . '.applicant_id = applicant_accounts.id AND ' . $tblApplicantDocuments . '.is_removed = false AND ' . $tblApplicantDocuments . '.is_approved = 2) AS DisapprovedDocuments'),
                    DB::raw('(SELECT COUNT(*) FROM ' . $this->tblApplicantDocuments . ' INNER JOIN ' . $this->tblDocuments . ' ON ' . $this->tblDocuments . '.id = ' . $this->tblApplicantDocuments . '.document_id WHERE ' . $this->tblApplicantDocuments . '.applicant_id = applicant_accounts.id AND ' . $this->tblApplicantDocuments . '.is_removed = 0 AND ' . $this->tblDocuments . '.is_removed = false AND (' . $this->tblApplicantDocuments . '.is_approved IS NULL OR ' . $this->tblApplicantDocuments . '.is_approved = 1)) AS applicantDocuments'),

                    //DB::raw('(SELECT COUNT(*) FROM ' . $tblApplicantDocuments . ' INNWHERE ' . $tblApplicantDocuments . '.applicant_id = applicant_accounts.id AND ' . $tblApplicantDocuments . '.is_removed = false AND (' . $tblApplicantDocuments . '.is_approved is null or ' . $tblApplicantDocuments . '.is_approved = 1)) AS applicantDocuments'),
                    DB::raw('(SELECT COUNT(*) FROM ' . $tblDocuments . ' WHERE ' . $tblDocuments . '.department_id = 2 AND ' . $tblDocuments . '.is_removed = false AND ' . $tblDocuments . '.year_level = (SELECT IF(' . $applicantAccountTable . '.course_id = 3, 11, 4) FROM ' . $applicantAccountTable . ' WHERE ' . $this->applicantAccountTable . '.id = ' . $this->tblApplicantDocuments . '.applicant_id)) as documentCount')
                )
                //->withCount('applicantDocuments')
                ->withCount('documentApprovedV2')
                ->havingRaw('applicantDocuments >= documentCount and documentCount > document_approved_v2_count and DisapprovedDocuments <= 0')
                 */->leftJoin($tblApplicantNotQualifieds, $tblApplicantNotQualifieds . '.applicant_id', 'applicant_accounts.id')
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
            $dataList = $dataList->leftJoin($tblApplicantNotQualifieds, $tblApplicantNotQualifieds . '.applicant_id', 'applicant_accounts.id')
                ->whereNull($tblApplicantNotQualifieds . '.applicant_id')
                ->join($tblApplicantDocuments, 'applicant_documents.applicant_id', '=', 'applicant_accounts.id')
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
            $dataList = //$this->examination_result($dataList, '>=')
                $this->examination_result_v2($dataList, true)
                ->orderBy($tblApplicantExamination . '.examination_start', 'desc')
                ->groupBy('applicant_accounts.id');
        } elseif ($category == 'passed_v2') {
            $dataList = $this->examination_result_v2($dataList, true)
                ->orderBy($tblApplicantExamination . '.examination_start', 'desc')
                ->groupBy('applicant_accounts.id');
        } elseif ($category == 'failed') {
            $dataList = // $this->examination_result($dataList, '<')
                $this->examination_result_v2($dataList, false)
                ->orderBy($tblApplicantExamination . '.examination_start', 'desc')
                ->groupBy('applicant_accounts.id');
        }
        /*  elseif ('shs_alumia_for_medical_schedule') {
            $dataList = $this->senior_high_alumia($dataList)
                ->leftJoin($tblApplicantMedicalScheduled, $tblApplicantMedicalScheduled . '.applicant_id', 'applicant_accounts.id')
                ->whereNull($tblApplicantMedicalScheduled . '.applicant_id')
                ->groupBy('applicant_accounts.id');
        } */ elseif ($category == 'shs_alumia_for_medical_schedule') {
            $dataList = $this->senior_high_alumia($dataList)
                ->leftJoin($tblApplicantMedicalScheduled, $tblApplicantMedicalScheduled . '.applicant_id', 'applicant_accounts.id')
                ->whereNull($tblApplicantMedicalScheduled . '.applicant_id')
                ->groupBy('applicant_accounts.id');
        } elseif ($category == 'for_medical_schedule') {
            $dataList = $this->examination_result_v2($dataList, true)/* ->union($this->senior_high_alumia($dataList)) */
                ->leftJoin($tblApplicantMedicalScheduled, $tblApplicantMedicalScheduled . '.applicant_id', 'applicant_accounts.id')
                ->whereNull($tblApplicantMedicalScheduled . '.applicant_id')
                ->orderBy($tblApplicantExamination . '.examination_start', 'desc')
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
    function examination_result_v2($data, $status)
    {
        $tblApplicantExamination = env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations';
        $tblApplicantExaminationResult = env('DB_DATABASE_SECOND') . '.applicant_entrance_examination_results';
        return $data->join($tblApplicantExamination, $tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
            ->join($tblApplicantExaminationResult, $tblApplicantExaminationResult . '.examination_id', $tblApplicantExamination . '.id')
            ->where($tblApplicantExamination . '.is_removed', false)
            ->where($tblApplicantExamination . '.is_finish', true)
            ->where($tblApplicantExaminationResult . '.is_removed', false)
            ->where($tblApplicantExaminationResult . '.result', $status);
    }
    function medical_result($query, $result)
    {
        $tblApplicantMedicalResult = env('DB_DATABASE_SECOND') . '.applicant_medical_results';
        return $query->join($tblApplicantMedicalResult, $tblApplicantMedicalResult . '.applicant_id', 'applicant_accounts.id')
            ->where($tblApplicantMedicalResult . '.is_removed', false)
            ->where($tblApplicantMedicalResult . '.is_fit', $result)
            ->orderBy($tblApplicantMedicalResult . '.created_at', 'desc')
            ->groupBy('applicant_accounts.id');
    }
    function filter_category($query, $search, $course, $category)
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
}
