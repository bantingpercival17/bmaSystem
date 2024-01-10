<?php

namespace App\Http\Livewire\OnboardTraining;

use App\Models\Documents;
use Livewire\Component;
use App\Models\StudentDetails;
use Illuminate\Support\Facades\Cache;

class MidshipmanView extends Component
{
    public $profile = [];
    public $inputStudent;
    public $selectSort = 'document-requerments';
    public $activeCard = 'pre-deployment-requirements';
    public $showModal = false;
    public $documentLink = null;
    public function render()
    {
        $component_path = 'livewire.registrar.student.profile-components.';
        $subHeaders = array(
            array('profile', $component_path . 'information'),
            array('pre-deployment-requirements', $component_path . 'shipboard-requirements'),
            array('shipboard-application', $component_path . 'shipboard-application')
        );
        $sortList = array('all', 'document-requerments', 'onboard-enrollment');
        $studentLists =  $this->findStudent($this->inputStudent, $this->selectSort);
        $document_requirements = Documents::where('document_propose', 'DOCUMENTS-MONITORING')->where('department_id', 4)->get();
        $this->profile = request()->query('student') ? StudentDetails::find(base64_decode(request()->query('student'))) : $this->profile;
        if ($this->profile) {
            $document_requirements = Documents::leftJoin('document_requirements', 'document_requirements.document_id', 'documents.id')
            ->where('document_requirements.is_removed', false)
            ->where('documents.document_propose', 'DOCUMENTS-MONITORING')
            ->where('documents.department_id', 4)
            ->where('document_requirements.student_id', base64_decode(request()->query('student')))
            ->select('documents.*') 
            ->groupBy('documents.id')
            ->get();
        }
        return view('livewire.onboard-training.midshipman-view', compact('studentLists', 'subHeaders', 'document_requirements', 'sortList'));
    }
    function swtchTab($data)
    {
        $this->activeCard = $data;
        Cache::put('menu', $data, 60);
    }
    function findStudent($data, $sort)
    {
        $query = StudentDetails::select('student_details.id', 'student_details.first_name', 'student_details.last_name', 'student_details.middle_initial')
            ->where('student_details.is_removed', false);
        $_student = explode(',', $data); // Seperate the Sentence
        $_count = count($_student);
        if ($sort === 'document-requerments') {
            $query->join('document_requirements', 'document_requirements.student_id', 'student_details.id')
                ->join('documents', 'documents.id', 'document_requirements.document_id')
                ->where('documents.document_propose', 'DOCUMENTS-MONITORING')->where('documents.is_removed', false)
                ->where('document_requirements.document_status', false)->whereNull('document_requirements.deployment_id')
                ->orderBy('document_requirements.created_at', 'asc')
                ->groupBy('document_requirements.student_id');
        } else if ($sort === 'onboard-enrollment') {
            $query->join('ship_board_information', 'ship_board_information.student_id', 'student_details.id')
                ->whereNull('ship_board_information.is_approved')
                ->orderBy('ship_board_information.updated_at', 'desc');
        }
        if (is_numeric($data)) {
            $query = $query->join('student_accounts', 'student_accounts.student_id', 'student_details.id')
                ->where('student_accounts.student_number', 'like', '%' . $data . '%')
                ->orderBy('student_details.last_name', 'asc');
        } else {
            if ($_count > 1) {
                $query = $query->where('student_details.last_name', 'like', '%' . $_student[0] . '%')
                    ->where('student_details.first_name', 'like', '%' . trim($_student[1]) . '%')
                    ->orderBy('student_details.last_name', 'asc');
            } else {
                $query = $query->where('student_details.last_name', 'like', '%' . $_student[0] . '%')
                    ->orderBy('student_details.last_name', 'asc');
            }
        }

        return $query->get();
    }
    function showDocuments($data)
    {
        $this->showModal = true;
        $this->documentLink = null;
        $this->documentLink = $data;
    }
    function hideDocuments()
    {
        $this->showModal = false;
        $this->documentLink = null;
    }
}
