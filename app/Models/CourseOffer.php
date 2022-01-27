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
            ->where('sections.year_level', $_data[1])
            ->where('is_removed', false)
            /* ->get() */;
    }
    public function enrollment_list()
    {
        $_academic = request()->input('_academic') ? AcademicYear::find(base64_decode(request()->input('_academic'))) : AcademicYear::where('is_active', 1)->first();
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')->join('payment_assessments as pa', 'pa.enrollment_id', 'enrollment_assessments.id')
            /* ->leftJoin('payment_transactions as pt', 'pt.assessment_id', 'pa.id') */
            ->where('enrollment_assessments.is_removed', false)->where('enrollment_assessments.academic_id', $_academic->id)
            ->orderBy('pa.created_at', 'DESC');
    }
    public function enrolled_list($_data)
    {
        return $this->hasMany(EnrollmentAssessment::class, 'course_id')
            ->join('payment_assessments as pa', 'pa.enrollment_id', 'enrollment_assessments.id')
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->orderBy('enrollment_assessments.created_at', 'DESC')
            ->where('enrollment_assessments.year_level', $_data)
            ->where('enrollment_assessments.is_removed', false);
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
