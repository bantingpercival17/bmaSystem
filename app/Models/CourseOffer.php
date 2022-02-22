<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CourseOffer extends Model
{
    use HasFactory;
    protected $fillable = ['course_name', 'course_code', 'school_level', 'is_removed'];
    public function course_subject($_data)
    {
        return $this->hasMany(CurriculumSubject::class, 'course_id')
            ->select('curriculum_subjects.id', 'subjects.subject_code', 'subjects.subject_name')
            ->join('subjects', 'subjects.id', 'curriculum_subjects.subject_id')
            ->where('curriculum_subjects.curriculum_id', $_data[0])
            ->where('curriculum_subjects.year_level', $_data[1])
            ->where('curriculum_subjects.semester', $_data[2])
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
            ->where('enrollment_assessments.is_removed', false)
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->groupBy('pt.assessment_id')
            ->orderBy('pa.created_at', 'DESC');
    }
    public function enrolled_list($_data)
    {
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->join('payment_assessments as pa', 'pa.enrollment_id', 'enrollment_assessments.id')
            ->join('payment_transactions as pt', 'pt.assessment_id', 'pa.id')
            ->where('pt.remarks', 'Upon Enrollment')
            ->groupBy('pt.assessment_id')
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->orderBy('enrollment_assessments.created_at', 'DESC')
            ->where('enrollment_assessments.year_level', $_data)
            ->where('enrollment_assessments.is_removed', false);
    }
    public function previous_enrolled()
    {
        $_academic = AcademicYear::where('id', '<', Auth::user()->staff->current_academic()->id)->orderBy('id', 'desc')->first();
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')->join('payment_assessments as pa', 'pa.enrollment_id', 'enrollment_assessments.id')
            ->join('payment_transactions as pt', 'pt.assessment_id', 'pa.id')
            ->where('pt.remarks', 'Upon Enrollment')
            ->where('enrollment_assessments.is_removed', false)
            ->where('enrollment_assessments.academic_id', $_academic->id)
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
    public function enrollment_application()
    {
        $_academic = AcademicYear::where('id', '<', Auth::user()->staff->current_academic()->id)->orderBy('id', 'desc')->first();

        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->join('enrollment_applications as ea', 'ea.student_id', 'enrollment_assessments.student_id')
            ->whereNull('ea.is_approved')
            ->where('enrollment_assessments.is_removed', false)
            ->where('enrollment_assessments.academic_id', $_academic->id);
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
            ->first();
    }
}
