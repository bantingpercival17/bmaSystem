<?php

namespace App\Http\Livewire\SummaryComponents;

use App\Models\AcademicYear;
use App\Models\ApplicantAccount;
use App\Models\CourseOfferV2;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ApplicantSummaryOverview extends Component
{
    public $academic = null;
    public function render()
    {
        $courses = CourseOfferV2::select('id', 'course_name', 'course_code')->get();
        $this->academic = $this->academicValue();
        $tableHeader = array(
            array('Information Verification', array('registered_applicants', 'approved', 'disapproved', 'pending', 'senior_high_school_alumni'), 'applicants.summary-reports'),
            array('Entrance Examination', array('waiting_examination_payment', 'examination_payment', 'entrance_examination', 'passed', 'failed'), 'applicants.summary-reports'),
            array('Medical Examination', array('for_medical_schedule', 'waiting_for_medical_results', 'fit', 'unfit', 'pending_result'), 'applicants.summary-reports'),
            array('Enrollment', array('qualified_for_enrollment', 'non_pbm', 'pbm'), 'applicants.summary-reports')

        );
        return view('livewire.summary-components.applicant-summary-overview', compact('courses', 'tableHeader'));
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
    function category_count($category, $course, $academic)
    {
        $applicantAccountTable = env('DB_DATABASE') . '.applicant_accounts';
        $tblDocuments = env('DB_DATABASE') . '.documents';
        $tblApplicantDetails = env('DB_DATABASE_SECOND') . '.applicant_detials';
        $tblApplicantDocuments = env('DB_DATABASE_SECOND') . '.applicant_documents';
        $tblApplicantNotQualifieds =  env('DB_DATABASE_SECOND') . '.applicant_not_qualifieds';
        $tblApplicantPayment = env('DB_DATABASE_SECOND') . '.applicant_payments';
        $tblApplicantAlumia = env('DB_DATABASE_SECOND') . '.applicant_alumnias';
        $tblApplicantExamination = env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations';
        //$tblApplicantOrientationScheduled = env('DB_DATABASE_SECOND') . '.applicant_briefing_schedules';
        $tblApplicantOrientation = env('DB_DATABASE_SECOND') . '.applicant_briefings';
        $query = ApplicantAccount::select('applicant_accounts.*')
            ->where('applicant_accounts.is_removed', false)
            ->where('applicant_accounts.academic_id', base64_decode($academic))
            ->where('applicant_accounts.course_id', $course);
        // Sort By Categories
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
                        $query = $query->leftJoin($tblApplicantPayment, $tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                            ->leftJoin($tblApplicantAlumia, $tblApplicantAlumia . '.applicant_id', 'applicant_accounts.id')
                            ->where($tblApplicantAlumia . '.applicant_id')
                            ->whereNull($tblApplicantPayment . '.applicant_id');
                    }
                }
            } else {
                // Not Qualified Applicants
                $query = $query->join($tblApplicantNotQualifieds, $tblApplicantNotQualifieds . '.applicant_id', $applicantAccountTable . '.id')
                    ->where($tblApplicantNotQualifieds . '.is_removed', false)
                    ->where($tblApplicantNotQualifieds . '.academic_id', Auth::user()->staff->current_academic()->id)
                    ->groupBy('applicant_accounts.id');
            }
        }

        return $query->get();
    }
}
