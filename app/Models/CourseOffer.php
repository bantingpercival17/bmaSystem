<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseOffer extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $fillable = ['course_name', 'course_code', 'school_level', 'is_removed'];
    public function course_subject($_data)
    {
        return $this->hasMany(CurriculumSubject::class, 'course_id')
            ->select('curriculum_subjects.id', 'subjects.subject_code', 'subjects.subject_name')
            ->join('subjects', 'subjects.id', 'curriculum_subjects.subject_id')
            ->where('curriculum_subjects.curriculum_id', $_data[0])
            ->where('curriculum_subjects.year_level', $_data[1])
            ->where('curriculum_subjects.semester', $_data[2])
            ->where('curriculum_subjects.is_removed', false)
            ->get();
    }
    public function section($_data)
    {
        return $this->hasMany(Section::class, 'course_id')
            ->where('sections.academic_id', $_data[0])
            ->where('sections.year_level', 'like', '%' . $_data[1] . '%')
            ->where('is_removed', false)
            /* ->get() */;
    }
    public function enrollment_list()
    {
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->join('payment_assessments as pa', 'pa.enrollment_id', 'enrollment_assessments.id')
            ->leftJoin('payment_transactions as pt', 'pt.assessment_id', 'pa.id')
            ->where('pt.remarks', 'Upon Enrollment')
            ->where('pt.is_removed', false)
            ->where('enrollment_assessments.is_removed', false)
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->groupBy('pt.assessment_id')
            ->orderBy('pt.created_at', 'DESC');
    }
    public function enrolled_list($_data)
    {
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->select('enrollment_assessments.*')
            ->join('payment_assessments as pa', 'pa.enrollment_id', 'enrollment_assessments.id')
            ->join('payment_transactions as pt', 'pt.assessment_id', 'pa.id')
            ->where('pt.remarks', 'Upon Enrollment')
            ->where('pt.is_removed', false)
            ->groupBy('pt.assessment_id')
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->orderBy('enrollment_assessments.created_at', 'DESC')
            ->where('enrollment_assessments.year_level', $_data)
            ->where('enrollment_assessments.is_removed', false);
    }
    public function sort_enrolled_list($_request)
    {
        $_query = $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->join('student_details as sd', 'sd.id', 'enrollment_assessments.student_id')
            ->join('student_accounts as sa', 'sa.student_id', 'sd.id')
            ->join('payment_assessments as pa', 'pa.enrollment_id', 'enrollment_assessments.id')
            ->join('payment_transactions as pt', 'pt.assessment_id', 'pa.id')
            ->where('pt.remarks', 'Upon Enrollment')
            ->groupBy('pt.assessment_id')
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('pt.is_removed', false)
            ->where('enrollment_assessments.is_removed', false);
        //Get Year Level
        $_query = $_request->_year_level ? $_query->where('enrollment_assessments.year_level', $_request->_year_leve) : $_query;
        //Sorting index & value
        $_query = $_request->_sort == 'enrollment-date' ?  $_query->orderBy('enrollment_assessments.created_at', 'DESC') : $_query;
        $_query = $_request->_sort == 'lastname' ?  $_query->orderBy('sd.last_name', 'ASC')->orderBy('sd.first_name', 'ASC') : $_query;
        $_query = $_request->_sort == 'student-number' ?  $_query->orderBy('sa.student_number', 'ASC') : $_query;
        if ($_request->_students) {
            $_student = explode(',', $_request->_students);
            $_count = count($_student);
            if ($_count > 1) {
                $_query = $_query->where('sd.last_name', 'like', "%" . trim($_student[0]) . "%")
                    ->where('sd.first_name', 'like', "%" . trim($_student[1]) . "%");
            } else {
                $_query = $_query->where('sd.last_name', 'like', "%" . trim($_student[0]) . "%");
            }
        }
        return $_query;
    }
    public function student_list()
    {
        $_level =  (string)request()->input('_year_level') . "/C";
        return $this->hasMany(Section::class, 'course_id')
            ->select('sd.first_name', 'sd.last_name', 'ss.student_id', 'ss.section_id')
            ->join('student_sections as ss', 'ss.section_id', 'sections.id')
            ->join('student_details as sd', 'ss.student_id', 'sd.id')
            ->where('sections.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('sections.year_level', $_level)
            ->orderBy('sd.last_name', 'asc')->orderBy('sd.first_name');
    }
    public function grading_student_list($_curriculum)
    {
        $_level =  (string)request()->input('_year_level') . "/C";
        return $this->hasMany(Section::class, 'course_id')
            ->select('sd.first_name', 'sd.last_name', 'ss.student_id', 'ss.section_id', 'sd.middle_name')
            ->join('student_sections as ss', 'ss.section_id', 'sections.id')
            ->join('student_details as sd', 'ss.student_id', 'sd.id')
            ->join('enrollment_assessments as ea', 'sd.id', 'ea.student_id')
            ->where('sections.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('sections.year_level', $_level)
            ->where('ea.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('ea.curriculum_id', $_curriculum->id)
            ->orderBy('sd.last_name', 'asc')->orderBy('sd.first_name');
    }
    public function previous_enrolled()
    {
        $_academic = AcademicYear::where('id', '<', Auth::user()->staff->current_academic()->id)->orderBy('id', 'desc')->first();
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->join('payment_assessments as pa', 'pa.enrollment_id', 'enrollment_assessments.id')
            /*  ->leftJoin('payment_transactions as pt', 'pt.assessment_id', 'pa.id')
            ->where('pt.remarks', 'Upon Enrollment') */
            ->where('enrollment_assessments.is_removed', false)
            ->where('enrollment_assessments.academic_id', $_academic->id)
            #->groupBy('pt.assessment_id')
            ->orderBy('pa.created_at', 'DESC');
    }
    public function students_clearance()
    {
        $_previous_academic = AcademicYear::where('id', '<', Auth::user()->staff->current_academic()->id)->orderBy('id', 'desc')->first();
        return $this->hasMany(OfficalCleared::class, 'course_id')
            ->select('offical_cleareds.student_id')
            ->leftJoin('enrollment_applications as ep', 'ep.student_id', 'offical_cleareds.student_id')
            ->where('offical_cleareds.is_cleared', true)
            ->where('offical_cleareds.is_removed', false)
            ->where('offical_cleareds.academic_id', $_previous_academic->id)
            ->whereNull('ep.student_id');
    }
    public function students_not_clearance()
    {
        $_previous_academic = AcademicYear::where('id', '<', Auth::user()->staff->current_academic()->id)->orderBy('id', 'desc')->first();
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->select('enrollment_assessments.student_id', 'enrollment_assessments.year_level')
            ->leftJoin('offical_cleareds as oc', function ($join) {
                $join->on('oc.student_id', 'enrollment_assessments.student_id');
                $join->on('oc.academic_id', 'enrollment_assessments.academic_id');
            })
            ->where('enrollment_assessments.academic_id', $_previous_academic->id)
            ->whereNull('oc.student_id');
    }
    public function students_not_clearance_year_level($_level)
    {
        $_previous_academic = AcademicYear::where('id', '<', Auth::user()->staff->current_academic()->id)->orderBy('id', 'desc')->first();
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->select('enrollment_assessments.student_id', 'enrollment_assessments.year_level')
            ->leftJoin('offical_cleareds as oc', function ($join) {
                $join->on('oc.student_id', 'enrollment_assessments.student_id');
                $join->on('oc.academic_id', 'enrollment_assessments.academic_id');
            })
            ->where('enrollment_assessments.year_level', $_level)
            ->where('enrollment_assessments.academic_id', $_previous_academic->id)
            ->whereNull('oc.student_id');
    }
    public function enrollment_application()
    {
        $_academic = AcademicYear::where('id', '<', Auth::user()->staff->current_academic()->id)->orderBy('id', 'desc')->first();

        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->join('enrollment_applications as ea', 'ea.student_id', 'enrollment_assessments.student_id')
            ->whereNull('ea.is_approved')
            ->where('enrollment_assessments.is_removed', false)
            ->where('enrollment_assessments.academic_id', $_academic->id);
    }

    public function payment_assessment_year_level($_year_level)
    {
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->leftJoin('payment_assessments as pa', 'pa.enrollment_id', 'enrollment_assessments.id')
            ->leftJoin('payment_transactions as pt', 'pa.id', 'pt.assessment_id')
            ->whereNull('pa.enrollment_id')
            ->where('enrollment_assessments.year_level', $_year_level);
    }
    # BRIDGING PROGRAM
    public function student_bridging_program()
    {
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('enrollment_assessments.bridging_program', 'with')
            ->where('enrollment_assessments.is_removed', false);
    }
    public function payment_assessment()
    {
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->leftJoin('payment_assessments as pa', 'pa.enrollment_id', 'enrollment_assessments.id')
            ->leftJoin('payment_transactions as pt', 'pa.id', 'pt.assessment_id')
            ->whereNull('pa.enrollment_id');
    }
    public function payment_transaction()
    {
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->join('payment_assessments as pa', 'pa.enrollment_id', 'enrollment_assessments.id')
            ->leftJoin('payment_transactions as pt', 'pa.id', 'pt.assessment_id')
            ->whereNull('pt.assessment_id');
    }
    public function payment_transaction_year_level($_year_level)
    {
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->join('payment_assessments as pa', 'pa.enrollment_id', 'enrollment_assessments.id')
            ->leftJoin('payment_transactions as pt', 'pa.id', 'pt.assessment_id')
            ->whereNull('pt.assessment_id')
            ->where('enrollment_assessments.year_level', $_year_level);
    }
    public function sections()
    {
        $_academic = Auth::user()->staff->current_academic();
        return $this->hasMany(Section::class, 'course_id')->where('academic_id', $_academic->id)->where('is_removed', false)->orderBy('section_name', 'Desc');
    }
    public function units($_data)
    {
        return $this->hasMany(CurriculumSubject::class, 'course_id')
            ->selectRaw("sum(s.units) as units")
            ->join('subjects as s', 's.id', 'curriculum_subjects.subject_id')
            ->where('curriculum_subjects.year_level', $_data->year_level)
            ->where('curriculum_subjects.curriculum_id', $_data->curriculum_id)
            ->where('curriculum_subjects.semester', $_data->academic->semester)
            ->where('curriculum_subjects.is_removed', false)
            ->first();
    }

    /* Applicant Model */

    #Pre-Registration Applicant without a files
    public function applicant_pre_registrations()
    {
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*')
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id') # Applicant Account
            ->join('applicant_detials', 'applicant_detials.applicant_id', 'applicant_accounts.id') # Join Applicant Details
            ->leftJoin('applicant_documents', 'applicant_documents.applicant_id', 'applicant_accounts.id')
            ->whereNull('applicant_documents.applicant_id');
        //Searching Tool
        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_count > 0 ? $_query->where('applicant_detials.last_name', 'like', "%" . trim($_student[0]) . "%") :
                $_query->where('applicant_detials.last_name', 'like', "%" . trim($_student[0]) . "%")
                ->where('applicant_detials.first_name', 'like', "%" . trim($_student[1]) . "%");
        }
        return $_query;
    }
    # Applicant For Verification of Documents
    public function applicant_for_checking()
    {
        $_level = $this->id == 3 ? 11 : 4;
        $_documents = Documents::where('department_id', 2)->where('year_level', $_level)->where('is_removed', false)->count();
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*')
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id') # Applicant Account
            ->join('applicant_detials', 'applicant_detials.applicant_id', 'applicant_accounts.id') # Join Applicant Details
            ->join('applicant_documents as sd', 'sd.applicant_id', 'applicant_accounts.id')
            #->having(DB::raw('COUNT(CASE WHEN is_approved = 1 THEN 1 END)'), '<', $_documents) # Applicant Documents
            ->where(function ($_subQuery) {
                $_subQuery->select(DB::raw('count("is_approved")'))->from('applicant_documents')
                    ->whereColumn('applicant_documents.applicant_id', 'applicant_accounts.id')
                    ->where('applicant_documents.is_removed', false)
                    ->where('applicant_documents.is_approved', true);
            }, '<', $_documents) # Applicant Documents
        ;
        //Searching Tool
        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_count > 0 ? $_query->where('applicant_detials.last_name', 'like', "%" . trim($_student[0]) . "%") :
                $_query->where('applicant_detials.last_name', 'like', "%" . trim($_student[0]) . "%")
                ->where('applicant_detials.first_name', 'like', "%" . trim($_student[1]) . "%");
        }
        return $_query;
    }
    # Applicant Documents Approved
    public function applicant_verified_documents()
    {
        $_level = $this->id == 3 ? 11 : 4;
        $_documents = Documents::where('department_id', 2)->where('year_level', $_level)->where('is_removed', false)->count();
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*')
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id') # Applicant Account
            ->join('applicant_detials', 'applicant_detials.applicant_id', 'applicant_accounts.id') # Join Applicant Details
            ->where(function ($_subQuery) {
                $_subQuery->select(DB::raw('count("is_approved")'))->from('applicant_documents')
                    ->whereColumn('applicant_documents.applicant_id', 'applicant_accounts.id')
                    ->where('applicant_documents.is_removed', false)
                    ->where('applicant_documents.is_approved', true);
            }, '>=', $_documents) # Applicant Documents
            ->leftJoin('applicant_payments', 'applicant_payments.applicant_id', 'applicant_accounts.id')
            ->whereNull('applicant_payments.applicant_id') # Applicant Payments
        ;
        //Searching Tool
        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_count > 0 ? $_query->where('applicant_detials.last_name', 'like', "%" . trim($_student[0]) . "%") :
                $_query->where('applicant_detials.last_name', 'like', "%" . trim($_student[0]) . "%")
                ->where('applicant_detials.first_name', 'like', "%" . trim($_student[1]) . "%");
        }
        return $_query;
    }
    # Applicant Payment Verification for Entrance Examination
    public function applicant_payment_verification_v2()
    {
        $_level = $this->id == 3 ? 11 : 4;
        $_documents = Documents::where('department_id', 2)->where('year_level', $_level)->where('is_removed', false)->count();
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*')
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id') # Applicant Account
            ->join('applicant_detials', 'applicant_detials.applicant_id', 'applicant_accounts.id') # Join Applicant Details
            ->where(function ($_subQuery) {
                $_subQuery->select(DB::raw('count("is_approved")'))->from('applicant_documents')
                    ->whereColumn('applicant_documents.applicant_id', 'applicant_accounts.id')
                    ->where('applicant_documents.is_removed', false)
                    ->where('applicant_documents.is_approved', true);
            }, '>=', $_documents) # Applicant Documents
            ->leftJoin('applicant_payments', 'applicant_payments.applicant_id', 'applicant_accounts.id')
            ->where('applicant_payments.is_removed', false)
            ->where('applicant_payments.is_approved', false)
            #->where('applicant_payments.is_approved', null)
            #->whereBetween('applicant_payments.is_approved',[false,null])
            #->whereNull('applicant_payments.is_approved') # Applicant Payments
        ;
        //Searching Tool
        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_count > 0 ? $_query->where('applicant_detials.last_name', 'like', "%" . trim($_student[0]) . "%") :
                $_query->where('applicant_detials.last_name', 'like', "%" . trim($_student[0]) . "%")
                ->where('applicant_detials.first_name', 'like', "%" . trim($_student[1]) . "%");
        }
        return $_query;
    }
    # Applicant Payment Verified 
    public function applicant_payment_verified()
    {
        $_level = $this->id == 3 ? 11 : 4;
        $_documents = Documents::where('department_id', 2)->where('year_level', $_level)->where('is_removed', false)->count();
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*') # Applicant Account
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id')
            ->join('applicant_detials', 'applicant_detials.applicant_id', 'applicant_accounts.id') # Join Applicant Details
            ->where(function ($_subQuery) { # Applicant Documents
                $_subQuery->select(DB::raw('count("is_approved")'))->from('applicant_documents')
                    ->whereColumn('applicant_documents.applicant_id', 'applicant_accounts.id')
                    ->where('applicant_documents.is_removed', false)
                    ->where('applicant_documents.is_approved', true);
            }, '>=', $_documents)
            ->leftJoin('applicant_payments', 'applicant_payments.applicant_id', 'applicant_accounts.id') # Applicant Payments
            ->where('applicant_payments.is_removed', false)
            ->where('applicant_payments.is_approved', true)
            ->join('applicant_entrance_examinations', 'applicant_entrance_examinations.applicant_id', 'applicant_accounts.id')
            ->where('applicant_entrance_examinations.is_removed', false)
            ->whereNull('applicant_entrance_examinations.is_finish');
        //Searching Tool
        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_count > 0 ? $_query->where('applicant_detials.last_name', 'like', "%" . trim($_student[0]) . "%") :
                $_query->where('applicant_detials.last_name', 'like', "%" . trim($_student[0]) . "%")
                ->where('applicant_detials.first_name', 'like', "%" . trim($_student[1]) . "%");
        }
        return $_query;
    }
    # Applicant Examination On-going
    public function applicant_examination_ongoing()
    {
        $_level = $this->id == 3 ? 11 : 4;
        $_documents = Documents::where('department_id', 2)->where('year_level', $_level)->where('is_removed', false)->count();
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*') # Applicant Account
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id')
            ->join('applicant_detials', 'applicant_detials.applicant_id', 'applicant_accounts.id') # Join Applicant Details
            ->where(function ($_subQuery) { # Applicant Documents
                $_subQuery->select(DB::raw('count("is_approved")'))->from('applicant_documents')
                    ->whereColumn('applicant_documents.applicant_id', 'applicant_accounts.id')
                    ->where('applicant_documents.is_removed', false)
                    ->where('applicant_documents.is_approved', true);
            }, '>=', $_documents)
            ->leftJoin('applicant_payments', 'applicant_payments.applicant_id', 'applicant_accounts.id') # Applicant Payments
            ->where('applicant_payments.is_removed', false)
            ->where('applicant_payments.is_approved', true)
            ->join('applicant_entrance_examinations', 'applicant_entrance_examinations.applicant_id', 'applicant_accounts.id')
            ->where('applicant_entrance_examinations.is_removed', false)
            ->where('applicant_entrance_examinations.is_finish', false);
        //Searching Tool
        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_count > 0 ? $_query->where('applicant_detials.last_name', 'like', "%" . trim($_student[0]) . "%") :
                $_query->where('applicant_detials.last_name', 'like', "%" . trim($_student[0]) . "%")
                ->where('applicant_detials.first_name', 'like', "%" . trim($_student[1]) . "%");
        }
        return $_query;
    }
    # Applicant Examination Passed
    public function applicant_examination_passed()
    {
        $_level = $this->id == 3 ? 11 : 4;
        $_item =  $this->id == 3 ? 100 : 200;
        $_point = $this->id == 3 ? 5 : 50;
        $_documents = Documents::where('department_id', 2)->where('year_level', $_level)->where('is_removed', false)->count();
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*') # Applicant Account
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id')
            ->join('applicant_detials', 'applicant_detials.applicant_id', 'applicant_accounts.id') # Join Applicant Details
            ->where(function ($_subQuery) { # Applicant Documents
                $_subQuery->select(DB::raw('count("is_approved")'))->from('applicant_documents')
                    ->whereColumn('applicant_documents.applicant_id', 'applicant_accounts.id')
                    ->where('applicant_documents.is_removed', false)
                    ->where('applicant_documents.is_approved', true);
            }, '>=', $_documents)
            ->leftJoin('applicant_payments', 'applicant_payments.applicant_id', 'applicant_accounts.id') # Applicant Payments
            ->where('applicant_payments.is_removed', false)
            ->where('applicant_payments.is_approved', true)
            ->join('applicant_entrance_examinations', 'applicant_entrance_examinations.applicant_id', 'applicant_accounts.id')
            ->where('applicant_entrance_examinations.is_removed', false)
            ->where('applicant_entrance_examinations.is_finish', true)
            ->where(
                DB::raw("(SELECT ((COUNT(eqc.is_answer)/" . $_item . ")*100) as exam_result 
                FROM bma_website.applicant_examination_answers as aea
                inner join bma_portal.examination_question_choices as eqc
                on eqc.id = aea.choices_id
                where eqc.is_answer = true and aea.examination_id = applicant_entrance_examinations.id)"),
                '>=',
                $_point
            )->orderBy('applicant_entrance_examinations.updated_at', 'desc');
        //Searching Tool
        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_count > 0 ? $_query->where('applicant_detials.last_name', 'like', "%" . trim($_student[0]) . "%") :
                $_query->where('applicant_detials.last_name', 'like', "%" . trim($_student[0]) . "%")
                ->where('applicant_detials.first_name', 'like', "%" . trim($_student[1]) . "%");
        }
        return $_query;
    }
    # Applicant Examination Failed
    public function applicant_examination_failed()
    {
        $_level = $this->id == 3 ? 11 : 4;
        $_item =  $this->id == 3 ? 100 : 200;
        $_point = $this->id == 3 ? 5 : 50;
        $_documents = Documents::where('department_id', 2)->where('year_level', $_level)->where('is_removed', false)->count();
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*') # Applicant Account
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id')
            ->join('applicant_detials', 'applicant_detials.applicant_id', 'applicant_accounts.id') # Join Applicant Details
            ->where(function ($_subQuery) { # Applicant Documents
                $_subQuery->select(DB::raw('count("is_approved")'))->from('applicant_documents')
                    ->whereColumn('applicant_documents.applicant_id', 'applicant_accounts.id')
                    ->where('applicant_documents.is_removed', false)
                    ->where('applicant_documents.is_approved', true);
            }, '>=', $_documents)
            ->leftJoin('applicant_payments', 'applicant_payments.applicant_id', 'applicant_accounts.id') # Applicant Payments
            ->where('applicant_payments.is_removed', false)
            ->where('applicant_payments.is_approved', true)
            ->join('applicant_entrance_examinations', 'applicant_entrance_examinations.applicant_id', 'applicant_accounts.id')
            ->where('applicant_entrance_examinations.is_removed', false)
            ->where('applicant_entrance_examinations.is_finish', true)
            ->where(
                DB::raw("(SELECT ((COUNT(eqc.is_answer)/" . $_item . ")*100) as exam_result 
                FROM bma_website.applicant_examination_answers as aea
                inner join bma_portal.examination_question_choices as eqc
                on eqc.id = aea.choices_id
                where eqc.is_answer = true and aea.examination_id = applicant_entrance_examinations.id)"),
                '<',
                $_point
            )->orderBy('applicant_entrance_examinations.updated_at', 'desc');
        //Searching Tool
        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_count > 0 ? $_query->where('applicant_detials.last_name', 'like', "%" . trim($_student[0]) . "%") :
                $_query->where('applicant_detials.last_name', 'like', "%" . trim($_student[0]) . "%")
                ->where('applicant_detials.first_name', 'like', "%" . trim($_student[1]) . "%");
        }
        return $_query;
    }
    /* Verified Applicants */
    public function verified_applicants()
    {
        $_level = $this->id == 3 ? 11 : 4;
        $_documents = Documents::where('department_id', 2)->where('year_level', $_level)->where('is_removed', false)->count();
        return $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*')
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->where(function ($_subQuery) {
                $_subQuery->select(DB::raw('count("is_approved")'))->from('applicant_documents')
                    ->whereColumn('applicant_documents.applicant_id', 'applicant_accounts.id')
                    ->where('applicant_documents.is_removed', false)
                    ->where('applicant_documents.is_approved', true);
            }, '>=', $_documents)
            ->groupBy('applicant_accounts.id');
    }
    /* Entrance Examination Payment */
    public function applicant_payment_verification()
    {
        $_level = $this->id == 3 ? 11 : 4;
        $_documents = Documents::where('department_id', 2)->where('year_level', $_level)->where('is_removed', false)->count();
        $_documents = intval($_documents);
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*')
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id')
            ->where(function ($_subQuery) {
                $_subQuery->select(DB::raw('count("is_approved")'))->from('applicant_documents')
                    ->whereColumn('applicant_documents.applicant_id', 'applicant_accounts.id')
                    ->where('applicant_documents.is_removed', false)
                    ->where('applicant_documents.is_approved', true);
            }, '>=', $_documents)
            ->join('applicant_payments', 'applicant_payments.applicant_id', 'applicant_accounts.id')
            ->where('applicant_payments.is_removed', false)
            ->whereNull('applicant_payments.is_approved');
        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_query->join('applicant_detials as ad', 'ad.applicant_id', 'applicant_accounts.id');
            $_query = $_count > 0 ? $_query->where('ad.last_name', 'like', "%" . trim($_student[0]) . "%") :
                $_query->where('ad.last_name', 'like', "%" . trim($_student[0]) . "%")
                ->where('ad.first_name', 'like', "%" . trim($_student[1]) . "%");
            //return request()->input('_student');
        }
        return $_query;
    }
    # Virtual Orientation
    public function applicant_virtual_orientation()
    {
        $_level = $this->id == 3 ? 11 : 4;
        $_item =  $this->id == 3 ? 100 : 200;
        $_point = $this->id == 3 ? 5 : 50;
        $_documents = Documents::where('department_id', 2)->where('year_level', $_level)->where('is_removed', false)->count();
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*') # Applicant Account
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id')
            ->join('applicant_detials', 'applicant_detials.applicant_id', 'applicant_accounts.id') # Join Applicant Details
            ->where(function ($_subQuery) { # Applicant Documents
                $_subQuery->select(DB::raw('count("is_approved")'))->from('applicant_documents')
                    ->whereColumn('applicant_documents.applicant_id', 'applicant_accounts.id')
                    ->where('applicant_documents.is_removed', false)
                    ->where('applicant_documents.is_approved', true);
            }, '>=', $_documents)
            ->leftJoin('applicant_payments', 'applicant_payments.applicant_id', 'applicant_accounts.id') # Applicant Payments
            ->where('applicant_payments.is_removed', false)
            ->where('applicant_payments.is_approved', true)
            ->join('applicant_entrance_examinations', 'applicant_entrance_examinations.applicant_id', 'applicant_accounts.id') # Entrance Examination
            ->where('applicant_entrance_examinations.is_removed', false)
            ->where('applicant_entrance_examinations.is_finish', true)
            ->where(
                DB::raw("(SELECT ((COUNT(eqc.is_answer)/" . $_item . ")*100) as exam_result 
                FROM bma_website.applicant_examination_answers as aea
                inner join bma_portal.examination_question_choices as eqc
                on eqc.id = aea.choices_id
                where eqc.is_answer = true and aea.examination_id = applicant_entrance_examinations.id)"),
                '>=',
                $_point
            ) # Get the Score;
            ->join('applicant_briefings', 'applicant_briefings.applicant_id', 'applicant_accounts.id')
            ->orderBy('applicant_briefings.updated_at', 'desc');
        //Searching Tool
        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_count > 0 ? $_query->where('applicant_detials.last_name', 'like', "%" . trim($_student[0]) . "%") :
                $_query->where('applicant_detials.last_name', 'like', "%" . trim($_student[0]) . "%")
                ->where('applicant_detials.first_name', 'like', "%" . trim($_student[1]) . "%");
        }
        return $_query;
    }
    # Medical Appointment
    public function applicant_medical_appointment()
    {
        $_level = $this->id == 3 ? 11 : 4;
        $_item =  $this->id == 3 ? 100 : 200;
        $_point = $this->id == 3 ? 5 : 50;
        $_documents = Documents::where('department_id', 2)->where('year_level', $_level)->where('is_removed', false)->count();
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*') # Applicant Account
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id')
            ->join('applicant_detials', 'applicant_detials.applicant_id', 'applicant_accounts.id') # Join Applicant Details
            ->where(function ($_subQuery) { # Applicant Documents
                $_subQuery->select(DB::raw('count("is_approved")'))->from('applicant_documents')
                    ->whereColumn('applicant_documents.applicant_id', 'applicant_accounts.id')
                    ->where('applicant_documents.is_removed', false)
                    ->where('applicant_documents.is_approved', true);
            }, '>=', $_documents)
            ->leftJoin('applicant_payments', 'applicant_payments.applicant_id', 'applicant_accounts.id') # Applicant Payments
            ->where('applicant_payments.is_removed', false)
            ->where('applicant_payments.is_approved', true)
            ->join('applicant_entrance_examinations', 'applicant_entrance_examinations.applicant_id', 'applicant_accounts.id') # Entrance Examination
            ->where('applicant_entrance_examinations.is_removed', false)
            ->where('applicant_entrance_examinations.is_finish', true)
            ->where(
                DB::raw("(SELECT ((COUNT(eqc.is_answer)/" . $_item . ")*100) as exam_result 
                FROM bma_website.applicant_examination_answers as aea
                inner join bma_portal.examination_question_choices as eqc
                on eqc.id = aea.choices_id
                where eqc.is_answer = true and aea.examination_id = applicant_entrance_examinations.id)"),
                '>=',
                $_point
            ) # Get the Score;
            ->join('applicant_briefings', 'applicant_briefings.applicant_id', 'applicant_accounts.id') # Applicant Virtual Orientation
            ->leftJoin('applicant_medical_appointments', 'applicant_medical_appointments.applicant_id', 'applicant_accounts.id')
            ->whereNull('applicant_medical_appointments.applicant_id');
        //Searching Tool
        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_count > 0 ? $_query->where('applicant_detials.last_name', 'like', "%" . trim($_student[0]) . "%") :
                $_query->where('applicant_detials.last_name', 'like', "%" . trim($_student[0]) . "%")
                ->where('applicant_detials.first_name', 'like', "%" . trim($_student[1]) . "%");
        }
        return $_query;
    }
    # Medical Schedule Approved
    public function applicant_medical_scheduled()
    {
        $_level = $this->id == 3 ? 11 : 4;
        $_item =  $this->id == 3 ? 100 : 200;
        $_documents = Documents::where('department_id', 2)->where('year_level', $_level)->where('is_removed', false)->count();
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*') # Applicant Account
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id')
            ->join('applicant_detials', 'applicant_detials.applicant_id', 'applicant_accounts.id') # Join Applicant Details
            ->where(function ($_subQuery) { # Applicant Documents
                $_subQuery->select(DB::raw('count("is_approved")'))->from('applicant_documents')
                    ->whereColumn('applicant_documents.applicant_id', 'applicant_accounts.id')
                    ->where('applicant_documents.is_removed', false)
                    ->where('applicant_documents.is_approved', true);
            }, '>=', $_documents)
            ->leftJoin('applicant_payments', 'applicant_payments.applicant_id', 'applicant_accounts.id') # Applicant Payments
            ->where('applicant_payments.is_removed', false)
            ->where('applicant_payments.is_approved', true)
            ->join('applicant_entrance_examinations', 'applicant_entrance_examinations.applicant_id', 'applicant_accounts.id') # Entrance Examination
            ->where('applicant_entrance_examinations.is_removed', false)
            ->where('applicant_entrance_examinations.is_finish', true)
            ->where(
                DB::raw("(SELECT ((COUNT(eqc.is_answer)/" . $_item . ")*100) as exam_result 
                FROM bma_website.applicant_examination_answers as aea
                inner join bma_portal.examination_question_choices as eqc
                on eqc.id = aea.choices_id
                where eqc.is_answer = true and aea.examination_id = applicant_entrance_examinations.id)"),
                '>=',
                '50'
            ) # Get the Score;
            ->join('applicant_briefings', 'applicant_briefings.applicant_id', 'applicant_accounts.id') # Applicant Virtual Orientation
            ->join('applicant_medical_appointments', 'applicant_medical_appointments.applicant_id', 'applicant_accounts.id') # Applicant Medical Appointment
            ->where('applicant_medical_appointments.is_removed', false)
            ->leftJoin('applicant_medical_results', 'applicant_medical_results.applicant_id', 'applicant_accounts.id')
            ->whereNull('applicant_medical_results.applicant_id');

        //Searching Tool
        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_count > 0 ? $_query->where('applicant_detials.last_name', 'like', "%" . trim($_student[0]) . "%") :
                $_query->where('applicant_detials.last_name', 'like', "%" . trim($_student[0]) . "%")
                ->where('applicant_detials.first_name', 'like', "%" . trim($_student[1]) . "%");
        }
        return $_query;
    }
    # Medical Results
    public function applicant_medical_results()
    {
        $_level = $this->id == 3 ? 11 : 4;
        $_item =  $this->id == 3 ? 100 : 200;
        $_documents = Documents::where('department_id', 2)->where('year_level', $_level)->where('is_removed', false)->count();
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*') # Applicant Account
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id')
            ->join('applicant_detials', 'applicant_detials.applicant_id', 'applicant_accounts.id') # Join Applicant Details
            ->where(function ($_subQuery) { # Applicant Documents
                $_subQuery->select(DB::raw('count("is_approved")'))->from('applicant_documents')
                    ->whereColumn('applicant_documents.applicant_id', 'applicant_accounts.id')
                    ->where('applicant_documents.is_removed', false)
                    ->where('applicant_documents.is_approved', true);
            }, '>=', $_documents)
            ->leftJoin('applicant_payments', 'applicant_payments.applicant_id', 'applicant_accounts.id') # Applicant Payments
            ->where('applicant_payments.is_removed', false)
            ->where('applicant_payments.is_approved', true)
            ->join('applicant_entrance_examinations', 'applicant_entrance_examinations.applicant_id', 'applicant_accounts.id') # Entrance Examination
            ->where('applicant_entrance_examinations.is_removed', false)
            ->where('applicant_entrance_examinations.is_finish', true)
            ->where(
                DB::raw("(SELECT ((COUNT(eqc.is_answer)/" . $_item . ")*100) as exam_result 
                FROM bma_website.applicant_examination_answers as aea
                inner join bma_portal.examination_question_choices as eqc
                on eqc.id = aea.choices_id
                where eqc.is_answer = true and aea.examination_id = applicant_entrance_examinations.id)"),
                '>=',
                '50'
            ) # Get the Score;
            ->join('applicant_briefings', 'applicant_briefings.applicant_id', 'applicant_accounts.id') # Applicant Virtual Orientation
            ->join('applicant_medical_appointments', 'applicant_medical_appointments.applicant_id', 'applicant_accounts.id') # Applicant Medical Appointment
            ->where('applicant_medical_appointments.is_removed', false)
            ->leftJoin('applicant_medical_results', 'applicant_medical_results.applicant_id', 'applicant_accounts.id')
            ->whereNotNull('applicant_medical_results.applicant_id')
            ->where('applicant_medical_results.is_removed', false);
        # ->whereNotNull('amr.applicant_id')
        #->where('amr.is_removed', false)
        //Searching Tool
        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_count > 0 ? $_query->where('applicant_detials.last_name', 'like', "%" . trim($_student[0]) . "%") :
                $_query->where('applicant_detials.last_name', 'like', "%" . trim($_student[0]) . "%")
                ->where('applicant_detials.first_name', 'like', "%" . trim($_student[1]) . "%");
        }
        return $_query;
    }
    public function applicant_qualified_to_enrolled()
    {
        return $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*')
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->groupBy('applicant_accounts.id')
            ->join('applicant_medical_results as amr', 'amr.applicant_id', 'applicant_accounts.id')
            ->where('amr.is_removed', false)->where('amr.is_fit', true);
    }

    // COURSE COLLECTION
    public function student_payment_mode($_data)
    {
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->join('payment_assessments as pa', 'pa.enrollment_id', 'enrollment_assessments.id')
            ->leftJoin('payment_transactions as pt', 'pt.assessment_id', 'pa.id')
            ->where('pt.remarks', 'Upon Enrollment')
            ->where('pt.is_removed', false)
            ->where('enrollment_assessments.is_removed', false)
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->groupBy('pt.assessment_id')
            ->orderBy('pt.created_at', 'DESC')
            ->where('pa.payment_mode', $_data)->get();
    }
    public function student_payment_schedule($_data)
    {
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->join('payment_assessments as pa', 'pa.enrollment_id', 'enrollment_assessments.id')
            ->leftJoin('payment_transactions as pt', 'pt.assessment_id', 'pa.id')
            ->where('pt.remarks', $_data)
            ->where('pt.is_removed', false)
            ->where('enrollment_assessments.is_removed', false)
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->groupBy('pt.assessment_id')
            ->orderBy('pt.created_at', 'DESC')->get();
    }
    // Medical
    public function waiting_scheduled()
    {
        return $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*')
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_briefings.is_removed', false)
            ->groupBy('applicant_accounts.id')
            ->join('applicant_briefings', 'applicant_briefings.applicant_id', 'applicant_accounts.id')
            ->where('applicant_accounts.is_removed', false)
            ->leftJoin('applicant_medical_appointments as ama', 'ama.applicant_id', 'applicant_accounts.id')
            ->whereNull('ama.applicant_id');
    }
    public function scheduled()
    {
        return $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*')
            ->where('applicant_accounts.is_removed', false)
            ->join('applicant_briefings as ab', 'ab.applicant_id', 'applicant_accounts.id')
            ->where('ab.is_removed', false)
            ->join('applicant_medical_appointments as ama', 'ama.applicant_id', 'ab.applicant_id')
            ->where('ama.is_removed', false)->where('is_approved', false);
    }
    public function waiting_result()
    {
        return $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*')
            ->where('applicant_accounts.is_removed', false)
            ->join('applicant_briefings as ab', 'ab.applicant_id', 'applicant_accounts.id')
            ->where('ab.is_removed', false)
            ->join('applicant_medical_appointments as ama', 'ama.applicant_id', 'ab.applicant_id')
            ->where('ama.is_removed', false)->where('is_approved', true);
    }
}
