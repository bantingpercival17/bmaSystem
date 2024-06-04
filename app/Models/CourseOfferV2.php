<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseOfferV2 extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'course_offers';
    protected $fillable = ['course_name', 'course_code', 'school_level', 'is_removed'];
    public $tblApplicantAccount;
    public $applicantAccountTable;
    public $tblDocuments;
    public $tblApplicantDetails;
    public $tblApplicantDocuments;
    public $tblApplicantNotQualifieds;
    public $tblApplicantPayment;
    public $tblApplicantAlumia;
    public $tblApplicantExamination;
    public $tblApplicantExaminationResult;
    public $tblApplicantExaminationAnswer;
    public $tblApplicantOrientationScheduled;
    public $tblApplicantOrientation;
    public $tblApplicantMedicalScheduled;
    public $tblApplicantMedicalResult;
    public function __construct()
    {
        $this->tblApplicantAccount = env('DB_DATABASE') . '.applicant_accounts';
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
        $this->tblApplicantMedicalScheduled = env('DB_DATABASE_SECOND') . '.applicant_medical_appointments';
        $this->tblApplicantMedicalResult = env('DB_DATABASE_SECOND') . '.applicant_medical_results';
        $this->tblApplicantExaminationResult = env('DB_DATABASE_SECOND') . '.applicant_entrance_examination_results';
    }

    function headers()
    {
        $tableHeader = array(
            array('Information Verification', array('registered_applicants', 'approved', 'disapproved', 'pending', 'senior_high_school_alumni'), 'applicants.summary-reports'),
            array('Entrance Examination', array('examination_payment', 'entrance_examination', 'passed', 'failed'), 'applicants.summary-reports'),
            array('Medical Examination', array('for_medical_schedule', 'waiting_for_medical_results', 'fit', 'unfit', 'pending_result'), 'applicants.summary-reports'),
            array('Enrollment', array('qualified_for_enrollment', 'non_pbm', 'pbm'), 'applicants.summary-reports')

        );
    }
    function applicant_account()
    {
        $academic = 10;
        return $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select($this->tblApplicantAccount . '.*')
            ->where($this->tblApplicantAccount . '.is_removed', false)
            ->where($this->tblApplicantAccount . '.academic_id', $academic);
    }
    function applicants_details()
    {
        return $this->applicant_account()
            ->join($this->tblApplicantDetails, $this->tblApplicantDetails . '.applicant_id', 'applicant_accounts.id');
    }
    function applicant_account_v2()
    {
        $academic = 10;
        return $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*')
            ->where('applicant_accounts.is_removed', false)
            ->where('applicant_accounts.academic_id', $academic)
            ->leftJoin($this->tblApplicantNotQualifieds, $this->tblApplicantNotQualifieds . '.applicant_id', 'applicant_accounts.id')
            ->whereNull($this->tblApplicantNotQualifieds . '.applicant_id')
            ->groupBy('applicant_accounts.id')
            ->join($this->tblApplicantDocuments, $this->tblApplicantDocuments . '.applicant_id', '=', 'applicant_accounts.id');
    }
    /*  function registered_applicants()
    {
        return $this->applicant_account_v2()
            ->where($this->tblApplicantDocuments . '.is_approved', '!=', 2)
            ->where($this->tblApplicantDocuments . '.is_removed', false)
            ->groupBy('applicant_accounts.id');
    } */
    function registered_applicants()
    {
        return $this->applicant_account_v2()
            ->select(
                'applicant_accounts.*',
                //DB::raw('(SELECT COUNT(*) FROM ' . $this->tblApplicantDocuments . ' INNER JOIN ' . $this->tblDocuments . ' ON ' . $this->tblDocuments . '.id = ' . $this->tblApplicantDocuments . '.document_id WHERE ' . $this->tblApplicantDocuments . '.applicant_id = applicant_accounts.id AND ' . $this->tblApplicantDocuments . '.is_removed = 0 AND ' . $this->tblApplicantDocuments . '.is_approved = 1 AND ' . $this->tblDocuments . '.is_removed = false) AS ApprovedDocuments'),
                DB::raw('(SELECT COUNT(*) FROM ' . $this->tblApplicantDocuments . ' WHERE ' . $this->tblApplicantDocuments . '.applicant_id = applicant_accounts.id AND ' . $this->tblApplicantDocuments . '.is_removed = 0 AND ' . $this->tblApplicantDocuments . '.is_approved = 2) AS DisapprovedDocuments'),
                DB::raw('(SELECT COUNT(*) FROM ' . $this->tblApplicantDocuments . ' WHERE ' . $this->tblApplicantDocuments . '.applicant_id = applicant_accounts.id AND ' . $this->tblApplicantDocuments . '.is_removed = 0 AND (' . $this->tblApplicantDocuments . '.is_approved IS NULL OR ' . $this->tblApplicantDocuments . '.is_approved = 1)) AS applicantDocuments'),
                DB::raw('(SELECT COUNT(*) FROM ' . $this->tblDocuments . ' WHERE ' . $this->tblDocuments . '.department_id = 2 AND ' . $this->tblDocuments . '.is_removed = false AND ' . $this->tblDocuments . '.year_level = (SELECT IF(' . $this->applicantAccountTable . '.course_id = 3, 11, 4) FROM ' . $this->applicantAccountTable . ' WHERE ' . $this->applicantAccountTable . '.id = ' . $this->tblApplicantDocuments . '.applicant_id)) AS documentCount')
            )
            ->withCount('documentApprovedV2')
            ->havingRaw('applicantDocuments >= documentCount AND documentCount > document_approved_v2_count AND DisapprovedDocuments <= 0');
    }
    function approved()
    {
        return $this->applicant_account_v2()
            ->select(
                'applicant_accounts.*',
                DB::raw('(SELECT COUNT(*) FROM ' . $this->tblApplicantDocuments . ' WHERE ' . $this->tblApplicantDocuments . '.applicant_id = applicant_accounts.id AND ' . $this->tblApplicantDocuments . '.is_removed = 0 AND ' . $this->tblApplicantDocuments . '.is_approved = 1) AS ApprovedDocuments'),
                DB::raw('(SELECT COUNT(*) FROM ' . $this->tblDocuments . ' WHERE ' . $this->tblDocuments . '.department_id = 2 AND ' . $this->tblDocuments . '.is_removed = false AND ' . $this->tblDocuments . '.year_level = (SELECT IF(' . $this->applicantAccountTable . '.course_id = 3, 11, 4) FROM ' . $this->applicantAccountTable . ' WHERE ' . $this->applicantAccountTable . '.id = ' . $this->tblApplicantDocuments . '.applicant_id)) as documentCount')
            )
            ->havingRaw('COUNT(' . $this->tblApplicantDocuments . '.applicant_id) >= documentCount and ApprovedDocuments >= documentCount')
            ->leftJoin($this->tblApplicantAlumia, $this->tblApplicantAlumia . '.applicant_id', 'applicant_accounts.id')
            ->whereNull($this->tblApplicantAlumia . '.applicant_id');
    }
    function disapproved()
    {
        return $this->applicant_account()
            ->join($this->tblApplicantNotQualifieds, $this->tblApplicantNotQualifieds . '.applicant_id', 'applicant_accounts.id')
            ->where($this->tblApplicantNotQualifieds . '.is_removed', false)
            ->where($this->tblApplicantNotQualifieds . '.academic_id', /* Auth::user()->staff->current_academic()->id */ 10)
            ->groupBy('applicant_accounts.id');
    }
    function pending()
    {
        return $this->applicant_account()
            ->leftJoin($this->tblApplicantNotQualifieds, $this->tblApplicantNotQualifieds . '.applicant_id', 'applicant_accounts.id')
            ->whereNull($this->tblApplicantNotQualifieds . '.applicant_id')
            ->join($this->tblApplicantDocuments, 'applicant_documents.applicant_id', '=', 'applicant_accounts.id')
            ->where($this->tblApplicantDocuments . '.is_approved', 2)
            ->where($this->tblApplicantDocuments . '.is_removed', false)->groupBy('applicant_accounts.id');
    }
    function senior_high_school_alumni()
    {
        return $this->applicant_account()->join($this->tblApplicantAlumia, $this->tblApplicantAlumia . '.applicant_id', 'applicant_accounts.id')
            ->where($this->tblApplicantAlumia . '.is_removed', false);
    }
    function waiting_examination_payment()
    {
        return $this->approved()
            ->leftJoin($this->tblApplicantPayment, $this->tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
            ->whereNull($this->tblApplicantPayment . '.applicant_id');
    }
    function examination_payment()
    {
        return $this->applicant_account()
            ->join($this->tblApplicantPayment, $this->tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
            ->where(function ($query) {
                $query->whereNull(env('DB_DATABASE_SECOND') . '.applicant_payments.is_approved')
                    ->orWhere(env('DB_DATABASE_SECOND') . '.applicant_payments.is_approved', false);
            })
            ->where($this->tblApplicantPayment . '.is_removed', false);
    }
    function entrance_examination_query()
    {
        return $this->applicant_account()
            ->join($this->tblApplicantExamination, $this->tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
            ->where($this->tblApplicantExamination . '.is_removed', false)
            ->where($this->tblApplicantExamination . '.is_finish', true);
    }
    function entrance_examination()
    {
        return $this->applicant_account()
            ->join($this->tblApplicantExamination, $this->tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
            ->whereNull($this->tblApplicantExamination . '.is_finish')
            ->groupBy($this->tblApplicantExamination . '.applicant_id');
    }
    function passed()
    {
        return $this->entrance_examination_query()
            ->join($this->tblApplicantExaminationResult, $this->tblApplicantExaminationResult . '.examination_id', $this->tblApplicantExamination . '.id')
            ->where($this->tblApplicantExaminationResult . '.result', true)
            ->where($this->tblApplicantExaminationResult . '.is_removed', false)
            ->groupBy('applicant_accounts.id')
            ->orderBy($this->tblApplicantExamination . '.updated_at', 'desc');
    }
    function failed()
    {
        return $this->entrance_examination_query()
            ->join($this->tblApplicantExaminationResult, $this->tblApplicantExaminationResult . '.examination_id', $this->tblApplicantExamination . '.id')
            ->where($this->tblApplicantExaminationResult . '.result', false)
            ->where($this->tblApplicantExaminationResult . '.is_removed', false)
            ->groupBy('applicant_accounts.id')->orderBy($this->tblApplicantExamination . '.updated_at', 'desc');
    }
    function for_medical_schedule()
    {
        $applicant_passed = $this->passed();
        return $applicant_passed->leftJoin($this->tblApplicantMedicalScheduled, $this->tblApplicantMedicalScheduled . '.applicant_id', 'applicant_accounts.id')
            ->whereNull($this->tblApplicantMedicalScheduled . '.applicant_id');
    }
    function waiting_for_medical_results()
    {
        return $this->applicant_account()
            ->join($this->tblApplicantMedicalScheduled, $this->tblApplicantMedicalScheduled . '.applicant_id', $this->tblApplicantAccount . '.id')
            ->where($this->tblApplicantMedicalScheduled . '.is_removed', false)
            ->leftJoin($this->tblApplicantMedicalResult, $this->tblApplicantMedicalResult . '.applicant_id', 'applicant_accounts.id')
            ->whereNull($this->tblApplicantMedicalResult . '.applicant_id')
            ->groupBy('applicant_accounts.id');
    }
    //'fit', 'unfit', 'pending_result'
    function fit()
    {
        return $this->applicant_account()
            ->join($this->tblApplicantMedicalResult, $this->tblApplicantMedicalResult . '.applicant_id', 'applicant_accounts.id')
            ->where($this->tblApplicantMedicalResult . '.is_removed', false)
            ->where($this->tblApplicantMedicalResult . '.is_fit', 1)
            ->orderBy($this->tblApplicantMedicalResult . '.created_at', 'desc');
    }
    function unfit()
    {
        return $this->applicant_account()
            ->join($this->tblApplicantMedicalResult, $this->tblApplicantMedicalResult . '.applicant_id', 'applicant_accounts.id')
            ->where($this->tblApplicantMedicalResult . '.is_removed', false)
            ->where($this->tblApplicantMedicalResult . '.is_fit', 2)
            ->orderBy($this->tblApplicantMedicalResult . '.created_at', 'desc');
    }
    function pending_result()
    {
        return $this->applicant_account()
            ->join($this->tblApplicantMedicalResult, $this->tblApplicantMedicalResult . '.applicant_id', 'applicant_accounts.id')
            ->where($this->tblApplicantMedicalResult . '.is_removed', false)
            ->where($this->tblApplicantMedicalResult . '.is_pending', 0)
            ->orderBy($this->tblApplicantMedicalResult . '.created_at', 'desc');
    }
    //array('Enrollment', array('qualified_for_enrollment', 'non_pbm', 'pbm'))
    function qualified_for_enrollment()
    {
        return $this->applicant_account()
            ->join($this->tblApplicantMedicalResult, $this->tblApplicantMedicalResult . '.applicant_id', 'applicant_accounts.id')
            ->where($this->tblApplicantMedicalResult . '.is_removed', false)
            ->where($this->tblApplicantMedicalResult . '.is_fit', 1)
            ->orderBy($this->tblApplicantMedicalResult . '.created_at', 'desc');
    }
    function non_pbm()
    {
        return $this->applicant_account()
            ->join($this->tblApplicantMedicalResult, $this->tblApplicantMedicalResult . '.applicant_id', 'applicant_accounts.id')
            ->where($this->tblApplicantMedicalResult . '.is_removed', false)
            ->where('applicant_accounts.strand', '!=', 'Pre-Baccalaureate Maritime Strand')
            ->where($this->tblApplicantMedicalResult . '.is_fit', 1)
            ->orderBy($this->tblApplicantMedicalResult . '.created_at', 'desc');
    }
    function pbm()
    {
        return $this->applicant_account()
            ->join($this->tblApplicantMedicalResult, $this->tblApplicantMedicalResult . '.applicant_id', 'applicant_accounts.id')
            ->where('applicant_accounts.strand', 'Pre-Baccalaureate Maritime Strand')
            ->where($this->tblApplicantMedicalResult . '.is_removed', false)
            ->where($this->tblApplicantMedicalResult . '.is_fit', 1)
            ->orderBy($this->tblApplicantMedicalResult . '.created_at', 'desc');
    }
}
