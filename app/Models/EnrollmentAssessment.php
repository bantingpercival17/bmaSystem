<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EnrollmentAssessment extends Model
{
    use HasFactory;
    protected $fillable = [
        "student_id",
        "academic_id",
        "course_id",
        "year_level",
        "curriculum_id",
        "bridging_program",
        "enrollment_category",
        "staff_id",
        "is_removed"
    ];
    public function course()
    {
        return $this->belongsTo(CourseOffer::class, 'course_id');
    }
    public function student()
    {
        return $this->belongsTo(StudentDetails::class, 'student_id');
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class, 'curriculum_id');
    }
    public function payment_assessments()
    {
        return $this->hasOne(PaymentAssessment::class, 'enrollment_id');
    }
    public function academic()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_id');
    }
    public function year_and_section($_data)
    {
        $_year_level = $_data->year_level == '11' ? 'Grade 11' : '-';
        $_year_level = $_data->year_level == '12' ? 'Grade 12' : $_year_level;
        $_year_level = $_data->year_level == '4' ? '4th Class' : $_year_level;
        $_year_level = $_data->year_level == '3' ? '3rd Class' : $_year_level;
        $_year_level = $_data->year_level == '2' ? '2nd Class' : $_year_level;
        $_year_level = $_data->year_level == '1' ? '1st Class' : $_year_level;
        $_student_section = $_data->student->section($_data->academic_id)->first();
        $_section = $_student_section ? $_student_section->section->section_name : '-';
        return $_year_level . " | " . $_section;
    }
    public function course_subjects($_assessment)
    {
        return CurriculumSubject::where('course_id', $_assessment->course_id)
            ->where('curriculum_id', $_assessment->curriculum_id)
            ->where('year_level', $_assessment->year_level)
            ->where('semester', $_assessment->academic->semester)
            ->where('is_removed', false)
            ->get();
    }
    public function course_semestral_fees($_data)
    {
        return CourseSemestralFees::where([
            'course_id' => $_data->course_id,
            'curriculum_id' => $_data->curriculum_id,
            'academic_id' => $_data->academic_id,
            'year_level' => $_data->year_level,
            'is_removed' => false
        ])->first();
    }
    public function payment_transactions()
    {
        return $this->hasOne(PaymentTransaction::class, 'assessment_id')->where('remarks', 'Upon Enrollment');
    }
    public function enrollment_payment_assessment()
    {
        return $this->hasOne(PaymentAssessment::class, 'enrollment_id')->where('is_removed', false)/* ->with('payment_assessment_paid') */;
    }
    public function additional_payment()
    {
        return $this->hasMany(PaymentAdditionalTransaction::class, 'enrollment_id')->where('is_removed', false);
    }
    public function bridging_payment()
    {
        return $this->hasOne(PaymentAdditionalTransaction::class, 'enrollment_id')->where('is_removed', false);
    }
    public function find_section()
    {
        $level = $this->course_id == 4 ? 'Grade ' . $this->year_level : $this->year_level . '/C';
        return Section::where('academic_id', $this->academic_id)->where('course_id', $this->course_id)->where('year_level', $level)->where('curriculum_id', $this->curriculum_id)->where(function ($_sub_query) {
            $_sub_query->select(DB::raw('count(*)'))->from('student_sections')
                ->whereColumn('student_sections.section_id', 'sections.id')
                ->where('student_sections.is_removed', false);
        }, '<=', 39)->first();
    }
    function student_section()
    {
        return $this->hasOne(StudentSection::class, 'enrollment_id')->where('is_removed', false);
    }
    public function color_course()
    {
        $_course_color = $this->course_id == 1 ? 'bg-info' : '';
        $_course_color = $this->course_id == 2 ? 'bg-primary' : $_course_color;
        $_course_color = $this->course_id == 3 ? 'bg-warning text-white' : $_course_color;
        return $_course_color;
    }
    public function course_level_tuition_fee()
    {
        return CourseSemestralFees::where([
            'course_id' => $this->course_id,
            'curriculum_id' => $this->curriculum_id,
            'academic_id' => $this->academic_id,
            'year_level' => $this->year_level,
            'is_removed' => false
        ])->first();
    }
    public function enrollment_cancellation()
    {
        return $this->hasOne(StudentCancellation::class, 'enrollment_id');
    }

    function medical_result()
    {
        return $this->hasOne(StudentMedicalResult::class, 'enrollment_id')->where('is_removed', false);
    }
    function over_payment()
    {
        $previous =  AcademicYear::where('id', '<', $this->academic_id)
            ->orderBy('id', 'desc')
            ->first();
        $enrollment = EnrollmentAssessment::where('academic_id', $previous->id)->where('student_id', $this->student_id)->where('is_removed', false)->first();
        $paymentAssessment = $enrollment->payment_assessments;
        return ($paymentAssessment->course_semestral_fee_id ? $paymentAssessment->course_semestral_fee->total_payments($paymentAssessment) : $paymentAssessment->total_payment) - $paymentAssessment->total_paid_amount->sum('payment_amount');
    }
}
