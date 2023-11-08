<?php

namespace App\Report;

use App\Models\CourseOffer;
use App\Models\Examination;
use Barryvdh\DomPDF\Facade as PDF;

class ApplicantReport
{
    public $path;
    public $legal;

    public function __construct()
    {
        $this->legal = [0, 0, 612.00, 1008.00];
        $this->path = "widgets.report.applicant";
    }

    public function applicant_form($_account)
    {
        $pdf = PDF::loadView("widgets.report.student.applicant_application_form", compact('_account'));
        $file_name = 'FORM RG-01 - ' . strtoupper($_account->applicant->last_name . ', ' . $_account->applicant->first_name . ' ' . $_account->applicant->middle_name);
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }

    public function applicant_examination_logs($_data)
    {
        // Set the Layout for the report
        $_layout = $this->path . '.examination-logs';
        // Import PDF Class
        $pdf = PDF::loadView($_layout, compact('_data'));
        // Set the Filename of report
        // Name format PART - SUBJECT CODE - DATE GENERATED
        $file_name = 'EXAMINATION SYSTEM LOGS -' . $_data->applicant_number . '-' . date('Ymd') . '.pdf';
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
    public function applicant_examination_result($_data)
    {
        // Set the Layout for the report
        $_layout = $this->path . '.examination-result';
        $_department = $_data->course_id == 3 ? 'SENIOR HIGHSCHOOL' : 'COLLEGE';
        $_examination = Examination::where('examination_name', 'ENTRANCE EXAMINATION')->where('department', $_department)->first();
        $_examination_categories = $_examination->categories;
        // Import PDF Class
        $pdf = PDF::loadView($_layout, compact('_data', '_examination_categories'));
        // Set the Filename of report

        $file_name = 'EXAMINATION RESULT - ' . $_data->applicant_number . '-' . date('Ymd') . '.pdf';
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
    public function applicant_examination_result_v2($data)
    {
        // Set the Layout for the report
        $_layout = $this->path . '.examination-result-v2';
        $_department = $data->applicant->course_id == 3 ? 'SENIOR HIGHSCHOOL' : 'COLLEGE';
        $_examination = Examination::where('examination_name', 'ENTRANCE EXAMINATION')->where('department', $_department)->first();
        $_examination_categories = $_examination->categories;
        // Import PDF Class
        $pdf = PDF::loadView($_layout, compact('data', '_examination_categories'));
        // Set the Filename of report
        $file_name = 'EXAMINATION RESULT - ' . $data->applicant->applicant_number . '-' . date('Ymd') . '.pdf';
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
    function applicant_vefied_list()
    {
        $_layout = $this->path . '.applicant-verified';
        // Import PDF Class
        $courses = CourseOffer::all();
        $pdf = PDF::loadView($_layout, compact('courses'));
        // Set the Filename of report
        $file_name = 'APPLICANT LIST [VERIFIED] - ' . date('Ymd') . '.pdf';
        return $pdf->setPaper($this->legal, 'portrait')->stream($file_name . '.pdf');
    }
}
