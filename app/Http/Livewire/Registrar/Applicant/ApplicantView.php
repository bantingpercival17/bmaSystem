<?php

namespace App\Http\Livewire\Registrar\Applicant;

use App\Models\AcademicYear;
use App\Models\ApplicantAccount;
use App\Models\CourseOffer;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ApplicantView extends Component
{
    public $selectCategories = 'for_checking';
    public $selectCourse = 'ALL COURSE';
    public $selectedCourse = 'ALL COURSE';
    public $searchInput;
    public $dataLists = [];
    public $academic = null;
    public function render()
    {
        $filterContent = array('pre-registered', 'registered', 'registration', 'for_checking', 'not_qualified', 'qualified_for_entrance_examination');
        $filterCourses = CourseOffer::all();
        $this->academic = $this->academicValue();
        $this->filterData();
        return view('livewire.registrar.applicant.applicant-view', compact('filterContent', 'filterCourses'));
    }
    function academicValue()
    {
        if ($this->academic === null) {
            $_academic = AcademicYear::where('is_active', 1)->first();
            $data = base64_encode($_academic->id);
        } else {
            $data =  request()->query('_academic') ?: $this->academic;
        }
        return $data;
    }
    function categoryCourse()
    {
        $course = 'ALL COURSE';
        if ($this->selectCourse != 'ALL COURSE') {
            $course = CourseOffer::find($this->selectCourse);
            $course = $course->course_name;
        }
        $this->selectedCourse = strtoupper($course);
    }
    function filterData()
    {
        $this->dataLists = [];
        $query = ApplicantAccount::select('applicant_accounts.*')
            ->where('applicant_accounts.is_removed', false)
            ->where('applicant_accounts.academic_id', base64_decode($this->academic));
        // Sort By Courses
        if ($this->selectCourse != 'ALL COURSE') {
            $query = $query->where('applicant_accounts.course_id', $this->selectCourse);
        }
        if ($this->searchInput != '') {
            $_student = explode(',', $this->searchInput); // Seperate the Sentence
            $_count = count($_student);
            if ($_count > 1) {
                $query = $query->where('applicant_detials.last_name', 'like', '%' . $_student[0] . '%')
                    ->where('applicant_detials.first_name', 'like', '%' . trim($_student[1]) . '%')
                    ->orderBy('applicant_detials.last_name', 'asc');
            } else {
                $query = $query->where('applicant_detials.last_name', 'like', '%' . $_student[0] . '%')
                    ->orderBy('applicant_detials.last_name', 'asc');
            }
        }
        switch ($this->selectCategories) {
            case 'pre-registered':
                $this->dataLists = $query->get();
                break;
            case 'registered':
                $this->dataLists = $query->join('applicant_detials', 'applicant_detials.applicant_id', 'applicant_accounts.id')
                    ->get();
                break;
            case 'registration':
                $this->dataLists = $query->leftJoin('applicant_documents', 'applicant_documents.applicant_id', 'applicant_accounts.id')
                    ->whereNull('applicant_documents.applicant_id')->get();
                break;
            case 'for_checking':
                $this->dataLists = $query->join('applicant_documents', 'applicant_documents.applicant_id', '=', 'applicant_accounts.id')
                    ->select('applicant_accounts.*', DB::raw('(SELECT COUNT(`is_approved`)
                FROM `applicant_documents`
                WHERE `applicant_documents`.`applicant_id` = `applicant_accounts`.`id`
                  AND `applicant_documents`.`is_removed` = 0
                  AND `applicant_documents`.`is_approved` = 1) AS ApprovedDocuments'), DB::raw('(
                    SELECT COUNT(bma_portal.documents.id)
                    FROM bma_portal.documents
                    WHERE bma_portal.documents.department_id = 2
                      AND bma_portal.documents.is_removed = false
                      AND bma_portal.documents.year_level = (
                          SELECT IF(bma_website.applicant_accounts.course_id = 3, 11, 4) as result
                          FROM bma_website.applicant_accounts
                          WHERE bma_website.applicant_accounts.id = 1
                      ))as documentCount'))
                    ->leftJoin('applicant_not_qualifieds as anq', 'anq.applicant_id', 'applicant_accounts.id')
                    ->whereNull('anq.applicant_id')
                    ->groupBy('applicant_accounts.id')
                    ->havingRaw('COUNT(applicant_documents.applicant_id) >= documentCount and ApprovedDocuments < documentCount')
                    ->get();
                break;
            case 'not_qualified':
                $this->dataLists = $query->join('applicant_not_qualifieds', 'applicant_not_qualifieds.applicant_id', 'applicant_accounts.id')
                    ->where('applicant_not_qualifieds.academic_id', base64_decode($this->academic))->get();
                break;
            case 'qualified_for_entrance_examination':
                $this->dataLists = $query->join('applicant_not_qualifieds', 'applicant_not_qualifieds.applicant_id', 'applicant_accounts.id')
                    ->where('applicant_not_qualifieds.academic_id', base64_decode($this->academic))
                    ->leftJoin('applicant_payments', 'applicant_payments.applicant_id', 'applicant_accounts.id')/* Applicant Payment */
                    ->whereNull('applicant_payments.applicant_id')->get();
                break;
            default:
                $this->dataLists = [];
                break;
        }
    }
}
