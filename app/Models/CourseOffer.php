<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class CourseOffer extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $fillable = ['course_name', 'course_code', 'school_level', 'is_removed'];

    public function course_subject($_data)
    {
        return $this->hasMany(CurriculumSubject::class, 'course_id')
            ->select('curriculum_subjects.id', 'subjects.subject_code', 'subjects.subject_name', 'subjects.units')
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
            ->where('is_removed', false);
        /* ->get() */
    }

    /**
     * It returns a list of enrollment_assessments that are not removed, are in the current academic
     * year, and have a payment transaction that is not removed
     *
     * @return The query is returning the enrollment_assessments table with the following conditions:
     * 1. The academic_id is the current academic id of the user
     * 2. The is_removed is false
     * 3. The payment_transactions is_removed is false
     * 4. The query is grouped by the enrollment_assessments id
     * 5. The query is ordered by the payment
     */
    public function enrollment_list()
    {
        $academic =  Auth::user()->staff->current_academic()->id;
        if (Cache::has('academic')) {
            $academic = base64_decode(Cache::get('academic'));
        }
        $_query = $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->select('enrollment_assessments.*')
            ->join('payment_assessments', 'enrollment_assessments.id', 'payment_assessments.enrollment_id')
            ->join('payment_transactions', 'payment_assessments.id', 'payment_transactions.assessment_id')
            ->where('enrollment_assessments.academic_id', $academic)
            ->where('enrollment_assessments.is_removed', false)
            ->where('payment_transactions.is_removed', false)
            ->leftJoin('student_cancellations', 'student_cancellations.enrollment_id', 'enrollment_assessments.id')
            ->whereNull('student_cancellations.id')
            ->groupBy('enrollment_assessments.id')
            ->orderBy('payment_transactions.created_at', 'DESC');
        return $_query;
    }
    /**
     * It returns a collection of `EnrollmentAssessment` models that are related to the current
     * `Course` model, and are filtered by the `academic_id`, `year_level`, and `is_removed` attributes
     *
     * @param data the year level
     *
     * @return It returns the enrollment_assessments table with the following columns:
     *         id, student_id, academic_id, course_id, year_level, is_removed, created_at, updated_at
     */
    public function enrollment_list_by_year_level($data)
    {
        $academic =  Auth::user()->staff->current_academic()->id;
        if (Cache::has('academic')) {
            $academic = base64_decode(Cache::get('academic'));
        }
        // Index 0 is Year Level
        // Index1 is Curriculum
        $_query = $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->select('enrollment_assessments.*')
            ->join('payment_assessments', 'enrollment_assessments.id', 'payment_assessments.enrollment_id')
            ->join('payment_transactions', 'payment_assessments.id', 'payment_transactions.assessment_id')
            ->where('enrollment_assessments.academic_id', $academic)
            ->where('enrollment_assessments.year_level', $data)
            ->where('enrollment_assessments.is_removed', false)
            ->where('payment_transactions.is_removed', false)
            ->leftJoin('student_cancellations', 'student_cancellations.enrollment_id', 'enrollment_assessments.id')
            ->where(function ($query) {
                $query->whereNull('student_cancellations.id')
                    ->orWhere('student_cancellations.type_of_cancellations', 'dropped');
            })
            ->groupBy('enrollment_assessments.id')
            ->orderBy('payment_transactions.created_at', 'DESC');
        if ($data === 1) {
            $_query = $_query->where('enrollment_assessments.curriculum_id', '!=', 7);
        }
        if ($data === 2) {
            $_query = $_query->where('enrollment_assessments.curriculum_id', '>', 4);
        }
        return $_query;
    }
    public function enrollment_list_by_year_level_without_cancellation($data)
    {

        // Index 0 is Year Level
        // Index1 is Curriculum
        $_query = $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->select('enrollment_assessments.*')
            ->join('payment_assessments', 'enrollment_assessments.id', 'payment_assessments.enrollment_id')
            ->join('payment_transactions', 'payment_assessments.id', 'payment_transactions.assessment_id')
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('enrollment_assessments.year_level', $data)
            ->where('enrollment_assessments.is_removed', false)
            ->where('payment_transactions.is_removed', false)/*
            ->leftJoin('student_cancellations', 'student_cancellations.enrollment_id', 'enrollment_assessments.id')
            ->whereNull('student_cancellations.id') */
            ->groupBy('enrollment_assessments.id')
            ->orderBy('payment_transactions.created_at', 'DESC');
        if ($data === 1) {
            $_query = $_query->where('enrollment_assessments.curriculum_id', '!=', 7);
        }
        if ($data === 2) {
            $_query = $_query->where('enrollment_assessments.curriculum_id', '>', 4);
        }
        return $_query;
    }
    public function enrollment_list_by_year_level_with_curriculum($data)
    {
        $academic =  Auth::user()->staff->current_academic()->id;
        if (Cache::has('academic')) {
            $academic = base64_decode(Cache::get('academic'));
        }
        $_query =  $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->select('enrollment_assessments.*')
            ->join('payment_assessments', 'enrollment_assessments.id', 'payment_assessments.enrollment_id')
            ->join('payment_transactions', 'payment_assessments.id', 'payment_transactions.assessment_id')
            ->where('enrollment_assessments.academic_id', $academic)
            ->where('enrollment_assessments.year_level', $data[0])
            ->where('enrollment_assessments.is_removed', false)
            ->where('payment_transactions.is_removed', false)
            ->groupBy('enrollment_assessments.id')
            ->orderBy('payment_transactions.created_at', 'DESC');
        if ($data[1] <= 4) {
            $_query = $_query->where('enrollment_assessments.curriculum_id', '<=', 4);
        }
        if ($data[1] >= 5) {
            $_query = $_query->where('enrollment_assessments.curriculum_id', '>=', 5);
        }
        return $_query;
    }
    public function student_enrollment_list($data)
    {
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->select('enrollment_assessments.*')
            //->select('enrollment_assessments.curriculum_id', 'enrollment_assessments.course_id', 'enrollment_assessments.academic_id', 'enrollment_assessments.year_level','enrollment_assessments.student_id')
            ->join('student_details', 'student_details.id', 'enrollment_assessments.student_id')
            ->join('payment_assessments', 'enrollment_assessments.id', 'payment_assessments.enrollment_id')
            ->join('payment_transactions', 'payment_assessments.id', 'payment_transactions.assessment_id')
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('enrollment_assessments.year_level', $data[0])
            ->where('enrollment_assessments.curriculum_id', $data[1])
            ->where('enrollment_assessments.is_removed', false)
            ->where('payment_transactions.is_removed', false)
            ->groupBy('enrollment_assessments.id')
            ->orderBy('student_details.last_name', 'asc')->orderBy('student_details.first_name', 'asc');
    }

    public function student_officially_enrolled_per_year($data)
    {
        $_query = $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->select('enrollment_assessments.*')
            //->select('enrollment_assessments.curriculum_id', 'enrollment_assessments.course_id', 'enrollment_assessments.academic_id', 'enrollment_assessments.year_level','enrollment_assessments.student_id')
            ->join('student_details', 'student_details.id', 'enrollment_assessments.student_id')
            ->join('payment_assessments', 'enrollment_assessments.id', 'payment_assessments.enrollment_id')
            ->join('payment_transactions', 'payment_assessments.id', 'payment_transactions.assessment_id')
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('enrollment_assessments.year_level', $data)
            //->where('enrollment_assessments.curriculum_id', $data[1])
            ->where('enrollment_assessments.is_removed', false)
            ->where('payment_transactions.is_removed', false)
            ->groupBy('enrollment_assessments.id')
            ->orderBy('student_details.last_name', 'asc')->orderBy('student_details.first_name', 'asc');
        if ($data === 1) {
            $_query = $_query->where('enrollment_assessments.curriculum_id', '!=', 7);
        }
        if ($data === 2) {
            $_query = $_query->where('enrollment_assessments.curriculum_id', '>', 4);
        }
        return $_query;
    }
    public function student_officially_enrolled_per_year_and_curriculum($data, $curriculum)
    {
        $_query = $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->select('enrollment_assessments.*')
            //->select('enrollment_assessments.curriculum_id', 'enrollment_assessments.course_id', 'enrollment_assessments.academic_id', 'enrollment_assessments.year_level','enrollment_assessments.student_id')
            ->join('student_details', 'student_details.id', 'enrollment_assessments.student_id')
            ->join('payment_assessments', 'enrollment_assessments.id', 'payment_assessments.enrollment_id')
            ->join('payment_transactions', 'payment_assessments.id', 'payment_transactions.assessment_id')
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('enrollment_assessments.year_level', $data)
            //->where('enrollment_assessments.curriculum_id', $data[1])
            ->where('enrollment_assessments.is_removed', false)
            ->where('payment_transactions.is_removed', false)
            ->groupBy('enrollment_assessments.id')
            ->orderBy('student_details.last_name', 'asc')->orderBy('student_details.first_name', 'asc');
        /*  if ($data === 1) {
            $_query = $_query->where('enrollment_assessments.curriculum_id', '!=', 7);
        }
        if ($data === 2) {
            $_query = $_query->where('enrollment_assessments.curriculum_id', '>', 4);
        } */
        if ($curriculum <= 4) {
            $_query = $_query->where('enrollment_assessments.curriculum_id', '<=', 4);
        }
        if ($curriculum >= 5) {
            $_query = $_query->where('enrollment_assessments.curriculum_id', '>=', 5);
        }
        return $_query;
    }
    public function enrolled_list($data)
    {
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->select('enrollment_assessments.*')
            ->join('payment_assessments', 'enrollment_assessments.id', 'payment_assessments.enrollment_id')
            ->join('payment_transactions', 'payment_assessments.id', 'payment_transactions.assessment_id')
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('enrollment_assessments.year_level', $data)
            ->where('enrollment_assessments.is_removed', false)
            ->where('payment_transactions.is_removed', false)
            ->leftJoin('student_cancellations', 'student_cancellations.enrollment_id', 'enrollment_assessments.id')
            ->whereNull('student_cancellations.id')
            ->groupBy('enrollment_assessments.id')
            ->orderBy('payment_transactions.created_at', 'DESC');
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
            ->leftJoin('student_cancellations', 'student_cancellations.enrollment_id', 'enrollment_assessments.id')
            ->whereNull('student_cancellations.id')
            ->where('enrollment_assessments.is_removed', false);
        //Get Year Level
        $_query = $_request->_year_level ? $_query->where('enrollment_assessments.year_level', $_request->_year_leve) : $_query;
        //Sorting index & value
        $_query = $_request->_sort == 'enrollment-date' ? $_query->orderBy('enrollment_assessments.created_at', 'DESC') : $_query;
        $_query = $_request->_sort == 'lastname' ? $_query->orderBy('sd.last_name', 'ASC')->orderBy('sd.first_name', 'ASC') : $_query;
        $_query = $_request->_sort == 'student-number' ? $_query->orderBy('sa.student_number', 'ASC') : $_query;
        if ($_request->_students) {
            $_student = explode(',', $_request->_students);
            $_count = count($_student);
            if ($_count > 1) {
                $_query = $_query->where('sd.last_name', 'like', '%' . trim($_student[0]) . '%')->where('sd.first_name', 'like', '%' . trim($_student[1]) . '%');
            } else {
                $_query = $_query->where('sd.last_name', 'like', '%' . trim($_student[0]) . '%');
            }
        }
        return $_query;
    }
    public function student_list()
    {
        $_level = (string) request()->input('_year_level') . '/C';
        return $this->hasMany(Section::class, 'course_id')
            ->select('sd.first_name', 'sd.last_name', 'ss.student_id', 'ss.section_id')
            ->join('student_sections as ss', 'ss.section_id', 'sections.id')
            ->join('student_details as sd', 'ss.student_id', 'sd.id')
            ->where('sections.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('sections.year_level', $_level)
            ->orderBy('sd.last_name', 'asc')
            ->orderBy('sd.first_name');
    }
    public function grading_student_list($_curriculum)
    {
        $_level = (string) request()->input('_year_level') . '/C';
        return $this->hasMany(Section::class, 'course_id')
            ->select('sd.first_name', 'sd.last_name', 'ss.student_id', 'ss.section_id', 'sd.middle_name')
            ->join('student_sections as ss', 'ss.section_id', 'sections.id')
            ->join('student_details as sd', 'ss.student_id', 'sd.id')
            ->join('enrollment_assessments as ea', 'sd.id', 'ea.student_id')
            ->where('sections.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('sections.year_level', $_level)
            ->where('ea.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('ea.curriculum_id', $_curriculum->id)
            ->orderBy('sd.last_name', 'asc')
            ->orderBy('sd.first_name');
    }
    public function previous_enrolled()
    {
        $_academic = AcademicYear::where('id', '<', Auth::user()->staff->current_academic()->id)
            ->orderBy('id', 'desc')
            ->first();
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->join('payment_assessments as pa', 'pa.enrollment_id', 'enrollment_assessments.id')
            /*  ->leftJoin('payment_transactions as pt', 'pt.assessment_id', 'pa.id')
             ->where('pt.remarks', 'Upon Enrollment') */
            ->where('enrollment_assessments.is_removed', false)
            ->where('enrollment_assessments.academic_id', $_academic->id)
            #->groupBy('pt.assessment_id')
            ->orderBy('pa.created_at', 'DESC');
    }
    public function expected_enrollee_year_level($data)
    {
        $_academic = AcademicYear::where('id', '<', Auth::user()->staff->current_academic()->id)
            ->orderBy('id', 'desc')
            ->first();
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->join('payment_assessments as pa', 'pa.enrollment_id', 'enrollment_assessments.id')
            ->where('enrollment_assessments.is_removed', false)
            ->where('enrollment_assessments.academic_id', $_academic->id)
            ->where('enrollment_assessments.year_level', $data)
            ->orderBy('pa.created_at', 'DESC');
    }
    public function students_clearance()
    {
        $_previous_academic = AcademicYear::where('id', '<', Auth::user()->staff->current_academic()->id)
            ->orderBy('id', 'desc')
            ->first();
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
        $_previous_academic = AcademicYear::where('id', '<', Auth::user()->staff->current_academic()->id)
            ->orderBy('id', 'desc')
            ->first();
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
        $_previous_academic = AcademicYear::where('id', '<', Auth::user()->staff->current_academic()->id)
            ->orderBy('id', 'desc')
            ->first();
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
        /*  $studentsList = StudentDetails::select('student_details.id', 'student_details.first_name', 'student_details.last_name')
        ->leftJoin('enrollment_applications as ea', 'ea.student_id', 'student_details.id')
        ->where('ea.academic_id', $_academic->id)
        ->whereNull('ea.is_approved')
        ->where('ea.is_removed', false)->paginate(10); */
        return $this->hasMany(EnrollmentApplication::class, 'course_id')
            ->where('is_removed', false)
            ->where('academic_id', Auth::user()->staff->current_academic()->id)
            ->whereNull('is_approved');
        /* return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->join('enrollment_applications as ea', 'ea.student_id', 'enrollment_assessments.student_id')
            ->whereNull('ea.is_approved')
            ->where('enrollment_assessments.is_removed', false)
            ->where('enrollment_assessments.academic_id', $_academic->id)
            ->where('ea.academic_id', Auth::user()->staff->current_academic()->id); */
    }

    public function enrollment_assessment_year_level($data)
    {
        $_academic = AcademicYear::where('id', '<', Auth::user()->staff->current_academic()->id)
            ->orderBy('id', 'desc')
            ->first();

        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->join('enrollment_applications as ea', 'ea.student_id', 'enrollment_assessments.student_id')
            ->whereNull('ea.is_approved')
            ->where('enrollment_assessments.is_removed', false)
            ->where('enrollment_assessments.year_level', $data)
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
    public function student_bridging_program_year_level($data)
    {
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('enrollment_assessments.bridging_program', 'with')
            ->where('enrollment_assessments.year_level', $data)
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
    public function payment_assessment_sort($data)
    {
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('enrollment_assessments.year_level', $data)
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
    public function payment_transaction_online_year_level($data)
    {
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->join('payment_assessments', 'payment_assessments.enrollment_id', 'enrollment_assessments.id')
            ->join('payment_trasanction_onlines', 'payment_assessments.id', 'payment_trasanction_onlines.assessment_id')
            //->leftJoin('payment_transactions as pt', 'pa.id', 'pt.assessment_id')
            //->whereNull('payment_assessments.id')
            ->where('enrollment_assessments.year_level', $data)
            ->where('payment_trasanction_onlines.is_removed', false)
            ->whereNull('payment_trasanction_onlines.is_approved');
    }
    public function payment_transaction_online_status_year_level($data)
    {
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->join('payment_assessments', 'payment_assessments.enrollment_id', 'enrollment_assessments.id')
            ->join('payment_trasanction_onlines', 'payment_assessments.id', 'payment_trasanction_onlines.assessment_id')
            //->leftJoin('payment_transactions as pt', 'pa.id', 'pt.assessment_id')
            //->whereNull('payment_assessments.id')
            ->where('enrollment_assessments.year_level', $data)
            ->where('payment_trasanction_onlines.is_removed', false)
            ->where('payment_trasanction_onlines.is_approved', 0);
    }
    public function sections()
    {
        $_academic = Auth::user()->staff->current_academic();
        return $this->hasMany(Section::class, 'course_id')
            ->where('academic_id', $_academic->id)
            ->where('is_removed', false)
            ->orderBy('section_name', 'Desc');
    }
    public function sections_academic($_academic)
    {
        return $this->hasMany(Section::class, 'course_id')
            ->where('academic_id', $_academic->id)
            ->where('is_removed', false)
            ->orderBy('section_name', 'Desc');
    }
    public function units($_data)
    {
        return $this->hasMany(CurriculumSubject::class, 'course_id')
            ->selectRaw('sum(s.units) as units')
            ->join('subjects as s', 's.id', 'curriculum_subjects.subject_id')
            ->where('curriculum_subjects.year_level', $_data->year_level)
            ->where('curriculum_subjects.curriculum_id', $_data->curriculum_id)
            ->where('curriculum_subjects.semester', $_data->academic->semester)
            ->where('curriculum_subjects.is_removed', false)
            ->first();
    }

    /* Applicant Model */
    #Applicant Count Dashboard Version 3
    function applicant_count_per_category($category)
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
        $query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*')
            ->where('applicant_accounts.is_removed', false)
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id);
        // Sort By Courses
        if ($category == 'registered_applicants') {
            $query = $query
                ->join($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->leftJoin($tblApplicantDocuments, $tblApplicantDocuments . '.applicant_id', 'applicant_accounts.id')
                ->whereNull($tblApplicantDocuments . '.applicant_id');
        }
        if ($category == 'bma_senior_high') {
            $query = $query->join($tblApplicantAlumia, $tblApplicantAlumia . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantAlumia . '.is_removed', false);
        }
        if ($category == 'for_checking') {
            $query = $query
                ->join($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($tblApplicantDocuments, $tblApplicantDocuments . '.applicant_id', '=', 'applicant_accounts.id')
                ->select(
                    'applicant_accounts.*',
                    DB::raw('(SELECT COUNT(' . $tblApplicantDocuments . '.is_approved)
                FROM ' . $tblApplicantDocuments . '
                WHERE ' . $tblApplicantDocuments . '.applicant_id = applicant_accounts.id
                AND ' . $tblApplicantDocuments . '.is_removed = 0
                AND ' . $tblApplicantDocuments . '.is_approved = 1) AS ApprovedDocuments'),
                    DB::raw('(
                    SELECT COUNT(' . $tblDocuments . '.id)
                    FROM ' . $tblDocuments . '
                    WHERE ' . $tblDocuments . '.department_id = 2
                    AND ' . $tblDocuments . '.is_removed = false
                    AND ' . $tblDocuments . '.year_level = (
                        SELECT IF(' . $applicantAccountTable . '.course_id = 3, 11, 4) as result
                        FROM ' . $applicantAccountTable . '
                        WHERE ' . $applicantAccountTable . '.id = ' . $tblApplicantDetails . '.applicant_id
                )) as documentCount')
                )
                ->leftJoin($tblApplicantNotQualifieds . ' as anq', 'anq.applicant_id', 'applicant_accounts.id')
                ->whereNull('anq.applicant_id')
                ->groupBy('applicant_accounts.id')
                ->havingRaw('COUNT(' . $tblApplicantDocuments . '.applicant_id) >= documentCount and ApprovedDocuments < documentCount');
        }
        if ($category == 'not_qualified') {
            $query = $query->join($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($tblApplicantNotQualifieds, $tblApplicantNotQualifieds . '.applicant_id', $applicantAccountTable . '.id')
                ->where($tblApplicantNotQualifieds . '.is_removed', false)
                ->where($tblApplicantNotQualifieds . '.academic_id', Auth::user()->staff->current_academic()->id)
                ->groupBy('applicant_accounts.id');
        }
        if ($category == 'qualified') {
            $query = $query
                ->join($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($tblApplicantDocuments, $tblApplicantDocuments . '.applicant_id', '=', 'applicant_accounts.id')
                ->select(
                    'applicant_accounts.*',
                    DB::raw('(SELECT COUNT(' . $tblApplicantDocuments . '.is_approved)
            FROM ' . $tblApplicantDocuments . '
            WHERE ' . $tblApplicantDocuments . '.applicant_id = applicant_accounts.id
            AND ' . $tblApplicantDocuments . '.is_removed = 0
            AND ' . $tblApplicantDocuments . '.is_approved = 1) AS ApprovedDocuments'),
                    DB::raw('(
                SELECT COUNT(' . $tblDocuments . '.id)
                FROM ' . $tblDocuments . '
                WHERE ' . $tblDocuments . '.department_id = 2
                AND ' . $tblDocuments . '.is_removed = false
                AND ' . $tblDocuments . '.year_level = (
                    SELECT IF(' . $applicantAccountTable . '.course_id = 3, 11, 4) as result
                    FROM ' . $applicantAccountTable . '
                    WHERE ' . $applicantAccountTable . '.id = ' . $tblApplicantDetails . '.applicant_id
                )) as documentCount')
                )
                ->leftJoin($tblApplicantNotQualifieds . ' as anq', 'anq.applicant_id', 'applicant_accounts.id')
                ->whereNull('anq.applicant_id')
                ->groupBy('applicant_accounts.id')
                ->havingRaw('COUNT(' . $tblApplicantDocuments . '.applicant_id) >= documentCount and ApprovedDocuments = documentCount');
        }
        if ($category == 'qualified_for_entrance_examination') {
            $query = $query->join($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($tblApplicantDocuments, $tblApplicantDocuments . '.applicant_id', '=', 'applicant_accounts.id')
                ->select('applicant_accounts.*', DB::raw('(SELECT COUNT(' . $tblApplicantDocuments . '.is_approved)
                            FROM ' . $tblApplicantDocuments . '
                            WHERE ' . $tblApplicantDocuments . '.applicant_id = applicant_accounts.id
                            AND ' . $tblApplicantDocuments . '.is_removed = 0
                            AND ' . $tblApplicantDocuments . '.is_approved = 1) AS ApprovedDocuments'), DB::raw('(
                                SELECT COUNT(' . $tblDocuments . '.id)
                                FROM ' . $tblDocuments . '
                                WHERE ' . $tblDocuments . '.department_id = 2
                                AND ' . $tblDocuments . '.is_removed = false
                                AND ' . $tblDocuments . '.year_level = (
                                    SELECT IF(' . $applicantAccountTable . '.course_id = 3, 11, 4) as result
                                    FROM ' . $applicantAccountTable . '
                                    WHERE ' . $applicantAccountTable . '.id = ' . $tblApplicantDetails . '.applicant_id
                                ))as documentCount'))
                ->leftJoin($tblApplicantNotQualifieds . ' as anq', 'anq.applicant_id', 'applicant_accounts.id')
                ->leftJoin($tblApplicantPayment, $tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->leftJoin($tblApplicantAlumia, $tblApplicantAlumia . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantAlumia . '.applicant_id')
                ->whereNull($tblApplicantPayment . '.applicant_id')
                ->whereNull('anq.applicant_id')
                ->groupBy('applicant_accounts.id')
                ->havingRaw('COUNT(' . $tblApplicantDocuments . '.applicant_id) >= documentCount and ApprovedDocuments = documentCount');
        }
        if ($category == 'examination_payment') {
            $query = $query->join($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($tblApplicantDocuments, $tblApplicantDocuments . '.applicant_id', '=', 'applicant_accounts.id')
                ->select('applicant_accounts.*', DB::raw('(SELECT COUNT(' . $tblApplicantDocuments . '.is_approved)
                        FROM ' . $tblApplicantDocuments . '
                        WHERE ' . $tblApplicantDocuments . '.applicant_id = applicant_accounts.id
                        AND ' . $tblApplicantDocuments . '.is_removed = 0
                        AND ' . $tblApplicantDocuments . '.is_approved = 1) AS ApprovedDocuments'), DB::raw('(
                            SELECT COUNT(' . $tblDocuments . '.id)
                            FROM ' . $tblDocuments . '
                            WHERE ' . $tblDocuments . '.department_id = 2
                            AND ' . $tblDocuments . '.is_removed = false
                            AND ' . $tblDocuments . '.year_level = (
                                SELECT IF(' . $applicantAccountTable . '.course_id = 3, 11, 4) as result
                                FROM ' . $applicantAccountTable . '
                                WHERE ' . $applicantAccountTable . '.id = ' . $tblApplicantDetails . '.applicant_id
                            ))as documentCount'))
                ->join($tblApplicantPayment, $tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->where(function ($query) {
                    $query->whereNull(env('DB_DATABASE_SECOND') . '.applicant_payments' . '.is_approved')
                        ->orWhere(env('DB_DATABASE_SECOND') . '.applicant_payments' . '.is_approved', false);
                })
                ->where($tblApplicantPayment . '.is_removed', false)

                ->groupBy('applicant_accounts.id')
                ->havingRaw('COUNT(' . $tblApplicantDocuments . '.applicant_id) >= documentCount and ApprovedDocuments = documentCount');
        }
        if ($category == 'entrance_examination') {
            $query = $query->join($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($tblApplicantPayment, $tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantPayment . '.is_approved', true)
                ->where($tblApplicantPayment . '.is_removed', false)
                ->join($tblApplicantExamination, $tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantExamination . '.is_removed', false)
                ->whereNull($tblApplicantExamination . '.is_finish')
                ->groupBy($tblApplicantExamination . '.applicant_id');
        }
        if ($category == 'examination_passed') {
            $query =  $query->join($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($tblApplicantPayment, $tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantPayment . '.is_approved', true)
                ->where($tblApplicantPayment . '.is_removed', false)
                ->join($tblApplicantExamination, $tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantExamination . '.is_removed', false)
                ->where($tblApplicantExamination . '.is_finish', true)
                ->where(function ($query) {
                    $query->select(DB::raw('COUNT(*)'))
                        ->from(env('DB_DATABASE_SECOND') . '.applicant_examination_answers')
                        ->join(env('DB_DATABASE') . '.examination_question_choices', env('DB_DATABASE') . '.examination_question_choices.id', '=', env('DB_DATABASE_SECOND') . '.applicant_examination_answers' . '.choices_id')
                        ->where(env('DB_DATABASE') . '.examination_question_choices.is_answer', true)
                        ->whereColumn(env('DB_DATABASE_SECOND') . '.applicant_examination_answers' . '.examination_id', env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.id');
                }, '>=', function ($query) {
                    $query->select(DB::raw('IF(applicant_accounts.course_id = 3, 20, 100)'));
                })
                ->groupBy('applicant_accounts.id')->orderBy($tblApplicantExamination . '.created_at', 'desc');;
        }
        if ($category == 'examination_failed') {
            $query =  $query->join($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($tblApplicantPayment, $tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantPayment . '.is_approved', true)
                ->where($tblApplicantPayment . '.is_removed', false)
                ->join($tblApplicantExamination, $tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantExamination . '.is_removed', false)
                ->where($tblApplicantExamination . '.is_finish', true)
                ->where(function ($query) {
                    $query->select(DB::raw('COUNT(*)'))
                        ->from(env('DB_DATABASE_SECOND') . '.applicant_examination_answers')
                        ->join(env('DB_DATABASE') . '.examination_question_choices', env('DB_DATABASE') . '.examination_question_choices.id', '=', env('DB_DATABASE_SECOND') . '.applicant_examination_answers' . '.choices_id')
                        ->where(env('DB_DATABASE') . '.examination_question_choices.is_answer', true)
                        ->whereColumn(env('DB_DATABASE_SECOND') . '.applicant_examination_answers' . '.examination_id', env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.id');
                }, '<', function ($query) {
                    $query->select(DB::raw('IF(applicant_accounts.course_id = 3, 20, 100)'));
                })
                ->groupBy('applicant_accounts.id')->orderBy($tblApplicantExamination . '.created_at', 'desc');;
        }
        if ($category == 'no_of_qualified_examinees') {
            $query =  $query->join($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($tblApplicantPayment, $tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantPayment . '.is_approved', true)
                ->where($tblApplicantPayment . '.is_removed', false)
                ->join($tblApplicantExamination, $tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantExamination . '.is_removed', false)
                ->where($tblApplicantExamination . '.is_finish', true)
                ->groupBy('applicant_accounts.id')->orderBy($tblApplicantExamination . '.created_at', 'desc');
        }
        if ($category == 'expected_attendees') {
            $query =  $query->join($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($tblApplicantPayment, $tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantPayment . '.is_approved', true)
                ->where($tblApplicantPayment . '.is_removed', false)
                ->join($tblApplicantExamination, $tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantExamination . '.is_removed', false)
                ->where($tblApplicantExamination . '.is_finish', true)
                ->join($tblApplicantOrientation, $tblApplicantOrientation . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantOrientation . '.is_completed', false)
                ->groupBy('applicant_accounts.id')/* ->orderBy($tblApplicantOrientationScheduled . '.created_at', 'desc') */;
        }
        if ($category == 'total_attendees') {
            $query =  $query->join($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($tblApplicantPayment, $tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantPayment . '.is_approved', true)
                ->where($tblApplicantPayment . '.is_removed', false)
                ->join($tblApplicantExamination, $tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantExamination . '.is_removed', false)
                ->where($tblApplicantExamination . '.is_finish', true)
                ->join($tblApplicantOrientation, $tblApplicantOrientation . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantOrientation . '.is_completed', true)
                ->groupBy('applicant_accounts.id')/* ->orderBy($tblApplicantOrientationScheduled . '.created_at', 'desc') */;
        }
        if ($category == 'for_medical_schedule') {
            $query =  $query->join($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($tblApplicantPayment, $tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantPayment . '.is_approved', true)
                ->where($tblApplicantPayment . '.is_removed', false)
                ->join($tblApplicantExamination, $tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantExamination . '.is_removed', false)
                ->where($tblApplicantExamination . '.is_finish', true)
                ->join($tblApplicantOrientation, $tblApplicantOrientation . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantOrientation . '.is_completed', true)
                ->leftJoin(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', 'applicant_accounts.id')
                ->whereNull('ama.applicant_id')
                ->groupBy('applicant_accounts.id')/* ->orderBy($tblApplicantOrientationScheduled . '.created_at', 'desc') */;
        }
        if ($category == 'medical_schedule') {
            $query =  $query->join($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($tblApplicantPayment, $tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantPayment . '.is_approved', true)
                ->where($tblApplicantPayment . '.is_removed', false)
                ->join($tblApplicantExamination, $tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantExamination . '.is_removed', false)
                ->where($tblApplicantExamination . '.is_finish', true)
                ->join($tblApplicantOrientation, $tblApplicantOrientation . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantOrientation . '.is_completed', true)
                ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', env('DB_DATABASE_SECOND') . '.applicant_briefings.applicant_id')
                ->where('ama.is_removed', false)
                ->where('ama.is_approved', false)
                ->groupBy('applicant_accounts.id')/* ->orderBy($tblApplicantOrientationScheduled . '.created_at', 'desc') */;
        }
        if ($category == 'waiting_for_medical_results') {
            $query =  $query->join($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($tblApplicantPayment, $tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantPayment . '.is_approved', true)
                ->where($tblApplicantPayment . '.is_removed', false)
                ->join($tblApplicantExamination, $tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantExamination . '.is_removed', false)
                ->where($tblApplicantExamination . '.is_finish', true)
                ->join($tblApplicantOrientation, $tblApplicantOrientation . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantOrientation . '.is_completed', true)
                ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', env('DB_DATABASE_SECOND') . '.applicant_briefings.applicant_id')
                ->where('ama.is_removed', false)
                ->where('ama.is_approved', true)
                ->leftJoin(env('DB_DATABASE_SECOND') . '.applicant_medical_results', env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id', 'ama.applicant_id')
                ->whereNull(env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id')
                ->groupBy('applicant_accounts.id')/* ->orderBy($tblApplicantOrientationScheduled . '.created_at', 'desc') */;
        }
        if ($category == 'medical_result') {
            $query =  $query->join($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($tblApplicantPayment, $tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantPayment . '.is_approved', true)
                ->where($tblApplicantPayment . '.is_removed', false)
                ->join($tblApplicantExamination, $tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantExamination . '.is_removed', false)
                ->where($tblApplicantExamination . '.is_finish', true)
                ->join($tblApplicantOrientation, $tblApplicantOrientation . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantOrientation . '.is_completed', true)
                ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', env('DB_DATABASE_SECOND') . '.applicant_briefings.applicant_id')
                ->where('ama.is_removed', false)
                ->where('ama.is_approved', true)
                ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_results', env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id', env('DB_DATABASE_SECOND') . '.applicant_briefings.applicant_id')
                ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_removed', false)
                ->groupBy('applicant_accounts.id')->orderBy(env('DB_DATABASE_SECOND') . '.applicant_medical_results.created_at', 'desc');
        }
        if ($category == 'qualified_to_enrollment') {
            $query =  $query->join($tblApplicantDetails, $tblApplicantDetails . '.applicant_id', 'applicant_accounts.id')
                ->join($tblApplicantPayment, $tblApplicantPayment . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantPayment . '.is_approved', true)
                ->where($tblApplicantPayment . '.is_removed', false)
                ->join($tblApplicantExamination, $tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantExamination . '.is_removed', false)
                ->where($tblApplicantExamination . '.is_finish', true)
                ->join($tblApplicantOrientation, $tblApplicantOrientation . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantOrientation . '.is_completed', true)
                ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', env('DB_DATABASE_SECOND') . '.applicant_briefings.applicant_id')
                ->where('ama.is_removed', false)
                ->where('ama.is_approved', true)
                ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_results', env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id', env('DB_DATABASE_SECOND') . '.applicant_briefings.applicant_id')
                ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_removed', false)
                ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_fit', true)
                ->groupBy('applicant_accounts.id')->orderBy(env('DB_DATABASE_SECOND') . '.applicant_medical_results.created_at', 'desc');
        }

        return $query->get();
    }
    #Pre-Registration Applicant without a files
    public function applicant_pre_registrations()
    {
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*')
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id') # Applicant Account
            ->join(env('DB_DATABASE_SECOND') . '.applicant_detials', env('DB_DATABASE_SECOND') . '.applicant_detials.applicant_id', 'applicant_accounts.id') # Join Applicant Details
            ->leftJoin(env('DB_DATABASE_SECOND') . '.applicant_documents', env('DB_DATABASE_SECOND') . '.applicant_documents.applicant_id', 'applicant_accounts.id')
            ->whereNull(env('DB_DATABASE_SECOND') . '.applicant_documents.applicant_id');
        //Searching Tool
        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_count > 0 ? $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%') : $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%')->where(env('DB_DATABASE_SECOND') . '.applicant_detials.first_name', 'like', '%' . trim($_student[1]) . '%');
        }
        return $_query;
    }
    function applicant_registrants()
    {
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*')
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id') # Applicant Account
            ->join(env('DB_DATABASE_SECOND') . '.applicant_detials', env('DB_DATABASE_SECOND') . '.applicant_detials.applicant_id', 'applicant_accounts.id') # Join Applicant Details
            ->join(env('DB_DATABASE_SECOND') . '.applicant_documents', env('DB_DATABASE_SECOND') . '.applicant_documents.applicant_id', 'applicant_accounts.id');

        return $_query;
    }
    # Applicant Incomplete Documents
    public function applicant_incomplete_documents()
    {
        $_level = $this->id == 3 ? 11 : 4;
        $_documents = Documents::where('department_id', 2)
            ->where('year_level', $_level)
            ->where('is_removed', false)
            ->count();
        $_query =  $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*')
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id') # Applicant Account
            ->join(env('DB_DATABASE_SECOND') . '.applicant_detials', env('DB_DATABASE_SECOND') . '.applicant_detials.applicant_id', 'applicant_accounts.id') # Join Applicant Details
            ->join(env('DB_DATABASE_SECOND') . '.applicant_documents as sd', 'sd.applicant_id', 'applicant_accounts.id')
            ->having(DB::raw('COUNT(sd.id)'), '<', $_documents); # Applicant Documents
        //Searching Tool
        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_count > 0 ? $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%') : $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%')->where(env('DB_DATABASE_SECOND') . '.applicant_detials.first_name', 'like', '%' . trim($_student[1]) . '%');
        }
        return $_query;
    }
    # Applicant For Verification of Documents
    public function applicant_for_checking()
    {
        $_level = $this->id == 3 ? 11 : 4;
        $_documents = Documents::where('department_id', 2)
            ->where('year_level', $_level)
            ->where('is_removed', false)
            ->count();
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*')
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id') # Applicant Account
            ->join(env('DB_DATABASE_SECOND') . '.applicant_detials', env('DB_DATABASE_SECOND') . '.applicant_detials.applicant_id', 'applicant_accounts.id') # Join Applicant Details
            ->join(env('DB_DATABASE_SECOND') . '.applicant_documents as sd', 'sd.applicant_id', 'applicant_accounts.id')
            ->leftJoin(env('DB_DATABASE_SECOND') . '.applicant_not_qualifieds', env('DB_DATABASE_SECOND') . '.applicant_not_qualifieds.applicant_id', 'applicant_accounts.id')
            ->having(DB::raw('COUNT(sd.id)'), '>=', $_documents) # Applicant Documents
            ->whereNull(env('DB_DATABASE_SECOND') . '.applicant_not_qualifieds.applicant_id')
            ->where(
                function ($_subQuery) {
                    $_subQuery
                        ->select(DB::raw('count("is_approved")'))
                        ->from(env('DB_DATABASE_SECOND') . '.applicant_documents')
                        ->whereColumn(env('DB_DATABASE_SECOND') . '.applicant_documents.applicant_id', 'applicant_accounts.id')
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_removed', false)
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_approved', true);
                },
                '<',
                $_documents,
            ); # Applicant Documents
        //Searching Tool
        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_count > 0 ? $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%') : $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%')->where(env('DB_DATABASE_SECOND') . '.applicant_detials.first_name', 'like', '%' . trim($_student[1]) . '%');
        }
        return $_query;
    }
    # Applicant Not Qualified

    public function applicant_not_qualified()
    {
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*')
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id') # Applicant Account
            ->join(env('DB_DATABASE_SECOND') . '.applicant_detials', env('DB_DATABASE_SECOND') . '.applicant_detials.applicant_id', 'applicant_accounts.id') # Join Applicant Details
            ->join(env('DB_DATABASE_SECOND') . '.applicant_not_qualifieds', env('DB_DATABASE_SECOND') . '.applicant_not_qualifieds.applicant_id', 'applicant_accounts.id')
            ->where(env('DB_DATABASE_SECOND') . '.applicant_not_qualifieds.is_removed', false)
            /*  ->join('applicant_documents as sd', 'sd.applicant_id', 'applicant_accounts.id')
            ->where('sd.feedback', 'like', '%Sorry you did not meet the Grade requirement%')
            ->where('sd.is_removed',false) */;

        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_count > 0 ? $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%') : $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%')->where(env('DB_DATABASE_SECOND') . '.applicant_detials.first_name', 'like', '%' . trim($_student[1]) . '%');
        }
        return $_query;
    }

    # Applicant Documents Approved
    public function applicant_verified_documents()
    {
        $_level = $this->id == 3 ? 11 : 4;
        $_documents = Documents::where('department_id', 2)
            ->where('year_level', $_level)
            ->where('is_removed', false)
            ->count();
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*')
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id') # Applicant Account
            ->join(env('DB_DATABASE_SECOND') . '.applicant_detials', env('DB_DATABASE_SECOND') . '.applicant_detials.applicant_id', 'applicant_accounts.id') # Join Applicant Details
            ->where(
                function ($_subQuery) {
                    $_subQuery
                        ->select(DB::raw('count("is_approved")'))
                        ->from(env('DB_DATABASE_SECOND') . '.applicant_documents')
                        ->whereColumn(env('DB_DATABASE_SECOND') . '.applicant_documents.applicant_id', 'applicant_accounts.id')
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_removed', false)
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_approved', true);
                },
                '>=',
                $_documents,
            ) # Applicant Documents
            ->leftJoin(env('DB_DATABASE_SECOND') . '.applicant_payments', env('DB_DATABASE_SECOND') . '.applicant_payments.applicant_id', 'applicant_accounts.id')
            ->leftJoin(env('DB_DATABASE_SECOND') . '.applicant_alumnias', env('DB_DATABASE_SECOND') . '.applicant_alumnias.applicant_id', 'applicant_accounts.id')
            ->where(env('DB_DATABASE_SECOND') . '.applicant_alumnias.applicant_id')
            ->whereNull(env('DB_DATABASE_SECOND') . '.applicant_payments.applicant_id'); # Applicant Payments
        //Searching Tool
        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_count > 0 ? $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%') : $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%')->where(env('DB_DATABASE_SECOND') . '.applicant_detials.first_name', 'like', '%' . trim($_student[1]) . '%');
        }
        return $_query;
    }
    # Applicant BMA Alumnia
    function applicant_alumnia()
    {
        $_level = $this->id == 3 ? 11 : 4;
        $_documents = Documents::where('department_id', 2)
            ->where('year_level', $_level)
            ->where('is_removed', false)
            ->count();
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*')
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id') # Applicant Account
            ->join(env('DB_DATABASE_SECOND') . '.applicant_detials', env('DB_DATABASE_SECOND') . '.applicant_detials.applicant_id', 'applicant_accounts.id') # Join Applicant Details
            ->where(
                function ($_subQuery) {
                    $_subQuery
                        ->select(DB::raw('count("is_approved")'))
                        ->from(env('DB_DATABASE_SECOND') . '.applicant_documents')
                        ->whereColumn(env('DB_DATABASE_SECOND') . '.applicant_documents.applicant_id', 'applicant_accounts.id')
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_removed', false)
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_approved', true);
                },
                '>=',
                $_documents,
            ) # Applicant Documents
            ->join(env('DB_DATABASE_SECOND') . '.applicant_alumnias', env('DB_DATABASE_SECOND') . '.applicant_alumnias.applicant_id', 'applicant_accounts.id')
            ->where(env('DB_DATABASE_SECOND') . '.applicant_alumnias.is_removed', false);
        //Searching Tool
        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_count > 0 ? $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%') : $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%')->where(env('DB_DATABASE_SECOND') . '.applicant_detials.first_name', 'like', '%' . trim($_student[1]) . '%');
        }
        return $_query;
    }
    # Applicant Payment Verification for Entrance Examination
    public function applicant_payment_verification_v2()
    {
        $_level = $this->id == 3 ? 11 : 4;
        $_documents = Documents::where('department_id', 2)
            ->where('year_level', $_level)
            ->where('is_removed', false)
            ->count();
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*')
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id') # Applicant Account
            ->join(env('DB_DATABASE_SECOND') . '.applicant_detials', env('DB_DATABASE_SECOND') . '.applicant_detials.applicant_id', 'applicant_accounts.id') # Join Applicant Details
            ->where(
                function ($_subQuery) {
                    $_subQuery
                        ->select(DB::raw('count("is_approved")'))
                        ->from(env('DB_DATABASE_SECOND') . '.applicant_documents')
                        ->whereColumn(env('DB_DATABASE_SECOND') . '.applicant_documents.applicant_id', 'applicant_accounts.id')
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_removed', false)
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_approved', true);
                },
                '>=',
                $_documents,
            ) # Applicant Documents
            ->leftJoin(env('DB_DATABASE_SECOND') . '.applicant_payments', env('DB_DATABASE_SECOND') . '.applicant_payments.applicant_id', 'applicant_accounts.id')
            ->where(env('DB_DATABASE_SECOND') . '.applicant_payments.is_removed', false)
            ->where(env('DB_DATABASE_SECOND') . '.applicant_payments.is_approved', false);
        #->where(env('DB_DATABASE_SECOND').'.applicant_payments.is_approved', null)
        #->whereBetween(env('DB_DATABASE_SECOND').'.applicant_payments.is_approved',[false,null])
        #->whereNull(env('DB_DATABASE_SECOND').'.applicant_payments.is_approved') # Applicant Payments
        //Searching Tool
        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_count > 0 ? $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%') : $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%')->where(env('DB_DATABASE_SECOND') . '.applicant_detials.first_name', 'like', '%' . trim($_student[1]) . '%');
        }
        return $_query;
    }
    # Applicant Payment Verified
    public function applicant_payment_verified()
    {
        $_level = $this->id == 3 ? 11 : 4;
        $_documents = Documents::where('department_id', 2)
            ->where('year_level', $_level)
            ->where('is_removed', false)
            ->count();
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*') # Applicant Account
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id')
            ->join(env('DB_DATABASE_SECOND') . '.applicant_detials', env('DB_DATABASE_SECOND') . '.applicant_detials.applicant_id', 'applicant_accounts.id') # Join Applicant Details
            ->where(
                function ($_subQuery) {
                    # Applicant Documents
                    $_subQuery
                        ->select(DB::raw('count("is_approved")'))
                        ->from(env('DB_DATABASE_SECOND') . '.applicant_documents')
                        ->whereColumn(env('DB_DATABASE_SECOND') . '.applicant_documents.applicant_id', 'applicant_accounts.id')
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_removed', false)
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_approved', true);
                },
                '>=',
                $_documents,
            )
            ->leftJoin(env('DB_DATABASE_SECOND') . '.applicant_payments', env('DB_DATABASE_SECOND') . '.applicant_payments.applicant_id', 'applicant_accounts.id') # Applicant Payments
            ->where(env('DB_DATABASE_SECOND') . '.applicant_payments.is_removed', false)
            ->where(env('DB_DATABASE_SECOND') . '.applicant_payments.is_approved', true)
            ->join(env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations', env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.applicant_id', 'applicant_accounts.id')
            ->where(env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.is_removed', false)
            ->whereNull(env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.is_finish');
        //Searching Tool
        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_count > 0 ? $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%') : $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%')->where(env('DB_DATABASE_SECOND') . '.applicant_detials.first_name', 'like', '%' . trim($_student[1]) . '%');
        }
        return $_query;
    }
    # Applicant Examination On-going
    public function applicant_examination_ongoing()
    {
        $_level = $this->id == 3 ? 11 : 4;
        $_documents = Documents::where('department_id', 2)
            ->where('year_level', $_level)
            ->where('is_removed', false)
            ->count();
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*') # Applicant Account
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id')
            ->join(env('DB_DATABASE_SECOND') . '.applicant_detials', env('DB_DATABASE_SECOND') . '.applicant_detials.applicant_id', 'applicant_accounts.id') # Join Applicant Details
            ->where(
                function ($_subQuery) {
                    # Applicant Documents
                    $_subQuery
                        ->select(DB::raw('count("is_approved")'))
                        ->from(env('DB_DATABASE_SECOND') . '.applicant_documents')
                        ->whereColumn(env('DB_DATABASE_SECOND') . '.applicant_documents.applicant_id', 'applicant_accounts.id')
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_removed', false)
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_approved', true);
                },
                '>=',
                $_documents,
            )
            ->leftJoin(env('DB_DATABASE_SECOND') . '.applicant_payments', env('DB_DATABASE_SECOND') . '.applicant_payments.applicant_id', 'applicant_accounts.id') # Applicant Payments
            ->where(env('DB_DATABASE_SECOND') . '.applicant_payments.is_removed', false)
            ->where(env('DB_DATABASE_SECOND') . '.applicant_payments.is_approved', true)
            ->join(env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations', env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.applicant_id', 'applicant_accounts.id')
            ->where(env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.is_removed', false)
            ->where(env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.is_finish', false);
        //Searching Tool
        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_count > 0 ? $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%') : $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%')->where(env('DB_DATABASE_SECOND') . '.applicant_detials.first_name', 'like', '%' . trim($_student[1]) . '%');
        }
        return $_query;
    }
    # Applicant Examination Passed
    public function applicant_examination_passed()
    {
        $_level = $this->id == 3 ? 11 : 4;
        $_item = $this->id == 3 ? 100 : 200;
        $_point = $this->id == 3 ? 5 : 50;
        $_documents = Documents::where('department_id', 2)
            ->where('year_level', $_level)
            ->where('is_removed', false)
            ->count();
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*') # Applicant Account
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id')
            ->join(env('DB_DATABASE_SECOND') . '.applicant_detials', env('DB_DATABASE_SECOND') . '.applicant_detials.applicant_id', 'applicant_accounts.id') # Join Applicant Details
            ->where(
                function ($_subQuery) {
                    # Applicant Documents
                    $_subQuery
                        ->select(DB::raw('count("is_approved")'))
                        ->from(env('DB_DATABASE_SECOND') . '.applicant_documents')
                        ->whereColumn(env('DB_DATABASE_SECOND') . '.applicant_documents.applicant_id', 'applicant_accounts.id')
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_removed', false)
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_approved', true);
                },
                '>=',
                $_documents,
            )
            ->leftJoin(env('DB_DATABASE_SECOND') . '.applicant_payments', env('DB_DATABASE_SECOND') . '.applicant_payments.applicant_id', 'applicant_accounts.id') # Applicant Payments
            ->where(env('DB_DATABASE_SECOND') . '.applicant_payments.is_removed', false)
            ->where(env('DB_DATABASE_SECOND') . '.applicant_payments.is_approved', true)
            ->join(env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations', env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.applicant_id', 'applicant_accounts.id')
            ->where(env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.is_removed', false)
            ->where(env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.is_finish', true)
            ->where(
                DB::raw(
                    '(SELECT ((COUNT(eqc.is_answer)/' .
                        $_item .
                        ")*100) as exam_result
                FROM " . env('DB_DATABASE_SECOND') . ".applicant_examination_answers as aea
                inner join " . env('DB_DATABASE') . ".examination_question_choices as eqc
                on eqc.id = aea.choices_id
                where eqc.is_answer = true and aea.examination_id = applicant_entrance_examinations.id)",
                ),
                '>=',
                $_point,
            )
            ->orderBy(env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.updated_at', 'desc');
        //Searching Tool
        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_count > 0 ? $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%') : $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%')->where(env('DB_DATABASE_SECOND') . '.applicant_detials.first_name', 'like', '%' . trim($_student[1]) . '%');
        }
        return $_query;
    }
    # Applicant Examination Failed
    public function applicant_examination_failed()
    {
        $_level = $this->id == 3 ? 11 : 4;
        $_item = $this->id == 3 ? 100 : 200;
        $_point = $this->id == 3 ? 5 : 50;
        $_documents = Documents::where('department_id', 2)
            ->where('year_level', $_level)
            ->where('is_removed', false)
            ->count();
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*') # Applicant Account
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id')
            ->join(env('DB_DATABASE_SECOND') . '.applicant_detials', env('DB_DATABASE_SECOND') . '.applicant_detials.applicant_id', 'applicant_accounts.id') # Join Applicant Details
            ->where(
                function ($_subQuery) {
                    # Applicant Documents
                    $_subQuery
                        ->select(DB::raw('count("is_approved")'))
                        ->from(env('DB_DATABASE_SECOND') . '.applicant_documents')
                        ->whereColumn(env('DB_DATABASE_SECOND') . '.applicant_documents.applicant_id', 'applicant_accounts.id')
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_removed', false)
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_approved', true);
                },
                '>=',
                $_documents,
            )
            ->leftJoin(env('DB_DATABASE_SECOND') . '.applicant_payments', env('DB_DATABASE_SECOND') . '.applicant_payments.applicant_id', 'applicant_accounts.id') # Applicant Payments
            ->where(env('DB_DATABASE_SECOND') . '.applicant_payments.is_removed', false)
            ->where(env('DB_DATABASE_SECOND') . '.applicant_payments.is_approved', true)
            ->join(env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations', env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.applicant_id', 'applicant_accounts.id')
            ->where(env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.is_removed', false)
            ->where(env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.is_finish', true)
            ->where(
                DB::raw(
                    '(SELECT ((COUNT(eqc.is_answer)/' .
                        $_item .
                        ")*100) as exam_result
                FROM " . env('DB_DATABASE_SECOND') . ".applicant_examination_answers as aea
                inner join " . env('DB_DATABASE') . ".examination_question_choices as eqc
                on eqc.id = aea.choices_id
                where eqc.is_answer = true and aea.examination_id = applicant_entrance_examinations.id)",
                ),
                '<',
                $_point,
            )
            ->orderBy(env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.updated_at', 'desc');
        //Searching Tool
        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_count > 0 ? $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%') : $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%')->where(env('DB_DATABASE_SECOND') . '.applicant_detials.first_name', 'like', '%' . trim($_student[1]) . '%');
        }
        return $_query;
    }
    /* Verified Applicants */
    public function verified_applicants()
    {
        $_level = $this->id == 3 ? 11 : 4;
        $_documents = Documents::where('department_id', 2)
            ->where('year_level', $_level)
            ->where('is_removed', false)
            ->count();
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*')
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->where(
                function ($_subQuery) {
                    $_subQuery
                        ->select(DB::raw('count("is_approved")'))
                        ->from(env('DB_DATABASE_SECOND') . '.applicant_documents')
                        ->whereColumn(env('DB_DATABASE_SECOND') . '.applicant_documents.applicant_id', 'applicant_accounts.id')
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_removed', false)
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_approved', true);
                },
                '>=',
                $_documents,
            )
            /*  ->orderBy(env('DB_DATABASE_SECOND').'.applicant_detials.last_name', 'asc') */
            ->groupBy('applicant_accounts.id');

        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_count > 0 ? $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%') : $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%')->where(env('DB_DATABASE_SECOND') . '.applicant_detials.first_name', 'like', '%' . trim($_student[1]) . '%');
        }
        return $_query;
    }
    public function verified_applicants_v2()
    {
        $_level = $this->id == 3 ? 11 : 4;
        $_documents = Documents::where('department_id', 2)
            ->where('year_level', $_level)
            ->where('is_removed', false)
            ->count();
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*', env('DB_DATABASE_SECOND') . '.applicant_detials.last_name')
            ->join(env('DB_DATABASE_SECOND') . '.applicant_detials', env('DB_DATABASE_SECOND') . '.applicant_detials.applicant_id', 'applicant_accounts.id')
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->where(
                function ($_subQuery) {
                    $_subQuery
                        ->select(DB::raw('count("is_approved")'))
                        ->from(env('DB_DATABASE_SECOND') . '.applicant_documents')
                        ->whereColumn(env('DB_DATABASE_SECOND') . '.applicant_documents.applicant_id', 'applicant_accounts.id')
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_removed', false)
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_approved', true);
                },
                '>=',
                $_documents,
            )
            ->groupBy('applicant_accounts.id')->orderBy(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'asc');
        return $_query;
    }
    /* Entrance Examination Payment */
    public function applicant_payment_verification()
    {
        $_level = $this->id == 3 ? 11 : 4;
        $_documents = Documents::where('department_id', 2)
            ->where('year_level', $_level)
            ->where('is_removed', false)
            ->count();
        $_documents = intval($_documents);
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*')
            ->join(env('DB_DATABASE_SECOND') . '.applicant_payments', env('DB_DATABASE_SECOND') . '.applicant_payments.applicant_id', 'applicant_accounts.id')
            //->whereNull(env('DB_DATABASE_SECOND').'.applicant_payments.is_approved')

            ->where(env('DB_DATABASE_SECOND') . '.applicant_payments.is_removed', false)
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id')
            ->where(
                function ($_subQuery) {
                    $_subQuery
                        ->select(DB::raw('count("is_approved")'))
                        ->from(env('DB_DATABASE_SECOND') . '.applicant_documents')
                        ->whereColumn(env('DB_DATABASE_SECOND') . '.applicant_documents.applicant_id', 'applicant_accounts.id')
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_removed', false)
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_approved', true);
                },
                '>=',
                $_documents,
            )
            ->where(env('DB_DATABASE_SECOND') . '.applicant_payments.is_approved', 0);
        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_query->join('applicant_detials as ad', 'ad.applicant_id', 'applicant_accounts.id');
            $_query = $_count > 0 ? $_query->where('ad.last_name', 'like', '%' . trim($_student[0]) . '%') : $_query->where('ad.last_name', 'like', '%' . trim($_student[0]) . '%')->where('ad.first_name', 'like', '%' . trim($_student[1]) . '%');
            //return request()->input('_student');
        }
        return $_query;
    }
    # Virtual Orientation
    public function applicant_virtual_orientation()
    {
        $_level = $this->id == 3 ? 11 : 4;
        $_item = $this->id == 3 ? 100 : 200;
        $_point = $this->id == 3 ? 5 : 50;
        $_documents = Documents::where('department_id', 2)
            ->where('year_level', $_level)
            ->where('is_removed', false)
            ->count();
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*') # Applicant Account
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id')
            ->join(env('DB_DATABASE_SECOND') . '.applicant_detials', env('DB_DATABASE_SECOND') . '.applicant_detials.applicant_id', 'applicant_accounts.id') # Join Applicant Details
            ->where(
                function ($_subQuery) {
                    # Applicant Documents
                    $_subQuery
                        ->select(DB::raw('count("is_approved")'))
                        ->from(env('DB_DATABASE_SECOND') . '.applicant_documents')
                        ->whereColumn(env('DB_DATABASE_SECOND') . '.applicant_documents.applicant_id', 'applicant_accounts.id')
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_removed', false)
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_approved', true);
                },
                '>=',
                $_documents,
            )
            ->leftJoin(env('DB_DATABASE_SECOND') . '.applicant_payments', env('DB_DATABASE_SECOND') . '.applicant_payments.applicant_id', 'applicant_accounts.id') # Applicant Payments
            ->where(env('DB_DATABASE_SECOND') . '.applicant_payments.is_removed', false)
            ->where(env('DB_DATABASE_SECOND') . '.applicant_payments.is_approved', true)
            ->join(env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations', env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.applicant_id', 'applicant_accounts.id') # Entrance Examination
            ->where(env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.is_removed', false)
            ->where(env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.is_finish', true)
            ->where(
                DB::raw(
                    '(SELECT ((COUNT(eqc.is_answer)/' .
                        $_item .
                        ")*100) as exam_result
                FROM " . env('DB_DATABASE_SECOND') . ".applicant_examination_answers as aea
                inner join " . env('DB_DATABASE') . ".examination_question_choices as eqc
                on eqc.id = aea.choices_id
                where eqc.is_answer = true and aea.examination_id = applicant_entrance_examinations.id)",
                ),
                '>=',
                $_point,
            ) # Get the Score;
            ->join(env('DB_DATABASE_SECOND') . '.applicant_briefings', env('DB_DATABASE_SECOND') . '.applicant_briefings.applicant_id', 'applicant_accounts.id')
            ->where(env('DB_DATABASE_SECOND') . '.applicant_briefings.is_removed', false)
            ->where(env('DB_DATABASE_SECOND') . '.applicant_briefings.is_completed', false)
            ->orderBy(env('DB_DATABASE_SECOND') . '.applicant_briefings.updated_at', 'desc');
        //Searching Tool
        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_count > 0 ? $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%') : $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%')->where(env('DB_DATABASE_SECOND') . '.applicant_detials.first_name', 'like', '%' . trim($_student[1]) . '%');
        }
        return $_query;
    }
    # Medical Appointment
    public function applicant_medical_appointment()
    {
        $_level = $this->id == 3 ? 11 : 4;
        $_item = $this->id == 3 ? 100 : 200;
        $_point = $this->id == 3 ? 5 : 50;
        $_documents = Documents::where('department_id', 2)
            ->where('year_level', $_level)
            ->where('is_removed', false)
            ->count();
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*') # Applicant Account
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id')
            ->join(env('DB_DATABASE_SECOND') . '.applicant_detials', env('DB_DATABASE_SECOND') . '.applicant_detials.applicant_id', 'applicant_accounts.id') # Join Applicant Details
            ->where(
                function ($_subQuery) {
                    # Applicant Documents
                    $_subQuery
                        ->select(DB::raw('count("is_approved")'))
                        ->from(env('DB_DATABASE_SECOND') . '.applicant_documents')
                        ->whereColumn(env('DB_DATABASE_SECOND') . '.applicant_documents.applicant_id', 'applicant_accounts.id')
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_removed', false)
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_approved', true);
                },
                '>=',
                $_documents,
            )
            ->leftJoin(env('DB_DATABASE_SECOND') . '.applicant_payments', env('DB_DATABASE_SECOND') . '.applicant_payments.applicant_id', 'applicant_accounts.id') # Applicant Payments
            ->where(env('DB_DATABASE_SECOND') . '.applicant_payments.is_removed', false)
            ->where(env('DB_DATABASE_SECOND') . '.applicant_payments.is_approved', true)
            ->join(env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations', env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.applicant_id', 'applicant_accounts.id') # Entrance Examination
            ->where(env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.is_removed', false)
            ->where(env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.is_finish', true)
            ->where(
                DB::raw(
                    '(SELECT ((COUNT(eqc.is_answer)/' .
                        $_item .
                        ")*100) as exam_result
                FROM " . env('DB_DATABASE_SECOND') . ".applicant_examination_answers as aea
                inner join " . env('DB_DATABASE') . ".examination_question_choices as eqc
                on eqc.id = aea.choices_id
                where eqc.is_answer = true and aea.examination_id = applicant_entrance_examinations.id)",
                ),
                '>=',
                $_point,
            ) # Get the Score;
            ->join(env('DB_DATABASE_SECOND') . '.applicant_briefings', env('DB_DATABASE_SECOND') . '.applicant_briefings.applicant_id', 'applicant_accounts.id') # Applicant Virtual Orientation
            ->where(env('DB_DATABASE_SECOND') . '.applicant_briefings.is_removed', false)
            ->where(env('DB_DATABASE_SECOND') . '.applicant_briefings.is_completed', true)
            ->leftJoin(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments', env('DB_DATABASE_SECOND') . '.applicant_medical_appointments.applicant_id', 'applicant_accounts.id')
            ->whereNull(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments.applicant_id');
        //Searching Tool
        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_count > 0 ? $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%') : $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%')->where(env('DB_DATABASE_SECOND') . '.applicant_detials.first_name', 'like', '%' . trim($_student[1]) . '%');
        }
        return $_query;
    }
    # Medical Schedule Approved
    public function applicant_medical_scheduled()
    {
        $_level = $this->id == 3 ? 11 : 4;
        $_item = $this->id == 3 ? 100 : 200;
        $_documents = Documents::where('department_id', 2)
            ->where('year_level', $_level)
            ->where('is_removed', false)
            ->count();
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*') # Applicant Account
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id')
            ->join(env('DB_DATABASE_SECOND') . '.applicant_detials', env('DB_DATABASE_SECOND') . '.applicant_detials.applicant_id', 'applicant_accounts.id') # Join Applicant Details
            ->where(
                function ($_subQuery) {
                    # Applicant Documents
                    $_subQuery
                        ->select(DB::raw('count("is_approved")'))
                        ->from(env('DB_DATABASE_SECOND') . '.applicant_documents')
                        ->whereColumn(env('DB_DATABASE_SECOND') . '.applicant_documents.applicant_id', 'applicant_accounts.id')
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_removed', false)
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_approved', true);
                },
                '>=',
                $_documents,
            )
            ->leftJoin(env('DB_DATABASE_SECOND') . '.applicant_payments', env('DB_DATABASE_SECOND') . '.applicant_payments.applicant_id', 'applicant_accounts.id') # Applicant Payments
            ->where(env('DB_DATABASE_SECOND') . '.applicant_payments.is_removed', false)
            ->where(env('DB_DATABASE_SECOND') . '.applicant_payments.is_approved', true)
            ->join(env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations', env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.applicant_id', 'applicant_accounts.id') # Entrance Examination
            ->where(env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.is_removed', false)
            ->where(env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.is_finish', true)
            ->where(
                DB::raw(
                    '(SELECT ((COUNT(eqc.is_answer)/' .
                        $_item .
                        ")*100) as exam_result
                FROM " . env('DB_DATABASE_SECOND') . ".applicant_examination_answers as aea
                inner join " . env('DB_DATABASE') . ".examination_question_choices as eqc
                on eqc.id = aea.choices_id
                where eqc.is_answer = true and aea.examination_id = applicant_entrance_examinations.id)",
                ),
                '>=',
                '50',
            ) # Get the Score;
            ->join(env('DB_DATABASE_SECOND') . '.applicant_briefings', env('DB_DATABASE_SECOND') . '.applicant_briefings.applicant_id', 'applicant_accounts.id') # Applicant Virtual Orientation
            ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments', env('DB_DATABASE_SECOND') . '.applicant_medical_appointments.applicant_id', 'applicant_accounts.id') # Applicant Medical Appointment
            ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments.is_removed', false)
            ->leftJoin(env('DB_DATABASE_SECOND') . '.applicant_medical_results', env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id', 'applicant_accounts.id')
            ->whereNull(env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id');

        //Searching Tool
        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_count > 0 ? $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%') : $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%')->where(env('DB_DATABASE_SECOND') . '.applicant_detials.first_name', 'like', '%' . trim($_student[1]) . '%');
        }
        return $_query;
    }
    # Medical Results
    public function applicant_medical_results()
    {
        $_level = $this->id == 3 ? 11 : 4;
        $_item = $this->id == 3 ? 100 : 200;
        $_documents = Documents::where('department_id', 2)
            ->where('year_level', $_level)
            ->where('is_removed', false)
            ->count();
        $_query = $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*') # Applicant Account
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->groupBy('applicant_accounts.id')
            ->join(env('DB_DATABASE_SECOND') . '.applicant_detials', env('DB_DATABASE_SECOND') . '.applicant_detials.applicant_id', 'applicant_accounts.id') # Join Applicant Details
            ->where(
                function ($_subQuery) {
                    # Applicant Documents
                    $_subQuery
                        ->select(DB::raw('count("is_approved")'))
                        ->from(env('DB_DATABASE_SECOND') . '.applicant_documents')
                        ->whereColumn(env('DB_DATABASE_SECOND') . '.applicant_documents.applicant_id', 'applicant_accounts.id')
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_removed', false)
                        ->where(env('DB_DATABASE_SECOND') . '.applicant_documents.is_approved', true);
                },
                '>=',
                $_documents,
            )
            ->leftJoin(env('DB_DATABASE_SECOND') . '.applicant_payments', env('DB_DATABASE_SECOND') . '.applicant_payments.applicant_id', 'applicant_accounts.id') # Applicant Payments
            ->where(env('DB_DATABASE_SECOND') . '.applicant_payments.is_removed', false)
            ->where(env('DB_DATABASE_SECOND') . '.applicant_payments.is_approved', true)
            ->join(env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations', env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.applicant_id', 'applicant_accounts.id') # Entrance Examination
            ->where(env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.is_removed', false)
            ->where(env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations.is_finish', true)
            ->where(
                DB::raw(
                    '(SELECT ((COUNT(eqc.is_answer)/' .
                        $_item .
                        ")*100) as exam_result
                FROM " . env('DB_DATABASE_SECOND') . ".applicant_examination_answers as aea
                inner join " . env('DB_DATABASE') . ".examination_question_choices as eqc
                on eqc.id = aea.choices_id
                where eqc.is_answer = true and aea.examination_id = applicant_entrance_examinations.id)",
                ),
                '>=',
                '50',
            ) # Get the Score;
            ->join(env('DB_DATABASE_SECOND') . '.applicant_briefings', env('DB_DATABASE_SECOND') . '.applicant_briefings.applicant_id', 'applicant_accounts.id') # Applicant Virtual Orientation
            ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments', env('DB_DATABASE_SECOND') . '.applicant_medical_appointments.applicant_id', 'applicant_accounts.id') # Applicant Medical Appointment
            ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments.is_removed', false)
            ->leftJoin(env('DB_DATABASE_SECOND') . '.applicant_medical_results', env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id', 'applicant_accounts.id')
            ->whereNotNull(env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id')
            ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_removed', false);
        //Searching Tool
        if (request()->input('_student')) {
            $_student = explode(',', request()->input('_student'));
            $_count = count($_student);
            $_query = $_count > 0 ? $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%') : $_query->where(env('DB_DATABASE_SECOND') . '.applicant_detials.last_name', 'like', '%' . trim($_student[0]) . '%')->where(env('DB_DATABASE_SECOND') . '.applicant_detials.first_name', 'like', '%' . trim($_student[1]) . '%');
        }
        return $_query;
    }
    public function applicant_qualified_to_enrolled()
    {
        return $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*')
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->groupBy('applicant_accounts.id')
            ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_results as amr', 'amr.applicant_id', 'applicant_accounts.id')
            ->where('amr.is_removed', false)
            ->where('amr.is_fit', true);
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
            ->where('pa.payment_mode', $_data)
            ->get();
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
            ->orderBy('pt.created_at', 'DESC')
            ->get();
    }
    // Medical
    public function waiting_scheduled()
    {
        return $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*')
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where(env('DB_DATABASE_SECOND') . '.applicant_briefings.is_removed', false)
            ->groupBy('applicant_accounts.id')
            ->join(env('DB_DATABASE_SECOND') . '.applicant_briefings', env('DB_DATABASE_SECOND') . '.applicant_briefings.applicant_id', 'applicant_accounts.id')
            ->where('applicant_accounts.is_removed', false)
            ->leftJoin(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', 'applicant_accounts.id')
            ->whereNull('ama.applicant_id');
    }
    public function scheduled()
    {
        return $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*')
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->join(env('DB_DATABASE_SECOND') . '.applicant_briefings as ab', 'ab.applicant_id', 'applicant_accounts.id')
            ->where('ab.is_removed', false)
            ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', 'ab.applicant_id')
            ->where('ama.is_removed', false)
            ->where('is_approved', false)
            ->groupBy('applicant_accounts.id');
    }
    public function waiting_result()
    {
        return $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*')
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->join(env('DB_DATABASE_SECOND') . '.applicant_briefings as ab', 'ab.applicant_id', 'applicant_accounts.id')
            ->where('ab.is_removed', false)
            ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', 'ab.applicant_id')
            ->where('ama.is_removed', false)
            ->where('is_approved', true)
            ->leftJoin(env('DB_DATABASE_SECOND') . '.applicant_medical_results', env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id', 'ama.applicant_id')
            ->whereNull(env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id')
            ->groupBy('applicant_accounts.id');
    }
    public function medical_result_passed()
    {
        return $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*')
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->join(env('DB_DATABASE_SECOND') . '.applicant_briefings as ab', 'ab.applicant_id', 'applicant_accounts.id')
            ->where('ab.is_removed', false)
            ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', 'ab.applicant_id')
            ->where('ama.is_removed', false)
            ->where('is_approved', true)
            ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_results', env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id', 'ab.applicant_id')
            ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_fit', true)
            ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_removed', false)
            ->groupBy('applicant_accounts.id');
    }
    public function medical_result_pending()
    {
        return $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*')
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->join(env('DB_DATABASE_SECOND') . '.applicant_briefings as ab', 'ab.applicant_id', 'applicant_accounts.id')
            ->where('ab.is_removed', false)
            ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', 'ab.applicant_id')
            ->where('ama.is_removed', false)
            ->where('is_approved', true)
            ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_results', env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id', 'ab.applicant_id')
            ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_pending', false)
            ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_removed', false)
            ->groupBy('applicant_accounts.id');
    }
    public function medical_result_failed()
    {
        return $this->hasMany(ApplicantAccount::class, 'course_id')
            ->select('applicant_accounts.*')
            ->where('applicant_accounts.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('applicant_accounts.is_removed', false)
            ->join(env('DB_DATABASE_SECOND') . '.applicant_briefings as ab', 'ab.applicant_id', 'applicant_accounts.id')
            ->where('ab.is_removed', false)
            ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_appointments as ama', 'ama.applicant_id', 'ab.applicant_id')
            ->where('ama.is_removed', false)
            ->where('is_approved', true)
            ->join(env('DB_DATABASE_SECOND') . '.applicant_medical_results', env('DB_DATABASE_SECOND') . '.applicant_medical_results.applicant_id', 'ab.applicant_id')
            ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_fit', 2)
            ->where(env('DB_DATABASE_SECOND') . '.applicant_medical_results.is_removed', false)
            ->groupBy('applicant_accounts.id');
    }
    public function student_medical_scheduled()
    {
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->select('enrollment_assessments.*')
            ->join('student_medical_appointments', 'student_medical_appointments.student_id', 'enrollment_assessments.student_id')
            ->whereNull('student_medical_appointments.is_approved')
            ->where('student_medical_appointments.is_removed', false)
            ->orderBy('student_medical_appointments.appointment_date', 'asc')
            ->groupBy('student_medical_appointments.student_id');
    }
    public function student_medical_waiting_for_result()
    {
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->select('enrollment_assessments.*')
            ->join('student_medical_appointments', 'student_medical_appointments.student_id', 'enrollment_assessments.student_id')
            ->where('student_medical_appointments.is_approved', true)
            ->where('student_medical_appointments.is_removed', false)
            ->orderBy('student_medical_appointments.appointment_date', 'asc')
            ->groupBy('student_medical_appointments.student_id')
            ->leftJoin('student_medical_results', 'student_medical_results.student_id', 'enrollment_assessments.student_id')
            ->whereNull('student_medical_results.student_id');
    }

    public function student_medical_passed()
    {
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->select('enrollment_assessments.*')
            ->join('student_medical_appointments', 'student_medical_appointments.student_id', 'enrollment_assessments.student_id')
            ->where('student_medical_appointments.is_approved', true)
            ->orderBy('student_medical_appointments.appointment_date', 'asc')
            ->groupBy('student_medical_appointments.student_id')
            ->join('student_medical_results', 'student_medical_results.student_id', 'enrollment_assessments.student_id')
            ->where('student_medical_results.is_removed', false)
            ->where('is_fit', true);
    }
    public function student_medical_pending()
    {
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->select('enrollment_assessments.*')
            ->join('student_medical_appointments', 'student_medical_appointments.student_id', 'enrollment_assessments.student_id')
            ->where('student_medical_appointments.is_approved', true)
            ->orderBy('student_medical_appointments.appointment_date', 'asc')
            ->groupBy('student_medical_appointments.student_id')
            ->join('student_medical_results', 'student_medical_results.student_id', 'enrollment_assessments.student_id')
            ->where('student_medical_results.is_removed', false)
            ->where('is_pending', 0);
    }
    public function student_medical_failed()
    {
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->select('enrollment_assessments.*')
            ->join('student_medical_appointments', 'student_medical_appointments.student_id', 'enrollment_assessments.student_id')
            ->where('student_medical_appointments.is_approved', true)
            ->orderBy('student_medical_appointments.appointment_date', 'asc')
            ->groupBy('student_medical_appointments.student_id')
            ->join('student_medical_results', 'student_medical_results.student_id', 'enrollment_assessments.student_id')
            ->where('student_medical_results.is_removed', false)
            ->where('is_fit', false);
    }
    # Student Medical List with Year
    public function student_medical_scheduled_year($data)
    {
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->join('student_medical_appointments', 'student_medical_appointments.student_id', 'enrollment_assessments.student_id')
            ->where('enrollment_assessments.year_level', $data)
            // ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->whereNull('student_medical_appointments.is_approved')
            ->where('student_medical_appointments.is_removed', false)
            ->orderBy('student_medical_appointments.appointment_date', 'asc')
            ->groupBy('student_medical_appointments.student_id');
    }
    public function student_medical_waiting_for_result_year($data)
    {
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->select('enrollment_assessments.*')
            ->where('enrollment_assessments.year_level', $data)
            //->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->join('student_medical_appointments', 'student_medical_appointments.student_id', 'enrollment_assessments.student_id')
            ->where('student_medical_appointments.is_approved', true)
            ->where('student_medical_appointments.is_removed', false)
            ->orderBy('student_medical_appointments.appointment_date', 'asc')
            ->groupBy('student_medical_appointments.student_id')
            ->leftJoin('student_medical_results', 'student_medical_results.student_id', 'enrollment_assessments.student_id')
            ->whereNull('student_medical_results.student_id');
    }
    public function student_medical_passed_year($_data)
    {
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->select('enrollment_assessments.*')
            ->where('enrollment_assessments.year_level', $_data)
            ->join('student_medical_appointments', 'student_medical_appointments.student_id', 'enrollment_assessments.student_id')
            ->where('student_medical_appointments.is_approved', true)
            ->orderBy('student_medical_appointments.appointment_date', 'asc')
            ->groupBy('student_medical_appointments.student_id')
            ->join('student_medical_results', 'student_medical_results.student_id', 'enrollment_assessments.student_id')
            ->where('student_medical_results.is_removed', false)
            ->where('is_fit', true);
    }
    /* UMAK STUDENT */
    public function enrollment_assessment_umak_student()
    {
        $_academic = AcademicYear::where('id', '<', Auth::user()->staff->current_academic()->id)
            ->orderBy('id', 'desc')
            ->first();
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->join('enrollment_applications as ea', 'ea.student_id', 'enrollment_assessments.student_id')
            ->whereNull('ea.is_approved')
            ->where('enrollment_assessments.is_removed', false)
            ->where('enrollment_assessments.curriculum_id', 8)
            #->where('enrollment_assessments.year_level', $data)
            ->where('enrollment_assessments.academic_id', $_academic->id);
    }
    public function payment_assessment_umak_student()
    {
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('enrollment_assessments.curriculum_id', 8)
            //->where('enrollment_assessments.year_level', $data)
            ->leftJoin('payment_assessments as pa', 'pa.enrollment_id', 'enrollment_assessments.id')
            ->leftJoin('payment_transactions as pt', 'pa.id', 'pt.assessment_id')
            ->whereNull('pa.enrollment_id');
    }
    public function payment_transaction_umak_student()
    {
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->join('payment_assessments as pa', 'pa.enrollment_id', 'enrollment_assessments.id')
            ->where('enrollment_assessments.curriculum_id', 8)
            ->leftJoin('payment_transactions as pt', 'pa.id', 'pt.assessment_id')
            ->whereNull('pt.assessment_id');
    }
    public function enrolled_list_umak_student()
    {
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->select('enrollment_assessments.*')
            ->join('payment_assessments', 'enrollment_assessments.id', 'payment_assessments.enrollment_id')
            ->join('payment_transactions', 'payment_assessments.id', 'payment_transactions.assessment_id')
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            //->where('enrollment_assessments.year_level', $data)
            ->where('enrollment_assessments.curriculum_id', 8)
            ->where('enrollment_assessments.is_removed', false)
            ->where('payment_transactions.is_removed', false)
            ->groupBy('enrollment_assessments.id')
            ->orderBy('payment_transactions.created_at', 'DESC');
    }

    public function student_onboarding($_level)
    {
        /*  $_first_day =  new DateTime();
        $_last_day = new DateTime();
        $_first_day->modify('Sunday');
        $_last_day->modify('Next Saturday');
        $_week_dates = array(
            $_first_day->format('Y-m-d') . '%',  $_last_day->format('Y-m-d') . '%'
        ); */
        $now = now();
        $day = new DateTime($now);
        $week = date('l', strtotime($now));
        $modify = $week == 'Sunday' ? 'Sunday' : 'Last Sunday';
        $_week_start = $day->modify($modify);
        $_week_start = $day->format('Y-m-d');
        $_week_end = $day->modify('Next Saturday');
        $_week_end = $day->format('Y-m-d');
        $_week_dates = [$_week_start . '%', $_week_end . '%'];
        return $this->hasMany(StudentOnboardingAttendance::class, 'course_id')
            ->where('student_onboarding_attendances.academic_id', Auth::user()->staff->current_academic()->id)
            ->whereBetween('student_onboarding_attendances.created_at', $_week_dates)
            ->join('enrollment_assessments', 'enrollment_assessments.student_id', 'student_onboarding_attendances.student_id')
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('enrollment_assessments.is_removed', false)
            ->where('enrollment_assessments.year_level', $_level);
    }
    function enrollment_cancellation($data)
    {
        $academic =  Auth::user()->staff->current_academic()->id;
        if (Cache::has('academic')) {
            $academic = base64_decode(Cache::get('academic'));
        }
        return   $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->join('student_cancellations', 'student_cancellations.enrollment_id', 'enrollment_assessments.id')
            ->where('enrollment_assessments.academic_id', $academic)
            ->groupBy('enrollment_assessments.id')
            ->where('student_cancellations.type_of_cancellations', $data)
            ->orderBy('student_cancellations.created_at', 'DESC');
    }
    function course_semestral_fees()
    {
        return $this->hasMany(CourseSemestralFees::class, 'course_id')
            ->where('academic_id', Auth::user()->staff->current_academic()->id)
            ->where('is_removed', false);
    }
    function student_medical_result($level)
    {
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('enrollment_assessments.year_level', $level)
            ->where('enrollment_assessments.is_removed', false)
            ->join('student_medical_results', 'student_medical_results.enrollment_id', 'enrollment_assessments.id')
            ->where('student_medical_results.is_removed', false)
            ->where('student_medical_results.is_pending', 0);
    }
}
