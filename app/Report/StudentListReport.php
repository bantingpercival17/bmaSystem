<?php

namespace App\Report;

use App\Models\AcademicYear;
use App\Models\CourseOffer;
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
        /* foreach ($_sections as $key => $_section) {
            $pdf = PDF::loadView("widgets.report.student.student_section_list", compact('_sections'));
            $file_name = strtoupper($_course->course_code) . "-" . $_data['year_level'] . "-STUDENT-SECTION-LIST";
            return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
        } */
        $pdf = PDF::loadView("widgets.report.student.student_section_list", compact('_sections', '_academic'));
        $file_name = strtoupper($_course->course_code) . " : " . $_data['year_level'] . "/C - FORM : STUDENT SECTION LIST " . $_academic->school_year . " - " . $_academic->semester;
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
    public function summary_grade($_students, $_request)
    {
        $_academic = AcademicYear::find(base64_decode($_request->_academic));
        $_course = CourseOffer::find(base64_decode($_request->_course));
        $_curriculum = EnrollmentAssessment::select('enrollment_assessments.curriculum_id', 'enrollment_assessments.course_id', 'enrollment_assessments.academic_id', 'enrollment_assessments.year_level')
            ->groupBy('enrollment_assessments.curriculum_id')
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('enrollment_assessments.course_id', base64_decode($_request->_course))
            ->where('enrollment_assessments.year_level', $_request->_year_level)
            ->where('enrollment_assessments.is_removed', false)
            ->get();
        $_level =  $_request->_year_level;
        $pdf = PDF::loadView("widgets.report.grade-v2.summary_grade", compact('_course', '_curriculum', '_level'));
        $_year_level = $_level == '4' ? 'First Year' : '';
        $_year_level = $_level == '3' ? 'Second Year' : $_year_level;
        $_year_level = $_level == '2' ? 'Third Year' : $_year_level;
        $_year_level = $_level == '1' ? 'Fourth Year' : $_year_level;
        $file_name = strtoupper($_course->course_name . '-' . $_year_level . '_' . $_academic->school_year . "-" . $_academic->semester);
        return $pdf->setPaper($this->legal, 'landscape')->stream($file_name . '.pdf');
    }
}
