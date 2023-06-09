<?php

namespace App\Report;

use App\Models\AcademicYear;
use App\Models\CourseOffer;
use App\Models\Curriculum;
use App\Models\EnrollmentAssessment;
use App\Models\Section;
use App\Models\StudentSection;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;

class StudentListReport
{
    public function __construct()
    {

        $this->legal = [0, 0, 612.00, 1008.00];
        $this->short = [0, 0, 612.00, 792.00];
    }

    public function build()
    {
        $pdf = PDF::loadView("widgets.report.grade.grading_sheet_report", compact('_students', '_subject'));
        $file_name = strtoupper($this->subject->curriculum_subject->subject->subject_code);
        return $pdf->setPaper($this->legal, 'landscape')->stream($file_name . '.pdf');
    }
    public function student_section_list($_data)
    {
        //return $_data;
        $_sections = Section::where($_data)->get();
        $_academic = AcademicYear::find($_data['academic_id']);
        $_course = CourseOffer::find($_data['course_id']);
        $_year = Auth::user()->staff->convert_year_level($_data['year_level']);
        $pdf = PDF::loadView("widgets.report.student.student_section_list", compact('_sections', '_academic'));
        $file_name = $_course->course_code . "_" . strtoupper(Auth::user()->staff->convert_year_level(str_replace('/C', '', $_data['year_level'])))  . "_" . Auth::user()->staff->current_academic()->school_year . '_' . strtoupper(str_replace(' ', '_', Auth::user()->staff->current_academic()->semester));
        //$file_name = strtoupper($_course->course_code) . " : " . $_data['year_level'] . "/C - FORM : STUDENT SECTION LIST " . $_academic->school_year . " - " . $_academic->semester;

        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
    public function summary_grade($course, $level)
    {
        //return $_curriculum = $_course->enrollment_list_by_year_level($_request->_year_level)->get();
        $curriculum = Curriculum::all();
        $_year_level = $level == '4' ? 'First Year' : '';
        $_year_level = $level == '3' ? 'Second Year' : $_year_level;
        $_year_level = $level == '2' ? 'Third Year' : $_year_level;
        $_year_level = $level == '1' ? 'Fourth Year' : $_year_level;
        $pdf = PDF::loadView("widgets.report.grade-v2.semestral_summary_grade", compact('course', 'curriculum', 'level'));
        $file_name = strtoupper($course->course_name . '-' . $_year_level . '_' . Auth::user()->staff->current_academic()->school_year . "-" . Auth::user()->staff->current_academic()->semester);
        return $pdf->setPaper($this->legal, 'landscape')->stream($file_name . '.pdf');
    }
    function semestral_enrollees($courses)
    {
        
        $pdf = PDF::loadView("widgets.report.enrollment.semestral-enrollee", compact('courses'));
        $file_name = 'OFFICAL LIST OF ENROLLED MIDSHIPMEN-' .strtoupper( str_replace(' ','-',Auth::user()->staff->current_academic()->semester . '-' . Auth::user()->staff->current_academic()->school_year));
        return $pdf->setPaper($this->legal, 'portrait')->download($file_name . '.pdf');
    }
}
