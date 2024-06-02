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
        $headerContent = [];
        foreach ($tableHeader as $key => $header) {
            $headerTitle = $header[0];
            $headerLink = $header[2];
            $content = [];
            foreach ($header[1] as $key => $headerChild) {
                foreach ($courses as $key => $value) {
                    $subHeaderContent[$value->course_name] = array(count($value[$headerChild]), $value->id);
                }
                $subHeader = $headerChild;
                $content[] = compact('subHeader', 'subHeaderContent');
            }
            $headerContent[] = compact('headerTitle', 'content', 'headerLink');
        }
        return view('livewire.summary-components.applicant-summary-overview', compact('courses', 'headerContent'));
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
}
